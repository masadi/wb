<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubIbIdToTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->foreignId('sub_ib_id')->nullable()->constrained()->after('sub_ib')->onDelete('set null');
            $table->string('komisi_sub_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->dropForeign('traders_sub_ib_id_foreign');
            //$table->dropColumn(['sub_ib_id', 'komisi_sub_id']);
            $table->dropColumn(['sub_ib_id', 'komisi_sub_id']);
        });
    }
}
