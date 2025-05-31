<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    //
    use HasFactory;
    //
    protected $fillable = [
        'nama',
        'nik',
        'usia',
        'riwayat_penyakit',
        'jenis_kelamin',
        'alamat',
        'tanggal_lahir',
    ];

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = $value;

        if ($value) {
            $this->attributes['usia'] = Carbon::parse($value)->age;
        }
    }
    public function jadwalPemberianObat()
    {
        return $this->hasMany(JadwalPemberianObat::class, 'pasien_id'); // pasang relasi dengan kolom pasien_id
    }
}
