<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    use HasFactory;

    protected $table = 'kurir';
    protected $primaryKey = 'id_kurir';

    protected $fillable = [
        'nama_kurir',
        'kode_kurir',
        'layanan',
        'tarif_per_kg',
    ];

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_kurir', 'id_kurir');
    }
}
