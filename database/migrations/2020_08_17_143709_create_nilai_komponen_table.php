<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiKomponenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_komponen', function (Blueprint $table) {
            $table->uuid('nilai_komponen_id');
            $table->foreignId('komponen_id')->constrained('komponen')->onDelete('cascade');
            $table->uuid('user_id');
            $table->decimal('nilai', 5, 2);
            $table->decimal('total_nilai', 5, 2);
            $table->timestamps();
            $table->primary('nilai_komponen_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_komponen');
    }
}
