<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsiStandarIdToNilaiAkhirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->foreignId('isi_standar_id')->nullable()->constrained('ref.isi_standar')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_akhir', function (Blueprint $table) {
            $table->dropForeign(['isi_standar_id']);
            $table->dropColumn('isi_standar_id');
        });
    }
}
