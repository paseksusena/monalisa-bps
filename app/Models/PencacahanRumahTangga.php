<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencacahanRumahTangga extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('id_pml', 'like', '%' . $search . '%')
                ->orWhere('pml', 'like', '%' . $search . '%')
                ->orWhere('id_ppl', 'like', '%' . $search . '%')
                ->orWhere('ppl', 'like', '%' . $search . '%')
                ->orWhere('kode_kec', 'like', '%' . $search . '%')
                ->orWhere('kecamatan', 'like', '%' . $search . '%')
                ->orWhere('kode_desa', 'like', '%' . $search . '%')
                ->orWhere('desa', 'like', '%' . $search . '%')
                ->orWhere('nks', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('sampel_1', 'like', '%' . $search . '%')
                ->orWhere('sampel_2', 'like', '%' . $search . '%')
                ->orWhere('sampel_3', 'like', '%' . $search . '%')
                ->orWhere('sampel_4', 'like', '%' . $search . '%')
                ->orWhere('sampel_5', 'like', '%' . $search . '%')
                ->orWhere('sampel_6', 'like', '%' . $search . '%')
                ->orWhere('sampel_7', 'like', '%' . $search . '%')
                ->orWhere('sampel_8', 'like', '%' . $search . '%')
                ->orWhere('sampel_9', 'like', '%' . $search . '%')
                ->orWhere('sampel_10', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        });
    }
}
