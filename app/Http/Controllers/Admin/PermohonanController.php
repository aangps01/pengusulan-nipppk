<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DokumenWajibTambahan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailBerkasPermohonan;
use Illuminate\Support\Facades\Storage;
use App\Exports\Exports\LaporanPermohonanExport;
use Illuminate\Contracts\Encryption\DecryptException;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.admin.permohonan.index');
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $start = $request->input('start');
            $length = $request->input('length');
            $draw = $request->input('draw');
            $search = $request->input('search');

            $query = Permohonan::with([
                'user'
            ]);

            // Total records
            $totalRecords = $query->count();

            if ($request->status) {
                $query->where('status', $request->status);
            }

            // Filter records
            if ($search || $request->input('status') || $request->input('tanggal_pengajuan') || $request->input('tanggal_validasi') || $request->input('jenis')) {
                if ($search) {
                    $query->whereHas('user', function ($q) use ($search) {
                        $q->where('nik', 'like', '%' . $search['value'] . '%')
                        ->orWhere('name', 'like', '%' . $search['value'] . '%');
                    });
                }

                if ($request->status) {
                    $query->where('status', $request->status);
                }

                if ($request->tanggal_pengajuan) {
                    // 28/01/2023 - 28/01/2023
                    $tanggal_pengajuan = explode(' - ', $request->tanggal_pengajuan);
                    $start_date = Carbon::createFromFormat('d/m/Y', $tanggal_pengajuan[0], 'GMT+8')->startOfDay();
                    $end_date = Carbon::createFromFormat('d/m/Y', $tanggal_pengajuan[1], 'GMT+8')->endOfDay();
                    $query->whereBetween('created_at', [
                        $start_date,
                        $end_date
                    ]);
                }

                if ($request->tanggal_validasi) {
                    // 28/01/2023 - 28/01/2023
                    $tanggal_validasi = explode(' - ', $request->tanggal_validasi);
                    $start_date = Carbon::createFromFormat('d/m/Y', $tanggal_validasi[0], 'GMT+8')->startOfDay();
                    $end_date = Carbon::createFromFormat('d/m/Y', $tanggal_validasi[1], 'GMT+8')->endOfDay();
                    $query->whereBetween('tanggal_validasi', [
                        $start_date,
                        $end_date
                    ]);
                }

                if ($request->jenis) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('tipe', $request->jenis);
                    });
                }
                $totalFiltered = $query->count();
            } else {
                $totalFiltered = $totalRecords;
            }

            // Offset and limit
            if ($start != 0 || $length != -1) {
                $query = $query->offset($start)
                    ->limit($length);
            }

            $records = $query->get()
                ->map(function ($item) {
                    return collect([
                        'id' => encrypt($item->id),
                        'nik' => $item->user->nik,
                        'nama' => $item->user->name,
                        'status' => $item->badge_status,
                        'tanggal_pengajuan' => $item->created_at->format('d/m/Y H:i'),
                        'tanggal_validasi' => $item->tanggal_validasi ? $item->tanggal_validasi->format('d/m/Y H:i') : '-',
                        'aksi' => $item->status == 1 ? '<a href="' . route('admin.permohonan.verifikasi', encrypt($item->id)) . '" class="btn btn-primary btn-sm">Verifikasi</a>' : '<a href="' . route('admin.permohonan.verifikasi', encrypt($item->id)) . '" class="btn btn-primary btn-sm">Detail</a>',
                    ]);
                });
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $records,
            ]);
        }
    }

    public function verifikasi(Request $request, $id)
    {
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

        try {
            $permohonan = Permohonan::with([
                'user',
                'berkasPermohonan' => function ($query) {
                    $query->with([
                        'berkasPersyaratan',
                        'detailBerkasPermohonan' => function ($query) {
                            $query->orderByDesc('created_at');
                        },
                    ]);
                },
            ])
                ->where('id', decrypt($id))
                ->first();

            $is_semua_berkas_terverifikasi = $permohonan->berkasPermohonan->every(function ($item) {
                return $item->detailBerkasPermohonan->first()->status != 'Belum Verifikasi';
            });

            $berkas_permohonan = $permohonan->berkasPermohonan->map(function ($item) use ($permohonan) {
                return collect([
                    'key' => $item->berkasPersyaratan->berkas_key,
                    'nama' => $item->berkasPersyaratan->nama,
                    'is_required' => $item->berkasPersyaratan->is_required,
                    'catatan_revisi' => $item->detailBerkasPermohonan->first()?->keterangan,
                    'filename' => $item->detailBerkasPermohonan->first()?->generated_filename,
                    'filepath' => Storage::url($item->detailBerkasPermohonan->first()?->filepath),
                    'badge_status' => $item->detailBerkasPermohonan->first()?->badge_status,
                    'status' => $item->detailBerkasPermohonan->first()?->status,
                    'keterangan' => $item->detailBerkasPermohonan->first()?->keterangan,
                    'detail_berkas_id' => encrypt($item->detailBerkasPermohonan->first()?->id),
                ]);
            });

            // jika permohonan status 1, ubah jadi 2
            DB::beginTransaction();
            try {
                if ($permohonan->status == 1) {
                    $permohonan->update([
                        'status' => 2,
                    ]);
                }

                // get all dokumen wajib tambahan
                $dokumens_tambahan_user = DokumenWajibTambahan::where('user_id', $permohonan->user_id)->get();
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

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getFile() . $e->getLine() . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan pada server');
            }
        } catch (DecryptException $e) {
            abort(404);
        }
        return view('pages.admin.permohonan.verifikasi', compact('permohonan', 'berkas_permohonan', 'is_semua_berkas_terverifikasi', 'dokumens'));
    }

    public function validBerkas(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $request->validate([
            'id' => 'required',
        ]);

        $detail_berkas_permohonan = DetailBerkasPermohonan::with('berkasPermohonan.permohonan')
            ->where('id', decrypt($request->id))
            ->where('is_valid', false)
            ->whereHas('berkasPermohonan', function ($query) {
                $query->whereHas('permohonan', function ($query) {
                    $query->whereNotIn('status', [3, 5, 6]); // revisi, diterima, ditolak
                });
            })
            ->firstOrFail();
        DB::beginTransaction();
        try {
            $detail_berkas_permohonan->update([
                'is_valid' => true,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berkas berhasil divalidasi',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memvalidasi berkas',
            ]);
        }
    }

    public function revisiBerkas(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $request->validate([
            'id' => 'required',
            'catatan_revisi' => 'required',
        ]);

        $detail_berkas_permohonan = DetailBerkasPermohonan::with('berkasPermohonan.permohonan')
            ->where('id', decrypt($request->id))
            ->where('is_valid', false)
            ->whereHas('berkasPermohonan', function ($query) {
                $query->whereHas('permohonan', function ($query) {
                    $query->whereNotIn('status', [3, 5, 6]); // revisi, diterima, ditolak
                });
            })
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $detail_berkas_permohonan->update([
                'is_revisi' => true,
                'keterangan' => $request->catatan_revisi,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berkas berhasil direvisi',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat merevisi berkas',
            ]);
        }
    }

    public function selesaiPermohonan(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $request->validate([
            'id' => 'required',
        ]);

        // cek semua berkas sudah divalidasi atau belum
        $permohonan = Permohonan::with([
            'berkasPermohonan' => function ($query) {
                $query->with([
                    'detailBerkasPermohonan' => function ($query) {
                        $query->orderByDesc('created_at');
                    },
                ]);
            },
        ])
            ->where('id', decrypt($request->id))
            ->whereNotIn('status', [3, 5, 6]) // revisi, diterima, ditolak
            ->firstOrFail();
        $valid_count = $permohonan->berkasPermohonan->filter(function ($item) {
            return $item->detailBerkasPermohonan->first()->is_valid;
        })->count();
        $revisi_count = $permohonan->berkasPermohonan->filter(function ($item) {
            return $item->detailBerkasPermohonan->first()->is_revisi;
        })->count();
        $total_berkas = $permohonan->berkasPermohonan->count();

        if ($total_berkas != $valid_count + $revisi_count) {
            return response()->json([
                'status' => 'error',
                'message' => 'Masih terdapat berkas yang belum divalidasi',
            ]);
        }

        // cek apakah semua berkas telah valid
        $is_semua_valid = $revisi_count == 0 && $valid_count == $total_berkas ? true : false;

        DB::beginTransaction();
        try {
            if ($is_semua_valid) {
                $permohonan->update([
                    'status' => 5,
                    'tanggal_validasi' => now(),
                    'validator_id' => auth()->user()->id,
                ]);
            } else {
                $permohonan->update([
                    'status' => 3,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Permohonan berhasil diselesaikan',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyelesaikan permohonan',
            ]);
        }
    }

    public function tolakPermohonan(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $request->validate([
            'id' => 'required',
            'keterangan' => 'required',
        ]);

        $permohonan = Permohonan::where('id', decrypt($request->id))
            ->whereNotIn('status', [3, 5, 6]) // revisi, diterima, ditolak
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $permohonan->update([
                'status' => 6,
                'keterangan' => $request->keterangan,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Permohonan berhasil ditolak',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menolak permohonan',
            ]);
        }
    }

    public function resetPermohonan(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $request->validate([
            'id' => 'required',
        ]);

        $permohonan = Permohonan::with('berkasPermohonan.detailBerkasPermohonan')->where('id', decrypt($request->id))
            ->whereIn('status', [4, 5]) // revisi, diterima, ditolak
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $permohonan->update([
                'status' => 4,
                'tanggal_validasi' => null,
                'validator_id' => null,
            ]);

            // permohonan has many berkas permohonan has manu detail berkas permohonan. Edit all detail berkas permohonan is_valid to 0
            $permohonan->berkasPermohonan->map(function ($item) {
                $item->detailBerkasPermohonan->map(function ($item) {
                    $item->update([
                        'is_valid' => false,
                        'is_revisi' => false,
                        'keterangan' => null,
                    ]);
                });
            });

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Permohonan berhasil direset',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getFile() . $e->getLine() . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mereset permohonan',
            ]);
        }
    }

    public function laporanExport(Request $request)
    {
        $query = Permohonan::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->tanggal_pengajuan) {
            // 28/01/2023 - 28/01/2023
            $tanggal_pengajuan = explode(' - ', $request->tanggal_pengajuan);
            $start_date = Carbon::createFromFormat('d/m/Y', $tanggal_pengajuan[0], 'GMT+8')->startOfDay();
            $end_date = Carbon::createFromFormat('d/m/Y', $tanggal_pengajuan[1], 'GMT+8')->endOfDay();
            $query->whereBetween('created_at', [
                $start_date,
                $end_date
            ]);
        }

        if ($request->tanggal_validasi) {
            // 28/01/2023 - 28/01/2023
            $tanggal_validasi = explode(' - ', $request->tanggal_validasi);
            $start_date = Carbon::createFromFormat('d/m/Y', $tanggal_validasi[0], 'GMT+8')->startOfDay();
            $end_date = Carbon::createFromFormat('d/m/Y', $tanggal_validasi[1], 'GMT+8')->endOfDay();
            $query->whereBetween('tanggal_validasi', [
                $start_date,
                $end_date
            ]);
        }

        $permohonans = $query->get();

        return Excel::download(new LaporanPermohonanExport($permohonans), 'laporan-permohonan.xlsx');
    }
}
