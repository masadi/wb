<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapPdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekap_pd', function (Blueprint $table) {
            $table->uuid('rekap_pd_id');
            $table->uuid('sekolah_id');
            $table->integer('pd_kelas_10_laki')->unsigned();
            $table->integer('pd_kelas_10_perempuan')->unsigned();
            $table->integer('pd_kelas_11_laki')->unsigned();
            $table->integer('pd_kelas_11_perempuan')->unsigned();
            $table->integer('pd_kelas_12_laki')->unsigned();
            $table->integer('pd_kelas_12_perempuan')->unsigned();
            $table->integer('pd_kelas_13_laki')->unsigned();
            $table->integer('pd_kelas_13_perempuan')->unsigned();
            $table->timestamps();
            $table->primary('rekap_pd_id');
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
        Schema::dropIfExists('rekap_pd');
    }
}
