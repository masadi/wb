<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_dokumen', function (Blueprint $table) {
            $table->uuid('nilai_dok_id');
            $table->uuid('sekolah_sasaran_id');
            $table->uuid('instrumen_id');
            $table->uuid('dok_id');
            $table->smallInteger('ada')->unsigned();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->primary('nilai_dok_id');
            $table->foreign('sekolah_sasaran_id')->references('sekolah_sasaran_id')->on('sekolah_sasaran')->onDelete('cascade');
            $table->foreign('instrumen_id')->references('instrumen_id')->on('instrumen')->onDelete('cascade');
            $table->foreign('dok_id')->references('dok_id')->on('telaah_dokumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_dokumen');
    }
}
