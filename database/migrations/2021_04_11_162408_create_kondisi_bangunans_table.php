<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKondisiBangunansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kondisi_bangunan', function (Blueprint $table) {
            $table->uuid('bangunan_id');
            $table->decimal('tahun_pendataan_id', 4, 0);
            $table->foreign('bangunan_id')->references('bangunan_id')->on('bangunan')->onDelete('cascade');
            $table->foreign('tahun_pendataan_id')->references('tahun_pendataan_id')->on('tahun_pendataan')->onDelete('cascade');
            $table->decimal('rusak_pondasi', 6,2)->default(0);
            $table->string('ket_pondasi')->nullable();
            $table->decimal('rusak_sloop_kolom_balok', 6,2)->default(0);
            $table->string('ket_sloop_kolom_balok')->nullable();
            $table->decimal('rusak_plester_struktur', 6,2)->default(0);
            $table->string('ket_plester_struktur')->nullable();
            $table->decimal('rusak_kudakuda_atap', 6,2)->default(0);
            $table->string('ket_kudakuda_atap')->nullable();
            $table->decimal('rusak_kaso_atap', 6,2)->default(0);
            $table->string('ket_kaso_atap')->nullable();
            $table->decimal('rusak_reng_atap', 6,2)->default(0);
            $table->string('ket_reng_atap')->nullable();
            $table->decimal('rusak_tutup_atap', 6,2)->default(0);
            $table->string('ket_tutup_atap')->nullable();
            $table->decimal('nilai_saat_ini', 20,2)->default(0);
            $table->timestamps();
            $table->primary(['bangunan_id', 'tahun_pendataan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kondisi_bangunan');
    }
}
