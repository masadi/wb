<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubIbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_ibs', function (Blueprint $table) {
            $table->id();
            /*$table->string('nama_lengkap');
            $table->string('nomor_akun');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('bank');
            $table->string('nomor_rekening');
            $table->enum('sub_ib', ['ya', 'tidak']);*/
            $table->foreignId('trader_id')->constrained()->onDelete('cascade');
            //$table->unsignedBigInteger('downline_id');
            //$table->foreign('downline_id')->references('id')->on('traders');
            //$table->string('komisi_sub_id')->nullable();
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
        Schema::dropIfExists('sub_ibs');
    }
}
