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
            $table->foreignId('pemberian_obat_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('slug');
            $table->string('dosis');
            $table->string('rute');
            $table->string('interval');
            $table->text('keterangan')->nullable();
            $table->dateTime('waktu');
            $table->string('pengingat')->nullable();
            $table->enum('status', ['waiting', 'diberikan', 'canceled']);
            $table->timestamps();
        });

        // Tabel pivot untuk hubungan many-to-many antara obat dan pemberian_obat
        Schema::create('obat_pemberian_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained()->onDelete('cascade'); // Perbaikan disini
            $table->foreignId('jadwal_pemberian_obat_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_pemberian_obats'); // Perbaikan disini: drop tabel pivot
        Schema::dropIfExists('jadwal_pemberian_obats');
    }
};
