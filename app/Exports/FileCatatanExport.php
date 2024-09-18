<?php

namespace App\Exports;

use App\Models\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Akun;
use App\Models\KegiatanAdministrasi;
use App\Models\Transaksi;


class FileCatatanExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $transaksi;
    protected $akun;
    protected $kegiatan;
    protected $fungsi;
    protected $id;

    public function __construct($transaksi, $akun, $kegiatan, $fungsi, $id)
    {
        $this->transaksi = $transaksi;
        $this->akun = $akun;
        $this->kegiatan = $kegiatan;
        $this->fungsi = $fungsi;
        $this->id = $id;
    }

    public function collection()
    {
        $files = File::query()
            ->when($this->id, function ($query) {
                return $query->where('transaksi_id', $this->id);
            })
            ->get([
                'judul',
                'status',
                'penanggung_jwb',
                'catatan',
                'ceklist',
                'ukuran_file'
            ]);

        $i = 1;

        return $files->map(function ($file) use (&$i) {
            return [
                $i++,
                $file->judul,
                $file->status == 1 ? 'Sudah Upload' : 'Belum Upload',
                $file->penanggung_jwb,
                $file->catatan,
                $file->ceklist == 1 ? 'Terverifikasi' : 'Belum Verifikasi',
                $file->ukuran_file . ' MB',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama File',
            'Status',
            'Penanggung Jawab',
            'Catatan',
            'Ceklist',
            'Ukuran File',
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Tetapkan sel awal sebagai A3
    }

    public function styles(Worksheet $sheet)
    {
        // Set dynamic headers in A1, B1, C1, D1 based on URL parameters
        $sheet->setCellValue('A1', $this->fungsi);
        $sheet->setCellValue('B1', $this->kegiatan);
        $sheet->setCellValue('C1', $this->akun);
        $sheet->setCellValue('D1', $this->transaksi);

        // Style the header row (A3:G3)
        return [
            'A3:G3' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'], // Black color for text
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '939393'], // Grey background
                ],
            ],
        ];
    }
}
