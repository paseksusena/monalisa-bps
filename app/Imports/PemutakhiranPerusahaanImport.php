<?php

namespace App\Imports;

use App\Models\PemutakhiranPerusahaan;
use App\Models\UserMitra;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PemutakhiranPerusahaanImport implements ToModel, WithStartRow
{
    protected $kegiatan_id, $tgl_awal, $tgl_akhir;

    public function __construct($kegiatan_id, $tgl_awal, $tgl_akhir)
    {
        $this->kegiatan_id = $kegiatan_id;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }

    public function model(array $row)
    {
        // Create PemutakhiranPerusahaan record
        PemutakhiranPerusahaan::create([
            'kegiatan_id' => $this->kegiatan_id, // Nilai kegiatan_id yang diteruskan dari formulir
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
            'id_pml' => $row[1],
            'pml' => $row[2],
            'id_ppl' => $row[3],
            'ppl' => $row[4],
            'kode_sbr' => $row[5],
            'kode_kec' => $row[6],
            'kecamatan' => $row[7],
            'kode_desa' => $row[8],
            'desa' => $row[9],
        ]);

        // Ensure only one UserMitra with the same ppl_id, update if exists
        UserMitra::updateOrCreate(
            ['ppl_id' => $row[3]],
            ['name' => $row[4]]
        );

        // Return null as ToModel requires
        return null;
    }

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris kedua
    }
}
