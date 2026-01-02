<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DestinasiReview extends Model
{
    use HasFactory;

    protected $table = 'destinasi_reviews';

    protected $fillable = [
        'destinasi_id',
        'user_id',
        'name',
        'rating',
        'comment',
    ];

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
