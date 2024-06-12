<?php

namespace App\Imports;

use App\Models\Akun;
use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class TransaksiImport implements ToModel, WithStartRow
{
    protected $akun_id;

    public function __construct($akun_id)
    {
        $this->akun_id = $akun_id;
    }

    public function model(array $row)
    {
        $akun = Akun::where('id', $this->akun_id)->first();
        $transaksi = Transaksi::where('akun_id', $akun->id)->where('nama', $row[1])->first();
        if ($transaksi) {
            throw new \Exception("Nama transaksi : '{$row[1]}' sudah ada untuk dalam akun ini.");
        }

        if (empty($row[2])) {
            throw new \Exception("Terdapat kolom tanggal awal kosong.");
        } elseif (empty($row[3])) {
            throw new \Exception("Terdapat kolom tanggal awal kosong.");
        } else {

            $tgl_akhir = Date::excelToDateTimeObject($row[5])->format('Y-m-d');
            return new Transaksi([
                'akun_id' => $this->akun_id, // Nilai id_periode yang diteruskan dari formulir
                'no_kwt' => $row[1],
                'nama' => $row[2],
                'bln_arsip' => $row[3],
                'nilai_trans' => number_format($row[4], 0, ',', '.'), // Format nilai_trans
                'tgl_akhir' => $tgl_akhir,
            ]);
        }
    }

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris pertama
    }
}
