<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'web_hukum_user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'id_role',
        'name',
        'username',
        'image',
        'password',
        'is_active',
        'date_created',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function formulirKlien()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'id_user', 'id_user');
    }

    public function formulirPengacara()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'id_user_pengacara', 'id_user');
    }

    public function chatSent()
    {
        return $this->hasMany(Chat::class, 'id_pengirim', 'id_user');
    }

    public function chatReceived()
    {
        return $this->hasMany(Chat::class, 'id_penerima', 'id_user');
    }

    public function isAdmin()
    {
        return (int)$this->id_role === 1;
    }

    public function isPengacara()
    {
        return (int)$this->id_role === 2;
    }

    public function isKlien()
    {
        return (int)$this->id_role === 3;
    }
}
