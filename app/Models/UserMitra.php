<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserMitra extends Authenticatable
{
    use Notifiable;

    // Tambahkan atribut yang dibutuhkan oleh model
    protected $fillable = [
        'ppl_id',
        // Tambahkan atribut lainnya jika diperlukan
    ];

    // Jika menggunakan timestamp
    public $timestamps = true;

    // Tentukan nama tabel jika tidak sesuai konvensi
    protected $table = 'user_mitras';
}
