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
        Schema::table('pemberian_obats', function (Blueprint $table) {
            // Menambahkan kolom jadwal_pemberian_obat_id sebagai foreign key
            $table->foreignId('jadwal_pemberian_obat_id')->constrained('jadwal_pemberian_obats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('pemberian_obats', function (Blueprint $table) {
            // Menghapus kolom jadwal_pemberian_obat_id
            $table->dropForeign(['jadwal_pemberian_obat_id']);
            $table->dropColumn('jadwal_pemberian_obat_id');
        });
    }
};
