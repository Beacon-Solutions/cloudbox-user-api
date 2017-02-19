<?php

namespace App\Http\Controllers;


class UserController extends Controller
{

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
                'password' => $password,
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

        $ldap = ldap_connect("localhost");

        if (isset($ldap)) {
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

            if ($bind = ldap_bind($ldap, "cn=admin,dc=cloudbox,dc=com", "admin")) {
                $info["cn"] = $fullName;
                $names = explode(" ", $fullName);
                $info["sn"] = array_pop($names);
                $info["uid"] = $userName;
                $info["objectclass"] = array("top","person", "uidObject");
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
}