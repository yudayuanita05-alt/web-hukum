<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'web_hukum_chat';
    protected $primaryKey = 'id_chat';
    public $timestamps = false;

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'id_formulir',
        'pesan',
        'is_read',
        'created_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_pengirim', 'id_user');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima', 'id_user');
    }

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'id_formulir', 'id_formulir');
    }
}

