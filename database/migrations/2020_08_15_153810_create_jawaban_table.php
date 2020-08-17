<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->uuid('jawaban_id');
            $table->uuid('user_id');
            $table->foreignId('komponen_id')->constrained('komponen')->onDelete('cascade');
            $table->foreignId('aspek_id')->constrained('aspek')->onDelete('cascade');
            $table->foreignId('atribut_id')->constrained('atribut')->onDelete('cascade');
            $table->foreignId('indikator_id')->nullable()->constrained('indikator')->onDelete('cascade');
            $table->uuid('instrumen_id');
            $table->integer('nilai')->unsigned();
            $table->timestamps();
            $table->primary('jawaban_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('jawaban');
    }
}
