<?php

namespace App\Http\Controllers\User;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use App\Models\BerkasSementara;
use App\Models\BerkasPersyaratan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BerkasPermohonan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        $berkas_persyaratan = BerkasPersyaratan::with([
            'berkasPermohonan' => function ($query) {
                $query->with([
                    'detailBerkasPermohonan' => function ($query) {
                        $query->orderByDesc('created_at');
                    },
                    'permohonan'
                ])
                    ->whereHas('permohonan', function ($query) {
                        $query->where('user_id', auth()->user()->id);
                    });
            },
            'berkasSementara' => function ($query) {
                $query->where('user_id', auth()->user()->id)->orderByDesc('created_at');
            },
        ])
            // ->active()
            ->get()
            ->map(function ($berkas_persyaratan) {
                // $tipe_format = // 'tipe_format' => pdf, jpg => .pdf, .jpg
                return collect([
                    'id' => $berkas_persyaratan->id,
                    'berkas_key' => $berkas_persyaratan->berkas_key,
                    'nama' => $berkas_persyaratan->nama,
                    'is_required' => $berkas_persyaratan->is_required,
                    'batas_ukuran' => $berkas_persyaratan->batas_ukuran,
                    'nama_format' => $berkas_persyaratan->nama_format,
                    'tipe_format' => $berkas_persyaratan->tipe_format,
                    'permohonan_badge_status' => $berkas_persyaratan->berkasPermohonan->first()?->permohonan->badge_status,
                    'berkas_filename' => $berkas_persyaratan->berkasPermohonan->first()?->detailBerkasPermohonan->first()->generated_filename,
                    'berkas_filepath' => $berkas_persyaratan->berkasSementara->first()?->filepath ?? $berkas_persyaratan->berkasPermohonan->first()?->detailBerkasPermohonan->first()->filepath,
                    'status' => $berkas_persyaratan->berkasPermohonan->first()?->detailBerkasPermohonan->first()->status,
                    'catatan_revisi' => $berkas_persyaratan->berkasPermohonan->first()?->detailBerkasPermohonan->first()->keterangan,
                    'berkas_badge_status' => $berkas_persyaratan->berkasPermohonan->first()?->detailBerkasPermohonan->first()->badge_status,
                ]);
            });

        $permohonan = Permohonan::where('user_id', auth()->user()->id)->first();

        $status_permohonan = !$permohonan ? 0 : $permohonan->status;
        return view('pages.user.pengajuan.index', compact('berkas_persyaratan', 'status_permohonan', 'permohonan'));
    }

    public function uploadBerkas(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        // cek berkas persyaratan apakah ada
        $berkas_persyaratan_id = decrypt($request->input('berkas_persyaratan_id'));
        $berkas_persyaratan = BerkasPersyaratan::findOrFail($berkas_persyaratan_id);

        // build rules
        $rules = [
            'berkas' => 'required|mimes:' . str_replace('.', '', $berkas_persyaratan->tipe_format) . '|max:' . $berkas_persyaratan->batas_ukuran,
        ];

        // validasi
        $validator = Validator::make($request->all(), $rules, [
            'berkas.required' => 'Berkas tidak boleh kosong',
            'berkas.mimes' => 'Berkas harus berupa ' . $berkas_persyaratan->nama_format,
            'berkas.max' => 'Berkas maksimal ' . $berkas_persyaratan->batas_ukuran . ' KB',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // cek apakah ada request permohonan_id
        $permohonan_id = null;
        if ($request->permohonan_id) {
            $permohonan_id = decrypt($request->input('permohonan_id'));
            $permohonan = Permohonan::findOrFail($permohonan_id);
        }

        // insert berkas sementara
        DB::beginTransaction();
        try {
            $berkas_sementara = BerkasSementara::create([
                'user_id' => auth()->user()->id,
                'berkas_persyaratan_id' => $berkas_persyaratan_id,
                'permohonan_id' => $permohonan_id,
                'filepath' => $request->file('berkas')->store('public/berkas'),
                'filename' => $request->file('berkas')->getClientOriginalName(),
            ]);

            // delete berkas sementara yang sudah ada serta berkas storage
            $berkas_trash = BerkasSementara::where('user_id', auth()->user()->id)
                ->where('berkas_persyaratan_id', $berkas_persyaratan_id)
                ->where('permohonan_id', $permohonan_id)
                ->where('id', '!=', $berkas_sementara->id)
                ->get();
            foreach ($berkas_trash as $berkas) {
                Storage::delete($berkas->filepath);
                $berkas->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengunggah berkas',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Berkas berhasil diunggah',
            'data' => [
                'berkas_url' => Storage::url($berkas_sementara->filepath),
            ]
        ]);
    }

    public function update(Request $request)
    {
        // note :
        // 1. tolak permohonan jika semua berkas belum diupload => mau pengajuan baru

        // CASE PERMOHONAN BELUM ADA
        if (!Permohonan::where('user_id', auth()->user()->id)->exists()) {
            // build rules
            $berkas_persyaratan = BerkasPersyaratan::get();
            // cek semua berkas persyaratan required apakah ada di berkas sementara
            foreach ($berkas_persyaratan as $berkas) {
                if ($berkas->is_required) {
                    if (!BerkasSementara::where('user_id', auth()->user()->id)->where('berkas_persyaratan_id', $berkas->id)->whereNull('permohonan_id')->exists()) {
                        return redirect()->back()->with('error', 'Berkas ' . $berkas->nama . ' belum diupload');
                    }
                }
            }

            DB::beginTransaction();
            try {
                // create permohonan
                $permohonan = Permohonan::create([
                    'user_id' => auth()->user()->id,
                    'status' => 1,
                ]);

                // insert berkas permohonan
                $berkas_sementara = BerkasSementara::with('berkasPersyaratan')->where('user_id', auth()->user()->id)->whereNull('permohonan_id')->get();
                foreach ($berkas_sementara as $berkas) {
                    // set generated filename => nama berkas persyaratan + berkas nama asli get last extension
                    $generated_filename = $berkas->berkasPersyaratan->nama . '.' . pathinfo($berkas->filename, PATHINFO_EXTENSION);
                    // insert berkas permohonan
                    $permohonan->berkasPermohonan()->create([
                        'berkas_persyaratan_id' => $berkas->berkas_persyaratan_id,
                        'permohonan_id' => $permohonan->id,
                    ])->detailBerkasPermohonan()->create([
                        'filepath' => $berkas->filepath,
                        'original_filename' => $berkas->filename,
                        'generated_filename' => $generated_filename,
                        'is_valid' => 0,
                        'is_revisi' => 0,
                    ]);
                }

                // delete berkas sementara
                $berkas_sementara->each->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getFile() . $e->getLine() . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah berkas');
            }
            return redirect()->back()->with('success', 'Permohonan berhasil diajukan');
        }

        $permohonan = Permohonan::with(['berkasPermohonan' => function ($query) {
            $query->with([
                'berkasPersyaratan',
                'detailBerkasPermohonan' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]);
        }])->where('user_id', auth()->user()->id)->first();

        // CASE PERMOHONAN BARU
        if ($permohonan->status == 1) {
            $berkas_sementara = BerkasSementara::where('user_id', auth()->user()->id)->where('permohonan_id', $permohonan->id)->get();
            // insert berkas permohonan
            DB::beginTransaction();
            try {
                // insert berkas permohonan
                $permohonan->berkasPermohonan->map(function ($berkas_permohonan) use ($berkas_sementara) {
                    // cek jika berkas sementara ada
                    $berkas = $berkas_sementara->where('berkas_persyaratan_id', $berkas_permohonan->berkas_persyaratan_id)->first();
                    if ($berkas) {
                        // set generated filename => nama berkas persyaratan + berkas nama asli get last extension
                        $generated_filename = $berkas_permohonan->berkasPersyaratan->nama . '.' . pathinfo($berkas->filename, PATHINFO_EXTENSION);
                        // insert berkas permohonan
                        $berkas_permohonan->detailBerkasPermohonan->first()->replicate()->fill([
                            'filepath' => $berkas->filepath,
                            'original_filename' => $berkas->filename,
                            'generated_filename' => $generated_filename,
                            'is_valid' => 0,
                            'is_revisi' => 0,
                        ])->save();

                        // delete berkas sementara
                        $berkas->delete();
                    }
                });

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getFile() . $e->getLine() . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah berkas');
            }
            return redirect()->back()->with('success', 'Permohonan berhasil diperbarui');
        }

        // CASE SEDANG DIVERIFIKASI
        if ($permohonan->status == 2) {
            return redirect()->back()->with('error', 'Permohonan sedang diverifikasi');
        }

        // CASE BERKAS REVISI
        if ($permohonan->status == 3) {
            // get semua berkas yang perlu revisi
            $berkas_revisi = $permohonan->berkasPermohonan->filter(function ($berkas) {
                return $berkas->detailBerkasPermohonan()->revisi()->first();
            });

            // cek jika semua berkas revisi sudah di berkas sementara
            foreach ($berkas_revisi as $berkas_permohonan) {
                $berkas = BerkasSementara::where('user_id', auth()->user()->id)->where('berkas_persyaratan_id', $berkas_permohonan->berkas_persyaratan_id)->where('permohonan_id', $permohonan->id)->first();
                if (!$berkas) {
                    return redirect()->back()->with('error', 'Berkas ' . $berkas_permohonan->berkasPersyaratan->nama . ' belum diunggah');
                }
            }

            // insert berkas permohonan
            DB::beginTransaction();
            try {
                // cek jika semua berkas revisi sudah di berkas sementara
                foreach ($berkas_revisi as $berkas_permohonan) {
                    $berkas = BerkasSementara::where('user_id', auth()->user()->id)->where('berkas_persyaratan_id', $berkas_permohonan->berkas_persyaratan_id)->where('permohonan_id', $permohonan->id)->first();
                    if ($berkas) {
                        // set generated filename => nama berkas persyaratan + berkas nama asli get last extension
                        $generated_filename = $berkas_permohonan->berkasPersyaratan->nama . '.' . pathinfo($berkas->filename, PATHINFO_EXTENSION);
                        // insert berkas permohonan
                        $berkas_permohonan->detailBerkasPermohonan->first()->replicate()->fill([
                            'filepath' => $berkas->filepath,
                            'original_filename' => $berkas->filename,
                            'generated_filename' => $generated_filename,
                            'is_valid' => 0,
                            'is_revisi' => 0,
                        ])->save();

                        // delete berkas sementara
                        $berkas->delete();
                    }
                };

                $permohonan->status = 4;
                $permohonan->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getFile() . $e->getLine() . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah berkas');
            }
        }

        // CASE VERIFIKASI ULANG
        if ($permohonan->status == 4) {
            return redirect()->back()->with('error', 'Permohonan sedang diverifikasi ulang');
        }

        // CASE BERKAS DITERIMA
        if ($permohonan->status == 5) {
            return redirect()->back()->with('error', 'Permohonan sudah diterima');
        }

        // CASE BERKAS DITOLAK
        if ($permohonan->status == 6) {
            return redirect()->back()->with('error', 'Permohonan anda ditolak');
        }
    }
}