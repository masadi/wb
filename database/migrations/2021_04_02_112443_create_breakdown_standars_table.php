<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakdownStandarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakdown_standars', function (Blueprint $table) {
            $table->uuid('breakdown_standar_id');
            $table->foreignId('isi_standar_id')->nullable()->constrained('ref.isi_standar')->onDelete('set null');
            $table->uuid('breakdown_id');
            $table->foreign('breakdown_id')->references('breakdown_id')->on('breakdowns')->onDelete('cascade');
            $table->timestamps();
            $table->primary('breakdown_standar_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breakdown_standars');
    }
}
