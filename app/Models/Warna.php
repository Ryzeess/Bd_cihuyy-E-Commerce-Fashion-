<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warna extends Model
{
    use HasFactory;

    protected $table = 'warna';
    protected $primaryKey = 'id_warna';

    protected $fillable = [
        'nama_warna',
        'kode_warna',
    ];

    public function varianProduk()
    {
        return $this->hasMany(VarianProduk::class, 'id_warna', 'id_warna');
    }
}
