<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsiStandarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.isi_standar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standar_id')->nullable()->constrained('ref.standar')->onDelete('cascade');
            $table->foreignId('isi_standar_id')->nullable()->constrained('ref.isi_standar')->onDelete('cascade');
            $table->string('kode');
            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.isi_standar');
    }
}
