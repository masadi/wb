<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiInstrumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_instrumen', function (Blueprint $table) {
            $table->uuid('nilai_instrumen_id');
            $table->uuid('user_id');
            $table->uuid('verifikator_id')->nullable();
            $table->uuid('instrumen_id');
            $table->integer('nilai')->unsigned();
            $table->string('predikat');
            $table->text('keterangan')->nullable();
            $table->primary('nilai_instrumen_id');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('verifikator_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('instrumen_id')->references('instrumen_id')->on('instrumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_instrumen');
    }
}
