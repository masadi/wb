<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaAcaraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->uuid('berita_acara_id');
            $table->foreignId('jenis_berita_id')->constrained('jenis_berita_acara')->onDelete('cascade');
            $table->uuid('rapor_mutu_id');
            $table->uuid('verifikator_id');
            $table->uuid('sekolah_sasaran_id');
            $table->timestamps();
            $table->primary('berita_acara_id');
            $table->foreign('rapor_mutu_id')->references('rapor_mutu_id')->on('rapor_mutu')->onDelete('cascade');
            $table->foreign('verifikator_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sekolah_sasaran_id')->references('sekolah_sasaran_id')->on('sekolah_sasaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berita_acara');
    }
}
