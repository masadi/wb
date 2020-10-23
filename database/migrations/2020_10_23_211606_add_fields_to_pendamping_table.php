<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPendampingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendamping', function (Blueprint $table) {
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendamping', function (Blueprint $table) {
            $table->dropColumn(['nip', 'nuptk', 'email', 'nomor_hp']);
        });
    }
}
