<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref.dokumen_program', function (Blueprint $table) {
            $table->id('id');
            $table->integer('urut')->unsigned();
            $table->foreignId('program_id')->constrained('ref.program')->onDelete('cascade');
            $table->string('nama');
            $table->integer('poin')->unsigned();
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
        Schema::dropIfExists('ref.dokumen_program');
    }
}
