<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiAkhirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->uuid('nilai_akhir_id');
            $table->uuid('user_id');
            $table->uuid('verifikator_id')->nullable();
            $table->decimal('nilai', 5, 2);
            $table->primary('nilai_akhir_id');
            $table->string('predikat');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('verifikator_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_akhir');
    }
}
