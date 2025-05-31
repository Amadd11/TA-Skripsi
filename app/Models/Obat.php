<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    //
    protected $fillable = [
        'nama_obat',
        'kategori',
        'dosis',
        'satuan',
        'keterangan',
    ];

    public function obats()
    {
        return $this->belongsToMany(JadwalPemberianObat::class,);
    }
}
