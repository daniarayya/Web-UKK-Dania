<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $primaryKey = 'id_feedback';

    protected $fillable = [
        'id_aspirasi',
        'id_user',
        'isi'
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class,'id_aspirasi','id_aspirasi');
    }

    public function user()
    {
        // Sesuaikan foreign key 'id_user' dan owner key 'id_user'
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}

