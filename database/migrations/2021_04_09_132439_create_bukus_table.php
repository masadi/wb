<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->uuid('buku_id');
            $table->uuid('sekolah_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('judul');
            $table->unsignedInteger('mata_pelajaran_id');
            $table->string('nama_penerbit')->nullable();
            $table->string('isbn_issn')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedInteger('kelas')->nullable();
            $table->timestamps();
            $table->primary('buku_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku');
    }
}
