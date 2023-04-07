<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('pages.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        // validate password
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        DB::beginTransaction();
        try {
            // update password
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah data')->withInput();
        }

        // redirect
        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah');
    }
}
