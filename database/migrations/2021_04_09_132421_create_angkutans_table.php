<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAngkutansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('angkutan', function (Blueprint $table) {
            $table->uuid('angkutan_id');
            $table->unsignedBigInteger('jenis_prasarana_id');
            $table->uuid('sekolah_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->string('nama');
            $table->string('spesifikasi')->nullable();
            $table->string('merk')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kepemilikan')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('angkutan_id');
            $table->foreign('jenis_prasarana_id')->references('id')->on('jenis_prasarana')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('angkutan');
    }
}
