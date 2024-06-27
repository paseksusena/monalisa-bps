<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KegiatanAdministrasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use ZipArchive;


class TahunAdministrasiController extends Controller
{

    public function index()
    {
        $currentYear = Carbon::now()->year; //mengambil data tahun sekarang
        $startYear = Carbon::createFromFormat('Y', 2019)->year; //tahun di mulai dari 2019
        $years = range($startYear, $currentYear); //set tahun sekarang dengan tahun mulai

        foreach ($years as $year) { //perulangan setiap data progress tahunan
            $amount_file = 0;
            $complete_file = 0;
            $kegiatans = KegiatanAdministrasi::where('tahun', $year)->get(); //dari kegiatan administrasi
            $progres[$year] = 0;
            $amount[$year] = 0;
            $complete[$year] = 0;
            foreach ($kegiatans as $kegiatan) {
                $amount_file = $kegiatan->amount_file + $amount_file;
                $complete_file = $kegiatan->complete_file + $complete_file;
                $amount[$year] = $amount_file;
                $complete[$year] = $complete_file;
            }
            $progres[$year] = $amount_file > 0 ? number_format(($complete_file / $amount_file) * 100, 2) : 0; //set kalikan 100%
        };

        //kembali ke view (blade) dengan membawa beberapa parameter ini
        return view('page.admin.tambah-tahun', [
            'tahuns' => $years,
            'progres' => $progres,
            'amount_file' => $amount,
            'complete_file' => $complete,

        ]);
    }

    //fucntion untuk menghapus tahun administrasi
    public function destroy(Request $request)
    {
        //mengambil tahun kegiatan administrasi
        $kegiatans = KegiatanAdministrasi::where('tahun', $request->tahun)->get();

        $filePath = "storage/administrasis/$request->tahun"; //lokasi directory yang akan dihapus
        File::deleteDirectory($filePath); //hapus directory tersebut

        foreach ($kegiatans as $kegiatan) { //perulangan, manghapus kegiatan,akun,transaksi,file.
            $kegiatan->Akun()->each(function ($akun) {
                $akun->transaksi()->each(function ($transaksi) {
                    $transaksi->file()->delete();
                    $transaksi->delete();
                });
                $akun->delete();
            });

            $kegiatan->delete();
        }

        //kembali ke halaman jika berhasil dihapus
        return back()->with('success', 'Tahun  ' . $request->tahun . ' berhasil dihapus!');
    }

    //function download ZIP arsip tahunan
    public function download_zip(Request $request)
    {
        // Ambil request tahun
        $tahun = request('tahun');

        // Path folder yang ingin di-zip
        $folderPath = public_path('storage/administrasis/' . $tahun);

        // Nama file zip yang akan dibuat
        $zipFileName = 'Administrasi-BPS-' . $tahun . '.zip';

        // Buat instance dari kelas ZipArchive
        $zip = new ZipArchive;

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            // Tambahkan file dari folder ke dalam file zip
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folderPath));

            foreach ($files as $file) { //perulangan, untuk mengambil beberapa file di directory
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folderPath) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        // Mengirimkan file zip ke browser
        $response = response()->download(public_path($zipFileName));

        // Hapus file zip setelah diunduh
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
