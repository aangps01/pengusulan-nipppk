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
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('status')->default(1); // 1: usulan baru, 2: sedang verifikasi, 3: revisi, 4: verifikasi ulang, 5: selesai, 6: ditolak
            $table->string('keterangan')->nullable();
            $table->timestamp('tanggal_validasi')->nullable();
            $table->foreignId('validator_id')->nullable()->constrained('users', 'id');
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
        Schema::dropIfExists('permohonans');
    }
};
