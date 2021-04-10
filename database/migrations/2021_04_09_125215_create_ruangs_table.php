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
            $table->integer('lantai_ke')->unsigned()->nullable()->default(0);
            $table->integer('panjang')->unsigned()->nullable()->default(0);
            $table->integer('lebar')->unsigned()->nullable()->default(0);
            $table->integer('luas')->unsigned()->nullable()->default(0);
            $table->integer('luas_plester')->unsigned()->nullable()->default(0);
            $table->integer('luas_plafon')->unsigned()->nullable()->default(0);
            $table->integer('luas_dinding')->unsigned()->nullable()->default(0);
            $table->integer('luas_daun_jendela')->unsigned()->nullable()->default(0);
            $table->integer('luas_kusen')->unsigned()->nullable()->default(0);
            $table->integer('luas_tutup_lantai')->unsigned()->nullable()->default(0);
            $table->integer('jumlah_instalasi_listrik')->unsigned()->nullable()->default(0);
            $table->integer('panjang_instalasi_air')->unsigned()->nullable()->default(0);
            $table->integer('jumlah_instalasi_air')->unsigned()->nullable()->default(0);
            $table->integer('panjang_drainase')->unsigned()->nullable()->default(0);
            $table->integer('luas_finish_struktur')->unsigned()->nullable()->default(0);
            $table->integer('luas_finish_plafon')->unsigned()->nullable()->default(0);
            $table->integer('luas_finish_dinding')->unsigned()->nullable()->default(0);
            $table->integer('luas_finish_kpj')->unsigned()->nullable()->default(0);
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
