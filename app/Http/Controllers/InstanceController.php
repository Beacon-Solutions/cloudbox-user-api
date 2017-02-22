<?php

namespace App\Http\Controllers;


class InstanceController extends Controller
{

    public function createInstance()
    {
        $userName = session('username');
        $appID = request()->input('app_id');
        $usrApp = \DB::table('user_app')->where('id', $appID)->first();
        $app = \DB::table('app')->where('id', $usrApp->app_id)->first();


        $openstack = new \OpenStack\OpenStack([
            'authUrl' => 'http://10.0.0.100:5000/v3/',
            'region' => 'RegionOne',
            'user' => [
                'name' => 'demo',
                'password' => '12345678',
                'domain' => ['name' => 'default']
            ],
            'scope' => ['project' => ['id' => 'a15ee72567894c3eae93df9c0da02bed']]
        ]);


        $compute = $openstack->computeV2(['region' => 'RegionOne']);

        $options = [
            // Required
            'name' => $userName . $app->name,
            'imageId' => $app->image_id,
            'flavorId' => $app->flavoud_id,

//    // Required if multiple network is defined
            'networks' => [
                ['uuid' => '6e240bd7-3ffa-4cab-bccd-3e40ded21c43']
            ]
        ];


        $server = $compute->createServer($options);
        usleep(10000000);
        $server->retrieve();
        $ipv4 = array_values($server->addresses)[0][0]['addr'];
        if (isset($ipv4)) {

            $usr = \DB::table('user')->where('id', session('id'))->first();

            \DB::table('user_app')
                ->where('id', $appID)
                ->update(['launched' => 1, 'ipv4' => $ipv4, 'name' => $usr->full_name . '\'s ' . $app->name, 'description' => $app->description]);

        }

        return "success";
    }

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