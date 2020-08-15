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
            $table->foreignId('indikator_id')->constrained('indikator')->onDelete('cascade');
            $table->smallInteger('urut')->unsigned();
            $table->string('pertanyaan');
            $table->timestamps();
            $table->primary('instrumen_id');
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
