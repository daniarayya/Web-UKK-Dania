<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $primaryKey = 'nisn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'jurusan'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'nisn', 'nisn');
    }
}
