<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->uuid('laporan_id');
            $table->foreignId('jenis_laporan_id')->constrained('jenis_laporan')->onDelete('cascade');
            $table->uuid('pendamping_id');
            $table->uuid('sekolah_sasaran_id');
            $table->text('jumlah_iduka')->nullable();
            $table->text('lulusan')->nullable();
            $table->text('lulusan_all')->nullable();
            $table->text('perkembangan_smk')->nullable();
            $table->text('kesimpulan_saran')->nullable();
            $table->text('tanggal_pelaksanaan')->nullable();
            $table->text('pengisian')->nullable();
            $table->text('kendala')->nullable();
            $table->text('kondisi')->nullable();
            $table->timestamps();
            $table->primary('laporan_id');
            $table->foreign('pendamping_id')->references('pendamping_id')->on('pendamping')->onDelete('cascade');
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
        Schema::dropIfExists('laporan');
    }
}
