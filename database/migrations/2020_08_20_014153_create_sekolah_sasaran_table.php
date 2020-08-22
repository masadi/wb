<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahSasaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah_sasaran', function (Blueprint $table) {
            $table->uuid('sekolah_sasaran_id');
            $table->uuid('verifikator_id');
            $table->uuid('sekolah_id');
            $table->decimal('tahun_pendataan_id', 4, 0);
            $table->timestamps();
            $table->primary('sekolah_sasaran_id');
            $table->foreign('verifikator_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->foreign('tahun_pendataan_id')->references('tahun_pendataan_id')->on('tahun_pendataan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sekolah_sasaran');
    }
}
