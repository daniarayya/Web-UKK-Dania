<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'username','nama','password','nisn','role'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class,'nisn','nisn');
    }
}
