<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'input_aspirasis';
    protected $primaryKey = 'id_input_aspirasi';
    protected $fillable = [
        'nisn',
        'id_kategori',
        'lokasi',
        'keterangan',
        'foto'
    ];

    // Tambahkan accessor untuk foto_url
    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }
        
        // Jika foto sudah berupa URL lengkap
        if (filter_var($this->foto, FILTER_VALIDATE_URL)) {
            return $this->foto;
        }
        
        // Jika foto adalah path di storage
        if (Storage::exists($this->foto)) {
            return Storage::url($this->foto);
        }
        
        // Coba berbagai kemungkinan path
        $possiblePaths = [
            $this->foto,
            'public/' . $this->foto,
            'storage/' . $this->foto,
            'pengaduan/' . $this->foto,
            'public/pengaduan/' . $this->foto,
        ];
        
        foreach ($possiblePaths as $path) {
            if (Storage::exists($path)) {
                return Storage::url($path);
            }
        }
        
        return null;
    }
    
    // Accessor untuk cek apakah ada foto
    public function getAdaFotoAttribute()
    {
        return !empty($this->foto);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
    
    public function aspirasi()
    {
        return $this->hasOne(Aspirasi::class, 'id_input_aspirasi', 'id_input_aspirasi');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_aspirasi', 'id_aspirasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}