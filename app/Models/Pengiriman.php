<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_pesanan',
        'id_kurir',
        'nomor_resi',
        'status_pengiriman',
        'biaya_ongkir',
        'tanggal_kirim',
        'tanggal_diterima',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'id_kurir', 'id_kurir');
    }
}
