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
        Schema::create('preprocessings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedTinyInteger('jenis_kelamin'); // 1 = Laki-laki, 0 = Perempuan
            $table->integer('usia_bulan');
            $table->float('berat');
            $table->float('tinggi');
            $table->float('zs_bb_u');
            $table->float('zs_tb_u');
            $table->float('zs_bb_tb');
            $table->unsignedTinyInteger('label_gizi'); // 0 = Gizi Baik, 1 = Kurang Gizi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preprocessings');
    }
};
