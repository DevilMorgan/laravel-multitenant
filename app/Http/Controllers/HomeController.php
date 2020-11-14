<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show()
    {
        if (! auth()->check()) {
            return view('welcome');
        } else {
            $subscribersCount = Tenant::count();
            $usersCount = User::count();
            $loginsCount = Login::count();
            return view('dashboard', [
                'subscribersCount' => $subscribersCount,
                'usersCount' => $usersCount,
                'loginsCount' => $loginsCount,
            ]);
        }
    }
}
