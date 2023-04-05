<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingDashboardController extends Controller
{
    public function index(){
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        if (auth()->user()->is_admin())
            return redirect()->route('admin.dashboard.index');
        else if (auth()->user()->is_user())
            return redirect()->route('user.dashboard.index');
        else{
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun anda tidak memiliki hak akses.');
        }
    }
}
