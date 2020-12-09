<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiAfirmasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_afirmasi', function (Blueprint $table) {
            $table->uuid('nilai_afirmasi_id');
            $table->foreignId('dokumen_program_id')->constrained('ref.dokumen_program')->onDelete('cascade');
            $table->uuid('sekolah_sasaran_id');
            $table->uuid('pendamping_id');
            $table->integer('ada')->unsigned();
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
            $table->primary('nilai_afirmasi_id');
            $table->foreign('sekolah_sasaran_id')->references('sekolah_sasaran_id')->on('sekolah_sasaran')->onDelete('cascade');
            $table->foreign('pendamping_id')->references('pendamping_id')->on('pendamping')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_afirmasi');
    }
}
