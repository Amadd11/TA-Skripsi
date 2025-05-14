<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPengingatColumnInJadwalPemberianObatsTable extends Migration
{
    public function up()
    {
        Schema::table('jadwal_pemberian_obats', function (Blueprint $table) {
            $table->string('pengingat')->nullable()->change(); // Mengubah kolom pengingat menjadi nullable
        });
    }

    public function down()
    {
        Schema::table('jadwal_pemberian_obats', function (Blueprint $table) {
            $table->string('pengingat')->nullable(false)->change(); // Membalik perubahan jika rollback
        });
    }
}
