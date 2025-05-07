<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_anaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik');
            $table->enum('jk', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('nama_ortu');
            $table->string('prov');
            $table->string('kab');
            $table->string('kec');
            $table->string('desa');
            $table->string('puskesmas');
            $table->string('posyandu');
            $table->string('alamat');
            $table->string('usia_ukur');
            $table->date('tgl_pengukuran');
            $table->float('berat');
            $table->string('cara_ukur');
            $table->float('lila');
            $table->string('bb_umur');
            $table->string('tb_umur');
            $table->string('bb_tb');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_anaks');
    }
};
