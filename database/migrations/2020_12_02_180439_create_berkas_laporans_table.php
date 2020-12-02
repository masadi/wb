<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerkasLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berkas_laporan', function (Blueprint $table) {
            $table->uuid('berkas_laporan_id');
            $table->uuid('laporan_id');
            $table->string('file_path');
            $table->timestamps();
            $table->primary('berkas_laporan_id');
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berkas_laporan');
    }
}
