<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_ib_id')->constrained();
            $table->foreignId('trader_id')->constrained();
            //$table->unsignedBigInteger('downline_id');
            //$table->foreign('downline_id')->references('id')->on('traders');
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
        Schema::dropIfExists('komisis');
    }
}
