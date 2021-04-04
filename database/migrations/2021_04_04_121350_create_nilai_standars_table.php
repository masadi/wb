<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiStandarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_standar', function (Blueprint $table) {
            $table->uuid('nilai_standar_id');
            $table->foreignId('standar_id')->nullable()->constrained('ref.standar')->onDelete('cascade');
            $table->foreignId('isi_standar_id')->nullable()->constrained('ref.isi_standar')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nilai');
            $table->timestamps();
            $table->primary('nilai_standar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_standar');
    }
}
