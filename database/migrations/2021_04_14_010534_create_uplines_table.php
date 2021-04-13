<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uplines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trader_id');
            $table->foreign('trader_id')->references('id')->on('traders');
            $table->unsignedBigInteger('upline_id');
            $table->foreign('upline_id')->references('id')->on('traders');
            $table->string('komisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uplines');
    }
}
