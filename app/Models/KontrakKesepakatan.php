<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakKesepakatan extends Model
{
    protected $table = 'web_hukum_kontrak_kesepakatan';
    protected $primaryKey = 'id_kontrak';
    public $timestamps = false;

    protected $fillable = [
        'id_formulir',
        'keterangan',
        'berkas_kontrak',
        'status_sidang',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'date',
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'id_formulir', 'id_formulir');
    }
}

