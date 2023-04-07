<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $status_permohonan = collect([
            [
                'name' => 'Total User',
                'count' => User::isUser()->count(),
                'bg_color' => '#f56954',
            ],
            [
                'name' => 'Total Usulan',
                'count' => Permohonan::where('status', 0)->count(),
                'bg_color' => '#f39c12',
            ],
            [
                'name' => 'Pengajuan Baru',
                'count' => Permohonan::where('status', 1)->count(),
                'bg_color' => '#00c0ef',
            ],
            [
                'name' => 'Sedang Verifikasi',
                'count' => Permohonan::where('status', 2)->count(),
                'bg_color' => '#00a65a',
            ],
            [
                'name' => 'Revisi',
                'count' => Permohonan::where('status', 3)->count(),
                'bg_color' => '#f56954',
            ],
            [
                'name' => 'Verifikasi Ulang',
                'count' => Permohonan::where('status', 4)->count(),
                'bg_color' => '#f39c12',
            ],
            [
                'name' => 'Diterima',
                'count' => Permohonan::where('status', 5)->count(),
                'bg_color' => '#00c0ef',
            ],
            [
                'name' => 'Ditolak',
                'count' => Permohonan::where('status', 6)->count(),
                'bg_color' => '#00a65a',
            ],
        ]);
        // casting to nested collection
        $status_permohonan = $status_permohonan->map(function($item){
            return collect($item);
        });

        return view('pages.admin.dashboard.index', compact('status_permohonan'));
    }
}
