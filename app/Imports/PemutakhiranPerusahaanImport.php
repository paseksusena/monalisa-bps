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
            'perusahaan' => $row[1],
            'id_pml' => $row[2],
            'pml' => $row[3],
            'id_ppl' => $row[4],
            'ppl' => $row[5],
            'kode_sbr' => $row[6],
            'kode_kec' => $row[7],
            'kecamatan' => $row[8],
            'kode_desa' => $row[9],
            'desa' => $row[10],
        ]);

        // Ensure only one UserMitra with the same ppl_id, update if exists
        UserMitra::updateOrCreate(
            ['ppl_id' => $row[4]],
            ['name' => $row[5]]
        );

        // Return null as ToModel requires
        return null;
    }

    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris kedua
    }
}
