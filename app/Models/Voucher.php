<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $primaryKey = 'id_voucher';

    protected $fillable = [
        'kode_voucher',
        'tipe_diskon',
        'nilai_diskon',
        'minimal_belanja',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_voucher', 'id_voucher');
    }
}
