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
        Schema::create('detail_berkas_permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berkas_permohonan_id')->constrained();
            $table->string('original_filename');
            $table->string('generated_filename');
            $table->string('filepath');
            $table->boolean('is_valid')->default(false);
            $table->boolean('is_revisi')->default(false);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('detail_berkas_permohonans');
    }
};
