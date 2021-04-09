<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruang', function (Blueprint $table) {
            $table->uuid('ruang_id');
            $table->unsignedBigInteger('jenis_prasarana_id');
            $table->uuid('bangunan_id');
            $table->foreign('bangunan_id')->references('bangunan_id')->on('bangunan')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('nama');
            $table->string('registrasi')->nullable();
            $table->integer('lantai_ke')->unsigned()->nullable();
            $table->integer('luas_plester')->unsigned()->nullable();
            $table->integer('luas_plafon')->unsigned()->nullable();
            $table->integer('luas_dindik')->unsigned()->nullable();
            $table->integer('luas_daun_jendela')->unsigned()->nullable();
            $table->integer('luas_kusen')->unsigned()->nullable();
            $table->integer('luas_tutup_lantai')->unsigned()->nullable();
            $table->integer('jumlah_instalasi_listrik')->unsigned()->nullable();
            $table->integer('panjang_instalasi_air')->unsigned()->nullable();
            $table->integer('jumlah_instalasi_air')->unsigned()->nullable();
            $table->integer('panjang_drainase')->unsigned()->nullable();
            $table->integer('luas_finish_struktur')->unsigned()->nullable();
            $table->integer('luas_finish_plafon')->unsigned()->nullable();
            $table->integer('luas_finish_dindik')->unsigned()->nullable();
            $table->integer('luas_finish_kpj')->unsigned()->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('ruang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruang');
    }
}
