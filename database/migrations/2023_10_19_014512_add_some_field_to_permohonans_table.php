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
        Schema::table('permohonans', function (Blueprint $table) {
            $table->boolean('is_upload_dokumen_wajib_tambahan')->default(false);
            // change tanggal validasi to nullable datetime
            $table->dateTime('tanggal_validasi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn('is_upload_dokumen_wajib_tambahan');
            // change tanggal validasi to nullable datetime
            $table->dateTime('tanggal_validasi')->nullable(false)->change();
        });
    }
};
