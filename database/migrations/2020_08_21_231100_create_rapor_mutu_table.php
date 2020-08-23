<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaporMutuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapor_mutu', function (Blueprint $table) {
            $table->uuid('rapor_mutu_id');
            $table->uuid('verifikator_id')->nullable();
            $table->uuid('sekolah_sasaran_id');
            $table->uuid('user_direktorat_id')->nullable();
            $table->foreignId('status_rapor_id')->constrained('status_rapor')->onDelete('cascade');
            $table->foreignId('jenis_rapor_id')->constrained('jenis_rapor')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->primary('rapor_mutu_id');
            $table->foreign('verifikator_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sekolah_sasaran_id')->references('sekolah_sasaran_id')->on('sekolah_sasaran')->onDelete('cascade');
            $table->foreign('user_direktorat_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapor_mutu');
    }
}
