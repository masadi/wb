<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstrumenStandarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumen_standar', function (Blueprint $table) {
            $table->uuid('instrumen_standar_id');
            $table->uuid('instrumen_id');
            $table->foreignId('standar_id')->nullable()->constrained('ref.standar')->onDelete('cascade');
            $table->foreignId('isi_standar_id')->nullable()->constrained('ref.isi_standar')->onDelete('cascade');
            $table->timestamps();
            $table->primary('instrumen_standar_id');
            $table->foreign('instrumen_id')->references('instrumen_id')->on('instrumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instrumen_standar');
    }
}
