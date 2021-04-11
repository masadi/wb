<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusKepemilikanSarprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_kepemilikan_sarpras', function (Blueprint $table) {
            $table->decimal('kepemilikan_sarpras_id', 1,0);
            $table->string('nama');
            $table->timestamps();
            $table->primary('kepemilikan_sarpras_id');
        });
        Schema::table('alat', function (Blueprint $table) {
            $table->foreign('kepemilikan_sarpras_id')->references('kepemilikan_sarpras_id')->on('status_kepemilikan_sarpras')->onDelete('cascade');
        });
        Schema::table('tanah', function (Blueprint $table) {
            $table->foreign('kepemilikan_sarpras_id')->references('kepemilikan_sarpras_id')->on('status_kepemilikan_sarpras')->onDelete('cascade');
        });
        Schema::table('bangunan', function (Blueprint $table) {
            $table->foreign('kepemilikan_sarpras_id')->references('kepemilikan_sarpras_id')->on('status_kepemilikan_sarpras')->onDelete('cascade');
        });
        Schema::table('angkutan', function (Blueprint $table) {
            $table->foreign('kepemilikan_sarpras_id')->references('kepemilikan_sarpras_id')->on('status_kepemilikan_sarpras')->onDelete('cascade');
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
            $table->dropForeign(['kepemilikan_sarpras_id']);
        });
        Schema::table('tanah', function (Blueprint $table) {
            $table->dropForeign(['kepemilikan_sarpras_id']);
        });
        Schema::table('bangunan', function (Blueprint $table) {
            $table->dropForeign(['kepemilikan_sarpras_id']);
        });
        Schema::table('angkutan', function (Blueprint $table) {
            $table->dropForeign(['kepemilikan_sarpras_id']);
        });
        Schema::dropIfExists('status_kepemilikan_sarpras');
    }
}
