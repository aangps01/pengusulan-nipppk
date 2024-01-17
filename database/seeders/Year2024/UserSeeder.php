<?php

namespace Database\Seeders\Year2024;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dd(__DIR__ . "\\teknis.json");
        // call json in this directory namespace "teknis.json"
        $pegawai_teknis = json_decode(file_get_contents(__DIR__ ."\\teknis.json"), true);
        $pegawai_guru = json_decode(file_get_contents(__DIR__ ."\\guru.json"), true);
        $pegawai_nakes = json_decode(file_get_contents(__DIR__ ."\\nakes.json"), true);

        // pegawai teknis
        DB::beginTransaction();
        try{
            foreach($pegawai_teknis as $pegawai)
            {
                User::updateOrCreate([
                    'nik' => $pegawai['NIK'],
                ], [
                    'name' => $pegawai['NAMA SESUAI IJAZAH'],
                    'email' => $pegawai['NIK'] . '@example.com',
                    'password' => Hash::make($pegawai['NIK']),
                    'role' => 1,
                    'nomor_peserta' => $pegawai['NO PESERTA'],
                    'gelar_belakang' => $pegawai['GELAR'],
                    'tempat_lahir' => $pegawai['TEMPAT LAHIR'],
                    'tanggal_lahir' => $pegawai['TANGGAL LAHIR'],
                    'jenis_kelamin' => $pegawai['JENIS KELAMIN'],
                    'pendidikan' => $pegawai['PENDIDIKAN'],
                    'jabatan_dilamar' => $pegawai['JABATAN YG DILAMAR'],
                    'unit_kerja' => $pegawai['UNIT KERJA'],
                    'tipe' => 'teknis',
                    'tahun' => 2024
                ]);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        // pegawai guru
        DB::beginTransaction();
        try {
            foreach ($pegawai_guru as $pegawai) {
                User::updateOrCreate([
                    'nik' => $pegawai['NIK'],
                ], [
                    'name' => $pegawai['NAMA SESUAI IJAZAH'],
                    'email' => $pegawai['NIK'] . '@example.com',
                    'password' => Hash::make($pegawai['NIK']),
                    'role' => 1,
                    'nomor_peserta' => $pegawai['NO PESERTA'],
                    'gelar_belakang' => $pegawai['GELAR BELAKANG'],
                    'tempat_lahir' => $pegawai['TEMPAT LAHIR'],
                    'tanggal_lahir' => Carbon::createFromFormat('d-m-Y', $pegawai['TANGGAL LAHIR'])->format('Y-m-d'),
                    'jenis_kelamin' => $pegawai['JENIS KELAMIN'],
                    'pendidikan' => $pegawai['PENDIDIKAN'],
                    'jabatan_dilamar' => $pegawai['JABATAN YG DILAMAR'],
                    'unit_kerja' => $pegawai['UNIT KERJA'],
                    'tipe' => 'guru',
                    'tahun' => 2024
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // pegawai nakes
        DB::beginTransaction();
        try {
            foreach ($pegawai_nakes as $pegawai) {
                User::updateOrCreate([
                    'nik' => $pegawai['NIK'],
                ], [
                    'name' => $pegawai['NAMA SESUAI IJAZAH'],
                    'email' => $pegawai['NIK'] . '@example.com',
                    'password' => Hash::make($pegawai['NIK']),
                    'role' => 1,
                    'nomor_peserta' => $pegawai['NO PESERTA'],
                    'gelar_depan' => $pegawai['GELAR DEPAN'],
                    'gelar_belakang' => $pegawai['GELAR BELAKANG'],
                    'tempat_lahir' => $pegawai['TEMPAT LAHIR'],
                    'tanggal_lahir' => $pegawai['TANGGAL LAHIR'],
                    'jenis_kelamin' => $pegawai['JENIS KELAMIN'],
                    'pendidikan' => $pegawai['PENDIDIKAN'],
                    'jabatan_dilamar' => $pegawai['JABATAN YG DILAMAR'],
                    'unit_kerja' => $pegawai['UNIT KERJA'],
                    'tipe' => 'nakes',
                    'tahun' => 2024
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
