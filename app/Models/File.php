<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class File extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function Transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function scopeFilter($query, array $filters)
    {

        //filter berdasarkan keyword search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('namaFile', 'like', '%' . $search . '%')
                ->orWhere('ukuran_file', 'like', '%' . $search . '%')
                ->orWhere('ukuran_file', 'like', '%' . $search . '%');
        });

        $query->when($filters['transaksi'] ?? false, function ($query, $transaksi) {
            return $query->whereHas('transaksi', function ($query) use ($transaksi) {
                $query->where('id', $transaksi);
            });
        });
    }
}
