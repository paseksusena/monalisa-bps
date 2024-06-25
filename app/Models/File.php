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
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('namaFile', 'like', '%' . $search . '%')
                ->orWhere('ukuran_file', 'like', '%' . $search . '%');
        });

        $query->when($filters['transaksi'] ?? false, function ($query, $transaksi) {
            return $query->where('transaksi_id', $transaksi);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            if ($status == 'Sudah') {
                return $query->where('status', 1);
            } elseif ($status == 'Belum') {
                return $query->where('status', 0);
            }
        });

        $query->when($filters['ceklist'] ?? false, function ($query, $ceklist) {
            if ($ceklist == 'Sudah') {
                return $query->where('ceklist', 1)->where('status', 1);
            } elseif ($ceklist == 'Belum') {
                return $query->where(function ($query) {
                    $query->where('ceklist', 0)->orWhere('status', 0);
                });
            }
        });


        return $query;
    }
}
