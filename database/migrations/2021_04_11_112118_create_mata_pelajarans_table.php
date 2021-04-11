<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataPelajaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->unsignedInteger('mata_pelajaran_id');
            $table->string('nama');
            $table->timestamps();
            $table->primary('mata_pelajaran_id');
        });
        Schema::table('buku', function (Blueprint $table) {
            $table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('mata_pelajaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
        });
        Schema::dropIfExists('mata_pelajaran');
    }
}
