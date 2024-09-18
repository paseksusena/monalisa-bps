<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\File;

class CatatanExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $files = File::all();
        $session = session('selected_year');
        $catatanResults = collect([]); // Collection untuk data catatan

        // Looping untuk mengambil data dari getCatatan
        $i = 1;
        foreach ($files as $file) {
            $transaksi = $file->transaksi;

            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $catatanResults->push([
                    $i++, // Nomor urut
                    $transaksi->akun->kegiatanAdministrasi->fungsi,
                    $transaksi->akun->kegiatanAdministrasi->nama,
                    $transaksi->akun->nama,
                    $transaksi->nama,
                    $file->judul, // Nama file
                    $file->ceklist, // Status (0 atau 1)
                    $file->catatan, // Catatan terkait file
                ]);
            }
        }

        return $catatanResults; // Mengembalikan collection untuk diekspor
    }

    // Mendefinisikan heading kolom di file Excel
    public function headings(): array
    {
        return [
            'No',
            'Fungsi',
            'Kegiatan',
            'Akun',
            'Transaksi',
            'File',
            'Status',
            'Catatan'
        ];
    }

    // Mapping data dari collection ke format Excel
    public function map($row): array
    {
        return [
            $row[0], // No
            $row[1], // Fungsi
            $row[2], // Kegiatan
            $row[3], // Akun
            $row[4], // Transaksi
            $row[5], // File
            $row[6] == 1 ? 'Terverifikasi' : 'Belum Verifikasi', // Cek status
            $row[7], // Catatan
        ];
    }
}
