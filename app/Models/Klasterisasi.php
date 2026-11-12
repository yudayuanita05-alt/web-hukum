<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klasterisasi extends Model
{
    protected $table = 'web_hukum_klasterisasi';
    protected $primaryKey = 'id_klasterisasi';
    public $timestamps = false;

    protected $fillable = [
        'nama_klasterisasi',
        'keterangan',
    ];

    public function formulir()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'id_klasterisasi', 'id_klasterisasi');
    }
}

