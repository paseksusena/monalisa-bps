<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KegiatanAdministrasi;
use App\Models\Transaksi;

class Akun extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function KegiatanAdministrasi()
    {
        return $this->belongsTo(KegiatanAdministrasi::class, 'kegiatan_id');
    }

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }


    public function scopeFilter($query, array $filters)
    {

        //filter berdasarkan keyword search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('progres', 'like', '%' . $search . '%');
        });
        $query->when($filters['kegiatan'] ?? false, function ($query, $kegiatan) {
            return $query->whereHas('KegiatanAdministrasi', function ($query) use ($kegiatan) {
                $query->where('id', $kegiatan);
            });
        });
    }
}
