<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class notifikasiExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $transaksis = Transaksi::all();
        $session = session('selected_year');
        $tgl_now = Carbon::now()->toDateString();
        $lateResults = collect([]); // Mengubahnya menjadi collection

        // Loop melalui setiap hasil pencarian dan tambahkan ke collection hasil pencarian
        $i = 1; // Nomor baris awal
        foreach ($transaksis as $transaksi) {
            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                if ($tgl_now > $transaksi->tgl_akhir) {
                    if ($transaksi->progres < 100) {
                        $lateResults->push([
                            $i++, // Increment nomor baris setiap kali loop
                            $transaksi->akun->kegiatanAdministrasi->fungsi,
                            $transaksi->akun->kegiatanAdministrasi->nama,
                            $transaksi->akun->nama,
                            $transaksi->nama,
                            $transaksi->tgl_akhir,
                        ]);
                    }
                }
            }
        }

        return $lateResults; // Mengembalikan collection sebagai kumpulan data untuk diekspor
    }

    // Method untuk menentukan label pada file Excel
    public function headings(): array
    {
        return [
            'No',
            'Fungsi',
            'Kegiatan',
            'Akun',
            'Transaksi',
            'Tanggal tengat',
        ];
    }

    // Method untuk melakukan pemetaan data yang akan diekspor
    public function map($row): array
    {
        return [
            $row[0], // Nomor baris
            $row[1], // Fungsi
            $row[2], // Kegiatan
            $row[3], // Akun
            $row[4], // Transaksi
            $row[5], // Tanggal
        ];
    }
}
