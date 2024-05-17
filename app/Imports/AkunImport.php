<?php

namespace App\Imports;

use App\Models\Akun;
use App\Models\KegiatanAdministrasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;



class AkunImport implements ToModel, WithStartRow
{
    protected $kegiatan_id;

    public function __construct($kegiatan_id)
    {
        $this->kegiatan_id = $kegiatan_id;
    }

    public function model(array $row)
    {
        $kegiatan = KegiatanAdministrasi::where('id', $this->kegiatan_id)->first();
        $akun = Akun::where('kegiatan_id', $kegiatan->id)->where('nama', $row[1])->first();
        if ($akun) {
            throw new \Exception("Nama akun : '{$row[1]}' sudah ada untuk dalam kegiatan ini.");
        } else {


            return new Akun([
                'kegiatan_id' => $this->kegiatan_id, // Nilai id_periode yang diteruskan dari formulir
                'nama' => $row[1],

            ]);
        }
    }

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris pertama
    }
}
