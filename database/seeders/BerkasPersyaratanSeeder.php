<?php

namespace Database\Seeders;

use App\Models\BerkasPersyaratan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BerkasPersyaratanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $berkas_persyaratan = [
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Lamaran",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Ijazah Terakhir",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "DRH (Dokumen Riwayat Hidup)",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "SKCK (Surat Keterangan Catatan Kepolisian)",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Sehat",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => false,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Keterangan Tidak Pernah Menggunakan Narkoba (Suket Napza)",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Pas Foto",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "JPG/JPEG",
                "tipe_format" => ".jpg,.jpeg",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Keterangan Kebenaran Berkas",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Transkrip Nilai",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Sertifikat Pendidik",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Pernyataan 5 Poin",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Keterangan Sehat Jasmani",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
            [
                "berkas_key" => Str::random(10),
                "nama" => "Surat Keterangan Sehat Rohani",
                "is_required" => true,
                "batas_ukuran" => 1024,
                "nama_format" => "PDF",
                "tipe_format" => ".pdf",
                "is_active" => true,
            ],
        ];

        foreach ($berkas_persyaratan as $berkas) {
            BerkasPersyaratan::updateOrCreate(
                [
                    "nama" => $berkas["nama"]
                ],
                $berkas
            );
        }
    }
}
