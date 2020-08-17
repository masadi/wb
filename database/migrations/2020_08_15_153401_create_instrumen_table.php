<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstrumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumen', function (Blueprint $table) {
            $table->uuid('instrumen_id');
            $table->foreignId('indikator_id')->nullable()->constrained('indikator')->onDelete('cascade');
            $table->uuid('ins_id')->nullable();
            $table->smallInteger('urut')->unsigned();
            $table->text('pertanyaan');
            $table->smallInteger('skor')->unsigned();
            $table->timestamps();
            $table->primary('instrumen_id');
            $table->foreign('ins_id')->references('instrumen_id')->on('instrumen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instrumen');
    }
}
