<?php

namespace App\Http\Controllers;

// controller class fpr user management
class UserController extends Controller
{

    private $ldapDomain = "localhost";
    private $ldapAdmin = "cn=admin,dc=cloudbox,dc=com";
    private $ldapAdminPass = "admin";

    public function addUser()
    {

        $userName = request()->input('username');
        $password = request()->input('password');
        $fullName = request()->input('full_name');
        $position = request()->input('position');

        $user = \DB::table('user')->where('username', $userName)->first();

        if (empty($userName)) {
            return response()->json([
                'error' => true,
                'msg' => 'Username is required.'
            ]);
        }

        if (empty($password)) {
            return response()->json([
                'error' => true,
                'msg' => 'Password is required.'
            ]);
        }
        if (empty($fullName)) {
            return response()->json([
                'error' => true,
                'msg' => 'Full Name is required.'
            ]);
        }
        if (empty($position)) {
            return response()->json([
                'error' => true,
                'msg' => 'Position is required.'
            ]);
        }

        if (isset($user)) {
            return response()->json([
                'error' => true,
                'msg' => 'Username already exist.'
            ]);
        }

        \DB::table('user')->insert(
            [
                'username' => $userName,
                'password' => \Hash::make($password),
                'full_name' => $fullName,
                'role' => 2,
                'position' => $position
            ]
        );

        $newUser = \DB::table('user')->where('username', $userName)->first();

        if (!isset($newUser)) {
            return response()->json([
                'error' => true,
                'msg' => 'Error adding new user.'
            ]);
        }

        $ldap = ldap_connect($this->ldapDomain);

        if (isset($ldap)) {
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

            if ($bind = ldap_bind($ldap, $this->ldapAdmin, $this->ldapAdminPass)) {
                $info["cn"] = $fullName;
                $names = explode(" ", $fullName);
                $info["sn"] = array_pop($names);
                $info["uid"] = $userName;
                $info["objectclass"] = array("top", "person", "uidObject");
                $info['userPassword'] = $password;
                ldap_add($ldap, "uid=$userName, dc=cloudbox,dc=com", $info);
                ldap_close($ldap);
            }
        }

        return response()->json([
            'error' => false,
            'msg' => 'success'
        ]);
    }

    public function getUser($id)
    {
        $user = \DB::table('user')->where('id', $id)->first();

        if (!isset($user)) {
            return response('', 204);
        }

        $userApps = \DB::table('user_app')->where('user_id', $user->id)->get();
        $appList = [];

        foreach ($userApps as $userApp) {
            $app = \DB::table('app')->where('id', $userApp->app_id)->first();
            $appList[] = [
                'id' => $userApp->id,
                'app_name' => $app->name,
                'has_permission' => $userApp->has_permission
            ];
        }

        $userData = [
            'user_id' => $user->id,
            'username' => $user->username,
            'full_name' => $user->full_name,
            'position' => $user->position,
            'app_list' => $appList
        ];
        return view('dashboard.usermanage', compact('userData'));
    }

    public function updateUser()
    {

        $userID = request()->input('user_id');
        $fullName = request()->input('full_name');
        $position = request()->input('position');

        $user = \DB::table('user')->where('id', $userID)->first();

        if (!isset($user)) {
            return response()->json([
                'error' => true,
                'msg' => 'No matching user.'
            ]);
        }

        $userApps = \DB::table('user_app')->where('user_id', $user->id)->get();

        if ($userApps->count() == 0) {
            return response()->json([
                'error' => true,
                'msg' => 'Cannot update: Not matching apps found'
            ]);
        }

        \DB::table('user')->where('id', $user->id)->update(['full_name' => $fullName, 'position' => $position]);

        foreach ($userApps as $userApp) {
            $userAppID = request()->input('app_' . $userApp->id);
            if (isset($userAppID) && !empty($userAppID)) {
                \DB::table('user_app')->where('id', $userApp->id)->update(['has_permission' => 1]);
            } else {
                \DB::table('user_app')->where('id', $userApp->id)->update(['has_permission' => 0]);
            }
        }

        return response()->json([
            'error' => false,
            'msg' => 'success'
        ]);
    }

