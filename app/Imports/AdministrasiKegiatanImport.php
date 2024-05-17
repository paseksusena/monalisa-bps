<?php

namespace App\Imports;


use App\Models\KegiatanAdministrasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Exception;

class AdministrasiKegiatanImport implements ToModel, WithStartRow
{
    protected $fungsi, $tahun;

    public function __construct($fungsi, $tahun)
    {
        $this->fungsi = $fungsi;
        $this->tahun = $tahun;
    }

    public function model(array $row)
    {
        // Ensure you have a valid fungsi property


        $kegiatan = KegiatanAdministrasi::where('fungsi', $this->fungsi)->where('nama', $row[1])->first();
        if ($kegiatan) {
            throw new Exception("Nama kegiatan : '{$row[1]}' sudah ada.");
        }

        // Return the model or perform any action you need here
        return new KegiatanAdministrasi([
            'fungsi' => $this->fungsi,
            'tahun' => $this->tahun,
            'nama' => $row[1],
            // Map other columns as needed
        ]);
    }

    public function startRow(): int
    {
        return 2; // Start reading from the second row
    }
}
