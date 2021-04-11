<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->uuid('alat_id');
            $table->unsignedBigInteger('jenis_sarana_id');
            $table->uuid('ruang_id');
            $table->foreign('ruang_id')->references('ruang_id')->on('ruang')->onDelete('cascade');
            $table->string('nama');
            $table->string('spesifikasi')->nullable();
            $table->decimal('kepemilikan_sarpras_id', 1,0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('alat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alat');
    }
}
