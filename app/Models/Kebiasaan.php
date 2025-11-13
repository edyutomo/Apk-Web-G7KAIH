<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kebiasaan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kebiasaan';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'murid_id',
        'jam_bangun',
        'jam_tidur', 
        'durasi_belajar',
        'tanggal'
    ];

    // Tipe data untuk casting
    protected $casts = [
        'tanggal' => 'date',
    ];

    // RELASI: Kebiasaan milik satu murid
    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }
}