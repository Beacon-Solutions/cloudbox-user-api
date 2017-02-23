<?php

namespace App\Http\Controllers;


class InstanceController extends Controller
{

    // launches docker instance in openstack
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

        // builds the instance options
        $options = [
            'name' => $userName . $app->name,
            'imageId' => $app->image_id,
            'flavorId' => $app->flavoud_id,

            // Network id
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

}