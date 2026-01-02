<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';

    // hanya satu kali deklarasi $fillable
    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
        'latitude',
        'longitude',
        'gambar',
        'slug',
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

    /** Relasi ke review */
    public function reviews()
    {
        return $this->hasMany(DestinasiReview::class);
    }
}
