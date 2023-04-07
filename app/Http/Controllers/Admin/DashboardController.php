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
                'name' => 'Pengajuan Baru',
                'count' => Permohonan::where('status', 1)->count(),
                'bg_color' => '#435ebe',
            ],
            [
                'name' => 'Sedang Verifikasi',
                'count' => Permohonan::where('status', 2)->count(),
                'bg_color' => '#ffc107',
            ],
            [
                'name' => 'Revisi',
                'count' => Permohonan::where('status', 3)->count(),
                'bg_color' => '#dc3545',
            ],
            [
                'name' => 'Verifikasi Ulang',
                'count' => Permohonan::where('status', 4)->count(),
                'bg_color' => '#f39c12',
            ],
            [
                'name' => 'Diterima',
                'count' => Permohonan::where('status', 5)->count(),
                'bg_color' => '#198754',
            ],
            [
                'name' => 'Ditolak',
                'count' => Permohonan::where('status', 6)->count(),
                'bg_color' => '#6c757d',
            ],
        ]);
        // casting to nested collection
        $status_permohonan = $status_permohonan->map(function($item){
            return collect($item);
        });

        return view('pages.admin.dashboard.index', compact('status_permohonan'));
    }
}
