<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmkCoeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smk_coe', function (Blueprint $table) {
            $table->uuid('smk_coe_id');
            $table->uuid('sekolah_id');
            $table->decimal('tahun_pendataan_id', 4, 0);
            $table->string('nomor_sk')->nullable();
            $table->string('tanggal_sk')->nullable();
            $table->timestamps();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
            $table->foreign('tahun_pendataan_id')->references('tahun_pendataan_id')->on('tahun_pendataan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smk_coe');
    }
}
