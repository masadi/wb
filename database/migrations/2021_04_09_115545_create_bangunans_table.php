<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBangunansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangunan', function (Blueprint $table) {
            $table->uuid('bangunan_id');
            $table->uuid('tanah_id');
            $table->foreign('tanah_id')->references('tanah_id')->on('tanah')->onDelete('cascade');
            $table->string('nama');
            $table->string('imb')->nullable();
            $table->integer('panjang')->unsigned()->nullable();
            $table->integer('lebar')->unsigned()->nullable();
            $table->integer('luas')->unsigned()->nullable();
            $table->integer('lantai')->unsigned()->nullable();
            $table->decimal('kepemilikan_sarpras_id', 1,0);
            $table->integer('tahun_bangun')->unsigned()->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('bangunan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bangunan');
    }
}
