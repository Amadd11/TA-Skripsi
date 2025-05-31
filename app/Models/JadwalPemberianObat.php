<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalPemberianObat extends Model
{
    //
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'dosis',
        'rute',
        'interval',
        'keterangan',
        'waktu',
        'ruangan_id',
        'obat_id',
        'pasien_id',
        'pemberian_id',
        'user_id',
        'pengingat',
        'status',
    ];

    protected $casts = [
        'interval' => 'string', // Bisa jadi string jika formatnya "2x sehari" atau "8 jam sekali"
        'waktu' => 'datetime',
    ];

    /**
     * Menggunakan slug sebagai primary key untuk route model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set title dan otomatis buat slug dari title.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Relasi ke tabel User (yang memberikan obat).
     */
    public function perawat()
    {
        return $this->belongsTo(User::class, 'user_id'); // pastikan 'user_id' adalah kolom yang merujuk ke 'users' atau 'perawat'
    }

    /**
     * Relasi ke tabel Pasien.
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
    public function pemberianObat()
    {
        return $this->hasOne(PemberianObat::class);
    }

    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'obat_pemberian_obats');
    }
}
