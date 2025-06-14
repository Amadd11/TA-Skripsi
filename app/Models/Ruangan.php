<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'kapasitas',
    ];
}
