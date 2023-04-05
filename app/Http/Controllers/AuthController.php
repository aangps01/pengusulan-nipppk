<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('pages.login');
    }
}