    public function profile()
    {

        $user = \DB::table('user')->where('id', session('id'))->first();

        if (!isset($user)) {
            return response('', 204);
        }


        $userData = [
            'user_id' => $user->id,
            'username' => $user->username,
            'full_name' => $user->full_name,
            'position' => $user->position
        ];

        return view('dashboard.profile', compact('userData'));
    }

    // updates user profile
    public function updateProfile()
    {

        $userID = request()->input('user_id');
        $fullName = request()->input('full_name');
        $currentPassword = request()->input('current_password');
        $newPassword = request()->input('new_password');
        $confirmNewPassword = request()->input('confirm_new_password');

        $user = \DB::table('user')->where('id', $userID)->first();

        if (!isset($user)) {
            return response()->json([
                'error' => true,
                'msg' => 'No matching user.'
            ]);
        }

        if (!\Hash::check($currentPassword,$user->password)) {
            return response()->json([
                'error' => true,
                'msg' => 'Update failed. Wrong password.'
            ]);
        }

        if (!isset($fullName) || trim($fullName) === '') {
            return response()->json([
                'error' => true,
                'msg' => 'Full name cannot be empty.'
            ]);
        }

        $updatePassword = false;

        if ((isset($newPassword) && trim($newPassword) != '') || (isset($confirmNewPassword) && trim($confirmNewPassword) != '')) {
            if (strcasecmp($newPassword, $confirmNewPassword) != 0) {
                return response()->json([
                    'error' => true,
                    'msg' => 'New password confirmation failed.'
                ]);
            }
            $updatePassword = true;
        }

        $success = false;
        if ($updatePassword) {
            $success = \DB::table('user')->where('id', $user->id)->update(['full_name' => $fullName, 'password' => \Hash::make($newPassword)]);
        } else {
            $success = \DB::table('user')->where('id', $user->id)->update(['full_name' => $fullName]);
        }

        if ($success) {

            $ldap = ldap_connect($this->ldapDomain);

            if (isset($ldap)) {
                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

                if ($bind = ldap_bind($ldap, $this->ldapAdmin, $this->ldapAdminPass)) {
                    $info["cn"] = $fullName;
                    $names = explode(" ", $fullName);
                    $info["sn"] = array_pop($names);
                    $info['userPassword'] = $newPassword;
                    ldap_modify($ldap, "uid=$user->username, dc=cloudbox,dc=com", $info);
                    ldap_close($ldap);
                }
            }

            $updatedUser = \DB::table('user')->where('id', $userID)->first();
            session(['full_name' => $updatedUser->full_name]);
        }

        return response()->json([
            'error' => false,
            'msg' => 'Update Success.'
        ]);

    }

    public function resetPassword()
    {

        $username = request()->input('recover_username');

        $user = \DB::table('user')->where('username', $username)->first();

        if (isset($user)) {
            $generatedPassword = \Hash::make($this->generatePassword(12));

            \DB::table('user')->where('id', $user->id)->update(['password' => $generatedPassword]);

            $endPointProvider = 'http://104.236.82.72/';

            $client = new \GuzzleHttp\Client();

            \Log::info("Password reset successful");

//            $res = $client->post($endPointProvider . 'postPerformance',
//                [
//                    'form_params' => [
//                        'client_id' => '2',
//                        'client_cpu_usage' => $cpuPercent,
//                        'client_memory_usage' => $memPercent,
//                        'client_storage_usage' => $storagePercent
//                    ]
//                ]);

        }

        return response()->json([
            'msg' => 'If you are a valid user, your password has been reset. Please contact your CloudBox provider to get the new password.'
        ]);
    }

    function generatePassword($length = 8)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}