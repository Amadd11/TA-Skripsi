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
        Schema::create('pemberian_obats', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('benar_pasien');
            $table->boolean('benar_obat');
            $table->boolean('benar_dosis');
            $table->boolean('benar_cara');
            $table->boolean('benar_waktu');
            $table->boolean('benar_dokumentasi');
            $table->boolean('benar_alasan');
            $table->boolean('benar_respon');
            $table->boolean('benar_edukasi');
            $table->boolean('benar_evaluasi');
            $table->boolean('benar_bentuk');
            $table->boolean('benar_penyimpanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemberian_obats');
    }
};
