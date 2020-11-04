<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSektorIdToSmkCoeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smk_coe', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->constrained('ref.sektor')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smk_coe', function (Blueprint $table) {
            $table->dropForeign(['sektor_id']);
            $table->dropColumn('sektor_id');
        });
    }
}
