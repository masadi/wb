<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendampingToSekolahSasaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah_sasaran', function (Blueprint $table) {
            $table->uuid('pendamping_id')->nullable();
            $table->foreign('pendamping_id')->references('pendamping_id')->on('pendamping')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sekolah_sasaran', function (Blueprint $table) {
            $table->dropForeign(['pendamping_id']);
            $table->dropColumn('pendamping_id');
        });
    }
}
