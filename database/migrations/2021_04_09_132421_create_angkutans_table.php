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
            $table->unsignedBigInteger('jenis_sarana_id');
            $table->uuid('sekolah_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->string('nama');
            $table->string('spesifikasi')->nullable();
            $table->string('merk')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('alamat')->nullable();
            $table->decimal('kepemilikan_sarpras_id', 1,0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('angkutan_id');
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
