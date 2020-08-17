<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiAspekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_aspek', function (Blueprint $table) {
            $table->uuid('nilai_aspek_id');
            $table->foreignId('komponen_id')->constrained('komponen')->onDelete('cascade');
            $table->foreignId('aspek_id')->constrained('aspek')->onDelete('cascade');
            $table->uuid('user_id');
            $table->decimal('nilai', 5, 2);
            $table->decimal('total_nilai', 5, 2);
            $table->primary('nilai_aspek_id');
            $table->timestamps();
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
        Schema::dropIfExists('nilai_aspek');
    }
}
