<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaDidikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta_didik', function (Blueprint $table) {
            $table->uuid('peserta_didik_id');
			$table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('no_induk');
			$table->string('nisn')->nullable();
			$table->string('nik', 16)->nullable();
			$table->string('jenis_kelamin');
			$table->string('tempat_lahir');
			$table->date('tanggal_lahir');
			$table->integer('agama_id');
			$table->string('alamat')->nullable();
			$table->string('rt')->nullable();
			$table->string('rw')->nullable();
			$table->string('desa_kelurahan')->nullable();
			$table->string('kecamatan')->nullable();
			$table->string('kode_pos')->nullable();
			$table->string('no_telp')->nullable();
			$table->string('kode_wilayah', 8);
			$table->string('email')->nullable();
			$table->string('nama_ayah')->nullable();
			$table->string('nama_ibu')->nullable();
			$table->string('kerja_ayah')->nullable();
			$table->string('kerja_ibu')->nullable();
			$table->timestamps();
            $table->primary('peserta_didik_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peserta_didik');
    }
}
