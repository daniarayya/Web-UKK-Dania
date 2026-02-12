<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $primaryKey = 'id_aspirasi';

    protected $fillable = [
        'status',
        'id_kategori',
        'id_input_aspirasi',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_input_aspirasi', 'id_input_aspirasi');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
    
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class,'id_aspirasi','id_aspirasi');
    }

}

