<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKondisiRuangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kondisi_ruang', function (Blueprint $table) {
            $table->uuid('ruang_id');
            $table->decimal('tahun_pendataan_id', 4, 0);
            $table->foreign('ruang_id')->references('ruang_id')->on('ruang')->onDelete('cascade');
            $table->foreign('tahun_pendataan_id')->references('tahun_pendataan_id')->on('tahun_pendataan')->onDelete('cascade');
            $table->decimal('rusak_lisplang_talang', 6,2)->default(0);
            $table->string('ket_lisplang_talang')->nullable();
            $table->decimal('rusak_rangka_plafon', 6,2)->default(0);
            $table->string('ket_rangka_plafon')->nullable();
            $table->decimal('rusak_tutup_plafon', 6,2)->default(0);
            $table->string('ket_tutup_plafon')->nullable();
            $table->decimal('rusak_bata_dinding', 6,2)->default(0);
            $table->string('ket_bata_dinding')->nullable();
            $table->decimal('rusak_plester_dinding', 6,2)->default(0);
            $table->string('ket_plester_dinding')->nullable();
            $table->decimal('rusak_daun_jendela', 6,2)->default(0);
            $table->string('ket_daun_jendela')->nullable();
            $table->decimal('rusak_daun_pintu', 6,2)->default(0);
            $table->string('ket_daun_pintu')->nullable();
            $table->decimal('rusak_kusen', 6,2)->default(0);
            $table->string('ket_kusen')->nullable();
            $table->decimal('rusak_tutup_lantai', 6,2)->default(0);
            $table->string('ket_penutup_lantai')->nullable();
            $table->decimal('rusak_inst_listrik', 6,2)->default(0);
            $table->string('ket_inst_listrik')->nullable();
            $table->decimal('rusak_inst_air', 6,2)->default(0);
            $table->string('ket_inst_air')->nullable();
            $table->decimal('rusak_drainase', 6,2)->default(0);
            $table->string('ket_drainase')->nullable();
            $table->decimal('rusak_finish_struktur', 6,2)->default(0);
            $table->string('ket_finish_struktur')->nullable();
            $table->decimal('rusak_finish_plafon', 6,2)->default(0);
            $table->string('ket_finish_plafon')->nullable();
            $table->decimal('rusak_finish_dinding', 6,2)->default(0);
            $table->string('ket_finish_dinding')->nullable();
            $table->decimal('rusak_finish_kpj', 6,2)->default(0);
            $table->string('ket_finish_kpj')->nullable();
            $table->decimal('berfungsi', 1,0)->default(1);
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
        Schema::dropIfExists('kondisi_ruang');
    }
}
