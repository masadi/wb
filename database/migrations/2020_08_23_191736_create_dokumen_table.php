<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->uuid('dokumen_id');
            $table->foreignId('jenis_dokumen_id')->constrained('jenis_dokumen')->onDelete('cascade');
            $table->uuid('verifikator_id');
            $table->uuid('sekolah_sasaran_id');
            $table->string('file_path');
            $table->timestamps();
            $table->primary('dokumen_id');
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
        Schema::dropIfExists('dokumen');
    }
}
