<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRombonganBelajarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rombongan_belajar', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
			$table->string('jurusan_id', 25)->nullable();
			$table->uuid('jurusan_sp_id')->nullable();
            $table->integer('kurikulum_id');
			$table->string('nama');
			$table->uuid('ptk_id');
			$table->integer('tingkat');
			$table->decimal('jenis_rombel', 2, 0);
			$table->timestamps();
			$table->softDeletes();
			$table->primary('rombongan_belajar_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('ptk_id')->references('ptk_id')->on('ptk')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rombongan_belajar');
    }
}
