<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisPrasaranasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_prasarana', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('a_unit_organisasi', 1,0);
            $table->decimal('a_tanah', 1,0);
            $table->decimal('a_bangunan', 1,0);
            $table->decimal('a_ruang', 1,0);
            $table->timestamps();
        });
        Schema::table('ruang', function (Blueprint $table) {
            $table->foreign('jenis_prasarana_id')->references('id')->on('jenis_prasarana')->onDelete('cascade');
        });
        Schema::table('alat', function (Blueprint $table) {
            $table->foreign('jenis_prasarana_id')->references('id')->on('jenis_prasarana')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ruang', function (Blueprint $table) {
            $table->dropForeign(['jenis_prasarana_id']);
        });
        Schema::table('alat', function (Blueprint $table) {
            $table->dropForeign(['jenis_prasarana_id']);
        });
        Schema::dropIfExists('jenis_prasarana');
    }
}
