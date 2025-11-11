<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';

    protected $fillable = [
        'nama', 'kategori', 'deskripsi', 'latitude', 'longitude', 'gambar',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /* Scope: hanya yang punya koordinat (buat peta) */
    public function scopeHasCoordinates($q)
    {
        return $q->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
