<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelaahDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telaah_dokumen', function (Blueprint $table) {
            $table->uuid('dok_id');
            $table->string('nama');
            $table->uuid('instrumen_id');
            $table->smallInteger('urut')->unsigned();
            $table->timestamps();
            $table->primary('dok_id');
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
        Schema::dropIfExists('telaah_dokumen');
    }
}
