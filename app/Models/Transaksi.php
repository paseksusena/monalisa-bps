<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Akun;
use App\Models\File;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function Akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function File()
    {
        return $this->hasMany(File::class);
    }


    public function scopeFilter($query, array $filters)
    {

        //filter berdasarkan keyword search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('no_kwt', 'like', '%' . $search . '%');
        });

        $query->when($filters['akun'] ?? false, function ($query, $akun) {
            return $query->whereHas('akun', function ($query) use ($akun) {
                $query->where('id', $akun);
            });
        });
    }
}
