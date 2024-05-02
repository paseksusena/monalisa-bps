<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KegiatanAdministrasi;

class PeriodeAdministrasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }



    public function KegiatanAdministrasi()
    {
        return $this->hasMany(KegiatanAdministrasi::class, 'periode_id');
    }

    public function scopeFilter($query, array $filters)
    {

        //filter berdasarkan keyword search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('tgl_awal', 'like', '%' . $search . '%')
                ->orWhere('tgl_akhir', 'like', '%' . $search . '%')
                ->orWhere('periode', 'like', '%' . $search . '%');
        });

        $query->when($filters['fungsi'] ?? false, function ($query, $fungsi) {
            return $query->where('nama_fungsi', 'like', '%' . $fungsi . '%');
        });
    }
}
