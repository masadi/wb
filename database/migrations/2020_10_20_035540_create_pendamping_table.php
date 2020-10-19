<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendampingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendamping', function (Blueprint $table) {
            $table->uuid('pendamping_id');
            $table->string('nama');
            $table->uuid('sekolah_sasaran_id');
            $table->string('instansi');
            $table->timestamps();
            $table->primary('pendamping_id');
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
        Schema::dropIfExists('pendamping');
    }
}
