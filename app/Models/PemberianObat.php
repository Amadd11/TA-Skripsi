<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemberianObat extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nama',
        'benar_pasien',
        'benar_obat',
        'benar_dosis',
        'benar_cara',
        'benar_waktu',
        'benar_dokumentasi',
        'benar_alasan',
        'benar_respon',
        'benar_edukasi',
        'benar_evaluasi',
        'benar_bentuk',
        'benar_penyimpanan',
        'pemberian_obat_id',
    ];

    public function pemberianObat()
    {
        return $this->belongsToMany(JadwalPemberianObat::class);
    }
}
