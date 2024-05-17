<?php

namespace App\Imports;

use App\Models\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Transaksi; // Pastikan untuk mengimpor model Transaksi jika belum dilakukan


class FileImport implements ToModel, WithStartRow
{
    protected $transaksi_id;

    public function __construct($transaksi_id)
    {
        $this->transaksi_id = $transaksi_id;
    }

    public function model(array $row)
    {

        $transaksi = Transaksi::where('id', $this->transaksi_id)->first();
        $file = File::where('transaksi_id', $transaksi->id)->where('judul', $row[1])->first();
        if ($file) {
            throw new \Exception("Judul '{$row[1]}' sudah ada untuk transaksi ini.");
        }
        // Jika validasi berhasil, buat model dan kembalikan
        return new File([
            'transaksi_id' => $this->transaksi_id,
            'judul' => $row[1],
            'penanggung_jwb' => $row[2],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
