<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Scopes\TenantScope;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function leave()
    {
        if(!session()->has('impersonate')){
            abort(403);
        }

        Auth::login(User::withoutGlobalScope(TenantScope::class)->findOrFail(Session::get('impersonate')));

        Session::forget('impersonate');

        return redirect(route('home'));
    }
}
