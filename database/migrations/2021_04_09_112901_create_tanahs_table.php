<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTanahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tanah', function (Blueprint $table) {
            $table->uuid('tanah_id');
            $table->uuid('sekolah_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->string('nama');
            $table->string('no_sertifikat_tanah')->nullable();
            $table->integer('panjang')->unsigned()->nullable();
            $table->integer('lebar')->unsigned()->nullable();
            $table->integer('luas')->unsigned()->nullable();
            $table->integer('luas_lahan_tersedia')->unsigned()->nullable();
            $table->string('kepemilikan')->nullable();
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
        Schema::dropIfExists('tanah');
    }
}
