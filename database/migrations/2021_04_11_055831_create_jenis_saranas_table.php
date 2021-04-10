<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisSaranasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_sarana', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelompok')->nullable();
            $table->decimal('perlu_penempatan', 1, 0);
            $table->string('keterangan')->nullable();
            $table->decimal('a_alat', 1, 0)->default(0);
            $table->decimal('a_angkutan', 1, 0)->default(0);
            $table->timestamps();
        });
        Schema::table('alat', function (Blueprint $table) {
            $table->foreign('jenis_sarana_id')->references('id')->on('jenis_sarana')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropForeign(['jenis_sarana_id']);
        });
        Schema::dropIfExists('jenis_sarana');
    }
}
