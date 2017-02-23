<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{

    // basic layout page
    public function index()
    {
        $user = session('username');
        if (!isset($user)) {
            return redirect('/login');
        }
        $menu_items = [];
        if (session('type') == 1) {
            $menu_items = [
                [
                    'name' => 'Overview',
                    'url' => '/dashboard/overview'
                ],
                [
                    'name' => 'My Apps',
                    'url' => '/dashboard/apps/user'
                ],
                [
                    'name' => 'Admin Apps',
                    'url' => '/dashboard/apps/admin'
                ],
                [
                    'name' => 'Users',
                    'url' => '/dashboard/users'
                ]
            ];
        } elseif (session('type') == 2) {
            $menu_items = [
                [
                    'name' => 'Overview',
                    'url' => '/dashboard/overview'
                ],
                [
                    'name' => 'My Apps',
                    'url' => '/dashboard/apps/user'
                ]
            ];
        }

        $session_data = [
            'username' => session('username'),
            'full_name' => session('full_name'),
            'menu_items' => $menu_items
        ];
        return view('index', compact('session_data'));
    }
}