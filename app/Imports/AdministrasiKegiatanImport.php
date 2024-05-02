<?php

namespace App\Imports;

use App\Models\KegiatanAdministrasi;
use App\Models\PeriodeAdministrasi;
use Error;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class AdministrasiKegiatanImport implements ToModel, WithStartRow
{
    protected $periode_id;

    public function __construct($periode_id)
    {
        $this->periode_id = $periode_id;
    }

    public function model(array $row)
    {

        $periode = PeriodeAdministrasi::where('id', $this->periode_id)->first();
        $kegiatan = KegiatanAdministrasi::where('periode_id', $periode->id)->where('nama', $row[1])->first();
        if ($kegiatan) {
            throw new \Exception("Nama kegiatan : '{$row[1]}' sudah ada untuk dalam periode ini.");
        }

        if (empty($row[2])) {
            throw new \Exception("Terdapat kolom tanggal awal kosong.");
        } elseif (empty($row[3])) {
            throw new \Exception("Terdapat kolom tanggal awal kosong.");
        } else {

            $tgl_awal = Date::excelToDateTimeObject($row[2])->format('Y-m-d');
            $tgl_akhir = Date::excelToDateTimeObject($row[3])->format('Y-m-d');

            return new KegiatanAdministrasi([
                'periode_id' => $this->periode_id,
                'nama' => $row[1],
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
            ]);
        }
        // Ubah menjadi format Y-m-d

    }

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris pertama
    }
}
