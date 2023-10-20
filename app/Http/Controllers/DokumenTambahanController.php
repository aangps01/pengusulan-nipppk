<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DokumenWajibTambahan;

class DokumenTambahanController extends Controller
{
    public function index()
    {
        $dokumens_tambahan_user = DokumenWajibTambahan::whereHas('user', function ($query) {
            $query->where('id', auth()->user()->id);
        })->get();
        $dokumens = collect([
            [
                'nama' => 'Surat Keputusan P3K',
                'kode' => 'SKP3K',
                'is_upload' => $dokumens_tambahan_user->where('kode_dokumen', 'SKP3K')->count() > 0 ? true : false,
                'filepath' => $dokumens_tambahan_user->where('kode_dokumen', 'SKP3K')->count() > 0 ? $dokumens_tambahan_user->where('kode_dokumen', 'SKP3K')->first()->filepath : '',
            ],
            [
                'nama' => 'Surat Keputusan Calon P3K',
                'kode' => 'SKCALONP3K',
                'is_upload' => $dokumens_tambahan_user->where('kode_dokumen', 'SKCALONP3K')->count() > 0 ? true : false,
                'filepath' => $dokumens_tambahan_user->where('kode_dokumen', 'SKCALONP3K')->count() > 0 ? $dokumens_tambahan_user->where('kode_dokumen', 'SKCALONP3K')->first()->filepath : '',
            ]
        ]);
        $is_all_upload = $dokumens->where('is_upload', false)->count() > 0 ? false : true;
        return view('pages.user.dokumen-tambahan.index', compact('dokumens', 'is_all_upload'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'berkas.*' => 'required|mimes:pdf|max:2048',
        ]);

        $dokumens = collect([
            [
                'nama' => 'Surat Keputusan P3K',
                'kode' => 'SKP3K',
            ],
            [
                'nama' => 'Surat Keputusan Calon P3K',
                'kode' => 'SKCALONP3K',
            ]
        ]);

        foreach ($dokumens as $dokumen) {
            if ($request->hasFile('berkas.' . $dokumen['kode'])) {
                $file = $request->file('berkas.' . $dokumen['kode']);
                $filename = Str::random(10) . '-' . $dokumen['kode'] . '.' . $file->getClientOriginalExtension();
                $filepath = $file->storeAs('dokumen-tambahan', $filename, 'public');
                DokumenWajibTambahan::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'kode_dokumen' => $dokumen['kode'],
                    ],
                    [
                        'nama_dokumen' => $dokumen['nama'],
                        'filepath' => $filepath,
                    ]
                );
            }
        }

        // if user has permohonan
        if (auth()->user()->permohonan) {
            // if all dokumen wajib tambahan is uploaded, set is_upload_dokumen_wajib_tambahan to true
            if (auth()->user()->dokumenWajibTambahan->count() == count($dokumens)) {
                auth()->user()->permohonan->update([
                    'is_upload_dokumen_wajib_tambahan' => true,
                ]);
            }else{
                auth()->user()->permohonan->update([
                    'is_upload_dokumen_wajib_tambahan' => false,
                ]);
            }
        }

        return redirect()->route('user.dokumen-tambahan.index')->with('success', 'Dokumen tambahan berhasil diunggah');
    }
}
