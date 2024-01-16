<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_peserta')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('jabatan_dilamar')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('tipe')->nullable();
            $table->integer('tahun')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nomor_peserta');
            $table->dropColumn('gelar_depan');
            $table->dropColumn('gelar_belakang');
            $table->dropColumn('tempat_lahir');
            $table->dropColumn('tanggal_lahir');
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('pendidikan');
            $table->dropColumn('jabatan_dilamar');
            $table->dropColumn('unit_kerja');
            $table->dropColumn('tipe');
            $table->dropColumn('tahun');
        });
    }
};
