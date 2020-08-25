<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaktaIntegritasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pakta_integritas', function (Blueprint $table) {
            $table->uuid('pakta_integritas_id');
            $table->uuid('sekolah_sasaran_id');
            $table->decimal('terkirim', 1,0)->default(0);
            $table->primary('pakta_integritas_id');
            $table->timestamps();
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
        Schema::dropIfExists('pakta_integritas');
    }
}
