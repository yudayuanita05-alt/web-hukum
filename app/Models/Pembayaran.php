<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'web_hukum_pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_formulir',
        'order_id',
        'jumlah_tagihan',
        'metode_pembayaran',
        'transaction_id',
        'snap_token',
        'payment_url',
        'status',
        'status_transaksi',
        'bukti_bayar',
        'keterangan',
        'waktu_transaksi',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'jumlah_tagihan' => 'decimal:2',
        'waktu_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'id_formulir', 'id_formulir');
    }

    public function isLunas()
    {
        return (int)$this->status === 1 || strtolower($this->status_transaksi) === 'settlement';
    }
}

