<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukuran extends Model
{
    use HasFactory;

    protected $table = 'ukuran';
    protected $primaryKey = 'id_ukuran';

    protected $fillable = [
        'nama_ukuran',
        'keterangan',
    ];

    public function varianProduk()
    {
        return $this->hasMany(VarianProduk::class, 'id_ukuran', 'id_ukuran');
    }
}
