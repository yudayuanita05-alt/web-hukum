<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulirKonsultasi extends Model
{
    protected $table = 'web_hukum_formulir_konsultasi';
    protected $primaryKey = 'id_formulir';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_user_pengacara',
        'id_klasterisasi',
        'no_permohonan',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'pekerjaan',
        'waktu_pelaksanaan',
        'uraian_singkat_perkara',
        'ttd_pemohon',
        'ttd_konsultan',
        'bukti_awal',
        'status_verifikasi',
        'status_kasus_pengacara',
        'status_jadwal_temu',
        'tgl_waktu_jadwal_temu',
        'catatan_jadwal_temu',
        'created_at',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'waktu_pelaksanaan' => 'date',
        'tgl_waktu_jadwal_temu' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pengacara()
    {
        return $this->belongsTo(User::class, 'id_user_pengacara', 'id_user');
    }

    public function klasterisasi()
    {
        return $this->belongsTo(Klasterisasi::class, 'id_klasterisasi', 'id_klasterisasi');
    }

    public function kontrak()
    {
        return $this->hasOne(KontrakKesepakatan::class, 'id_formulir', 'id_formulir');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_formulir', 'id_formulir');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_formulir', 'id_formulir');
    }
}

