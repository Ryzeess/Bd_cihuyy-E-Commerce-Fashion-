<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianProduk extends Model
{
    use HasFactory;

    protected $table = 'varian_produk';
    protected $primaryKey = 'id_varian';

    protected $fillable = [
        'id_produk',
        'id_warna',
        'id_ukuran',
        'kode_barang',
        'stok',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class, 'id_warna', 'id_warna');
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran', 'id_ukuran');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_varian', 'id_varian');
    }
}
