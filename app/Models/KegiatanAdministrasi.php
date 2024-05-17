<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PeriodeAdministrasi;
use App\Models\Akun;

class KegiatanAdministrasi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];



    public function Akun()
    {
        return $this->hasMany(Akun::class, 'kegiatan_id');
    }

    public function scopeFilter($query, array $filters)
    {

        //filter berdasarkan keyword search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });


        // $query->when($filters['periode'] ?? false, function ($query, $periode) {
        //     return $query->whereHas('periodeAdministrasi', function ($query) use ($periode) {
        //         $query->where('slug', $periode);
        //     });
        // });
    }
}
