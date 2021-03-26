<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('question_id');
            $table->uuid('breakdown_id');
            $table->foreign('breakdown_id')->references('breakdown_id')->on('breakdowns')->onDelete('cascade');
            $table->smallInteger('urut')->unsigned();
            $table->text('question');
            //$table->text('answer');
            $table->timestamps();
            $table->primary('question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
