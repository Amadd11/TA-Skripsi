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
        Schema::create('jadwal_pemberian_obats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('pasien_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('dosis');
            $table->string('rute');
            $table->string('interval');
            $table->text('keterangan')->nullable();
            $table->time('waktu');
            $table->string('pengingat');
            $table->enum('status', ['waiting', 'diberikan', 'canceled']);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel pivot untuk hubungan many-to-many antara obat dan pemberian_obat
        Schema::create('obat_pemberian_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemberian_obat_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_pemberian_obat_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pemberian_obats');
    }
};
