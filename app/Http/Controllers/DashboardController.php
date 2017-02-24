<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{

    // returns overview ajax part
    public function overview()
    {

        $all_running_apps = \DB::table('user_app')->where('launched', 1)->whereNotNull('ipv4')->get();

        if ($all_running_apps->count() == 0) {
            $all_running_apps = [];
        }

        return view('dashboard.overview', ['all_running_apps' => $all_running_apps]);
    }

    // returns user applications ajax part
    public function appsUser()
    {

        $all_user_apps = \DB::table('user_app')
            ->where(['user_id' => session('id'), 'has_permission' => 1])
            ->get();

        $available_user_apps = [];
        foreach ($all_user_apps as $user_app) {
            $app_data = \DB::table('app')->where('id', $user_app->app_id)->first();
            if ($app_data->app_type == 2) {
                if (!isset($user_app->name)) {
                    $user_app->name = session('full_name') . '\'s ' . $app_data->name;
                }
                if (!isset($user_app->description)) {
                    $user_app->description = $app_data->description;
                }
                $available_user_apps[] = $user_app;
            }

        }

        return view('dashboard.apps.user', ['available_user_apps' => $available_user_apps]);
    }

    // returns admin applications ajax part
    public function appsAdmin()
    {
        $all_user_apps = \DB::table('user_app')
            ->where(['user_id' => session('id'), 'has_permission' => 1])
            ->get();

        $available_admin_apps = [];
        foreach ($all_user_apps as $admin_app) {
            $app_data = \DB::table('app')->where('id', $admin_app->app_id)->first();
            if ($app_data->app_type == 1) {
                if (!isset($admin_app->name)) {
                    $admin_app->name = $app_data->name;
                }
                if (!isset($admin_app->description)) {
                    $admin_app->description = $app_data->description;
                }
                $available_admin_apps[] = $admin_app;
            }

        }

        return view('dashboard.apps.admin', ['available_admin_apps' => $available_admin_apps]);
    }

    // returns users list  ajax part
    public function users()
    {
        $all_users = \DB::table('user') -> get();

        return view('dashboard.users', ['all_users' => $all_users]);
    }

    public function message(){

        $endPointProvider = 'http://104.236.82.72/';

        $client = new \GuzzleHttp\Client();


        $res = $client->get($endPointProvider . 'clients/getMessage/2');

        return $res->getBody();
    }

    public function getGlobalNews(){
        $endPointProvider = 'http://104.236.82.72/';

        $client = new \GuzzleHttp\Client();


        $res = $client->get($endPointProvider . 'getGlobalNews');

        return $res->getBody();
    }
}
