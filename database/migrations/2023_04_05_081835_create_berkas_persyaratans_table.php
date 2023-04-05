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
        Schema::create('berkas_persyaratans', function (Blueprint $table) {
            $table->id();
            $table->string('berkas_key');
            $table->string('nama');
            $table->string('deskripsi')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('batas_ukuran')->default(0);
            $table->string('nama_format')->default('PDF');
            $table->string('tipe_format')->default('pdf');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berkas_persyaratans');
    }
};
