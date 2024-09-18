<?php

namespace App\Http\Controllers\Administrasi;

use App\Exports\CatatanExport;
use App\Exports\notifikasiExport;
use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\File;
use App\Models\KegiatanAdministrasi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class AdministrasiController extends Controller
{

    //untuk digunakan sebagai menampilkan halama utama dari administrtasi 
    public function index_administrasi()
    {

        $session = session('selected_year');

        //membuat reange tahun untuk fitur pilih tahun
        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);

        if (request('tahun')) {
            session()->put('selected_year', request('tahun'));
        }

        //jika tidak ada session selected_year dan tidak ada request tahun pakai tahun sekarang untuk session
        if (!session('selected_year') || !request('tahun')) {
            session()->put('selected_year', $currentYear);
        }

        //melakukan return ke halaman page/administrasi/index
        return view('page.administrasi.index', [
            'years' => $years,
            'fungsi' => null,

        ]);
    }

    //melakukam set tahun session
    public function setYearSession(Request $request)
    {
        //melakukam input session
        $year = $request->input('year');
        $request->session()->put('selected_year', $year);
        return response()->json(['success' => true]);
    }

    // melakukan search
    public function search(Request $request)
    {
        // Inisialisasi array untuk menyimpan hasil pencarian
        $searchResults = [];

        // Ambil data pencarian dari permintaan
        $searchTerm = $request->input('search');
        $session = session('selected_year');

        // Lakukan pencarian di berbagai model dan simpan hasilnya ke dalam array
        $kegiatans = KegiatanAdministrasi::where('nama', 'like', '%' . $searchTerm . '%')->where('tahun', $session)
            ->get();;


        // Mencari files judul berdasarkan search  
        $files = File::where('judul', 'like', '%' . $searchTerm . '%')->get();
        // Mencari transaksi nama, no_kwt berdasarkan search  
        $transaksis = Transaksi::where('nama', 'like', '%' . $searchTerm . '%')
            ->orWhere('no_kwt', 'like', '%' . $searchTerm . '%')
            ->get();

        // Mencari akun nama berdasarkan search  
        $akuns = Akun::where('nama', 'like', '%' . $searchTerm . '%')->get();

        // melakukan perulangan untuk file, bertujuan untuk menampilkan nama, url, dan alamat file
        foreach ($files as $file) {
            if ($file->transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' => $file->judul,
                    'url' => '/administrasi/file?transaksi=' . $file->transaksi->id . '&akun=' . $file->transaksi->akun->id . '&kegiatan=' . $file->transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $file->transaksi->akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $file->transaksi->akun->kegiatanAdministrasi->fungsi .  '/' . $file->transaksi->akun->kegiatanAdministrasi->nama . '/' . $file->transaksi->akun->nama . '/' . $file->transaksi->nama . '/' . $file->judul,
                ];
            }
        }

        // melakukan perulangan untuk transaksi, bertujuan untuk menampilkan nama, url, dan alamat transaksi
        foreach ($transaksis as $transaksi) {
            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' =>   '(' . $transaksi->no_kwt . ') ' . $transaksi->nama,
                    'url' => '/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $transaksi->akun->id . '&kegiatan=' . $transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $transaksi->akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $transaksi->akun->kegiatanAdministrasi->fungsi .  '/' . $transaksi->akun->kegiatanAdministrasi->nama . '/' . $transaksi->akun->nama . '/' . $transaksi->nama,
                ];
            }
        }
        // melakukan perulangan untuk akun, bertujuan untuk menampilkan nama, url, dan alamat akun
        foreach ($akuns as $akun) {
            if ($akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' => $akun->nama,
                    'url' => '/administrasi/transaksi?akun=' . $akun->id . '&kegiatan=' . $akun->kegiatanAdministrasi->id . '&fungsi=' . $akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $akun->kegiatanAdministrasi->fungsi .  '/' . $akun->kegiatanAdministrasi->nama . '/' . $akun->nama,
                ];
            }
        }

        // melakukan perulangan untuk kegiatan, bertujuan untuk menampilkan nama, url, dan alamat kegiatan
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->tahun == $session) {
                $searchResults[] = [
                    'name' => $kegiatan->nama,
                    'url' => '/administrasi/akun?kegiatan=' . $kegiatan->id . '&fungsi=' . $kegiatan->fungsi,
                    'alamat' => $kegiatan->fungsi .  '/' . $kegiatan->nama,
                ];
            }
        }

        // Kirim hasil pencarian sebagai respons JSON
        return response()->json($searchResults);
    }


    //membuat notifikasi tengat waktu
    public function getNotifications()
    {
        // cari semua transaksi 
        $transaksis = Transaksi::all();
        $session = session('selected_year');
        $tgl_now = Carbon::now()->toDateString();
        $lateResults = [];

        // Perulangan transaksis untuk mencari nama, url, alamat, dan tgl akhir transaksi
        foreach ($transaksis as $transaksi) {
            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                if ($tgl_now > $transaksi->tgl_akhir) { // Perubahan kondisi ini
                    if ($transaksi->progres  < 100) {
                        $lateResults[] = [
                            'name' => $transaksi->nama,
                            'url' => '/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $transaksi->akun->id . '&kegiatan=' . $transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $transaksi->akun->kegiatanAdministrasi->fungsi,
                            'alamat' => $transaksi->akun->kegiatanAdministrasi->fungsi .  '/' . $transaksi->akun->kegiatanAdministrasi->nama . '/' . $transaksi->akun->nama . '/' . $transaksi->nama,
                            'tgl' => $transaksi->tgl_akhir
                        ];
                    }
                }
            }
        }

        // Kirim hasil pencarian sebagai respons JSON
        return response()->json($lateResults);
    }

    public function getCatatan()
    {
        $files = File::all();
        $session = session('selected_year');
        $catatanResults = [];

        foreach ($files as $file) {
            $transaksi = $file->transaksi;

            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $catatanResults[] = [
                    'name' => $file->judul,
                    'catatan' => $file->catatan,
                    'url' => '/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $transaksi->akun->id . '&kegiatan=' . $transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $transaksi->akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $file->transaksi->akun->kegiatanAdministrasi->fungsi . '/' . $file->transaksi->akun->kegiatanAdministrasi->nama . '/' . $file->transaksi->akun->nama . '/' . $file->transaksi->nama . '/' . $file->judul
                ];
            }
        }

        return response()->json($catatanResults);
    }

    // download notif excel 
    public function download_notif_excel()
    {

        return Excel::download(new notifikasiExport, 'notifikasi-administrasi.xlsx');

        // Kirim hasil pencarian sebagai respons JSON
    }

    public function download_catatan_excel()
    {

        return Excel::download(new CatatanExport, 'catatan.xlsx');

        // Kirim hasil pencarian sebagai respons JSON
    }

    //download templete xlsx untuk akun, transaksi, dan laci file
    public function downloadTemlate()
    {
        // ambil request template
        $nameFile = request('template');
        $path = public_path('storage/template/' . $nameFile . '.xlsx');
        return response()->download($path);
    }

    // function untuk menghitung progres fungsi, file terupload tiap fungsi, dan menghitung nilai transaksi tiap fungsi.
    public function monitoring()
    {

        $session = session('selected_year');

        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);
        if (!$session) {
            $session = $currentYear;
        }

        // 1. menghitung progres umum

        $progresUmum = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Umum')->get();
        $nilai_trans_umum = 0; // Inisialisasi $nilai_trans_umum dengan nilai awal 0
        $amount_file_umum = 0; // Inisialisasi $amount_file_umum dengan nilai awal 0
        $complete_file_umum = 0; // Inisialisasi $complete_file_umum dengan nilai awal 0

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_umum, &$amount_file_umum, &$complete_file_umum) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_umum) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_umum
                        $nilai_trans_umum += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_umum += $kegiatan->amount_file;
            $complete_file_umum += $kegiatan->complete_file;
        }

        $nilai_trans_umum = number_format($nilai_trans_umum, 0, ',', '.');

        $progresUmum = $amount_file_umum > 0 ? number_format(($complete_file_umum / $amount_file_umum) * 100, 2) : 0;


        //2.  Menghitung proses produksi
        $complete_file_produksi = 0;
        $amount_file_produksi = 0;
        $progresProduksi = 0;
        $nilai_trans_produksi = 0; // Inisialisasi $nilai_trans_produksi dengan nilai awal 0

        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Produksi')->get();

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan produksi
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_produksi, &$amount_file_produksi, &$complete_file_produksi) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_produksi) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_produksi
                        $nilai_trans_produksi += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_produksi += $kegiatan->amount_file;
            $complete_file_produksi += $kegiatan->complete_file;
        }

        // Format nilai $nilai_trans_produksi tanpa titik di belakang nol
        $nilai_trans_produksi = number_format($nilai_trans_produksi, 0, ',', '.');


        // Hitung progres produksi
        $progresProduksi = $amount_file_produksi > 0 ? number_format(($complete_file_produksi / $amount_file_produksi) * 100, 2) : 0;

        //3.  menghitung proses NERACA
        $complete_file_neraca = 0;
        $amount_file_neraca = 0;
        $progresNeraca = 0;
        $nilai_trans_neraca = 0; // Inisialisasi $nilai_trans_neraca dengan nilai awal 0

        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Neraca')->get();

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan neraca
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_neraca, &$amount_file_neraca, &$complete_file_neraca) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_neraca) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_neraca
                        $nilai_trans_neraca += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_neraca += $kegiatan->amount_file;
            $complete_file_neraca += $kegiatan->complete_file;
        }

        // Format nilai $nilai_trans_neraca tanpa titik di belakang nol
        $nilai_trans_neraca = number_format($nilai_trans_neraca, 0, ',', '.');

        // Hitung progres neraca
        $progresNeraca = $amount_file_neraca > 0 ? number_format(($complete_file_neraca / $amount_file_neraca) * 100, 2) : 0;

        // Tampilkan hasil atau gunakan $nilai_trans_neraca, $amount_file_neraca, $complete_file_neraca sesuai kebutuhan

        //4.  menghitung proses distribusi
        $complete_file_distribusi = 0;
        $amount_file_distribusi = 0;
        $progresDistribusi = 0;
        $nilai_trans_distribusi = 0; // Inisialisasi $nilai_trans_distribusi dengan nilai awal 0

        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Distribusi')->get();

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan distribusi
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_distribusi, &$amount_file_distribusi, &$complete_file_distribusi) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_distribusi) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_distribusi
                        $nilai_trans_distribusi += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_distribusi += $kegiatan->amount_file;
            $complete_file_distribusi += $kegiatan->complete_file;
        }

        // Format nilai $nilai_trans_distribusi tanpa titik di belakang nol
        $nilai_trans_distribusi = number_format($nilai_trans_distribusi, 0, ',', '.');

        // Hitung progres distribusi
        $progresDistribusi = $amount_file_distribusi > 0 ? number_format(($complete_file_distribusi / $amount_file_distribusi) * 100, 2) : 0;

        // Tampilkan hasil atau gunakan $nilai_trans_distribusi, $amount_file_distribusi, $complete_file_distribusi sesuai kebutuhan

        //5. menghitung proses sosial
        $complete_file_sosial = 0;
        $amount_file_sosial = 0;
        $progresSosial = 0;
        $nilai_trans_sosial = 0; // Inisialisasi $nilai_trans_sosial dengan nilai awal 0

        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Sosial')->get();

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan sosial
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_sosial, &$amount_file_sosial, &$complete_file_sosial) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_sosial) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_sosial
                        $nilai_trans_sosial += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_sosial += $kegiatan->amount_file;
            $complete_file_sosial += $kegiatan->complete_file;
        }

        // Format nilai $nilai_trans_sosial tanpa titik di belakang nol
        $nilai_trans_sosial = number_format($nilai_trans_sosial, 0, ',', '.');

        // Hitung progres sosial
        $progresSosial = $amount_file_sosial > 0 ? number_format(($complete_file_sosial / $amount_file_sosial) * 100, 2) : 0;



        //6.  menghitung proses ipds
        $complete_file_ipds = 0;
        $amount_file_ipds = 0;
        $progresIpds = 0;
        $nilai_trans_ipds = 0; // Inisialisasi $nilai_trans_ipds dengan nilai awal 0

        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'IPDS')->get();

        foreach ($kegiatans as $kegiatan) {
            // Mengiterasi setiap akun yang terkait dengan kegiatan IPDS
            $kegiatan->Akun()->each(function ($akun) use (&$nilai_trans_ipds, &$amount_file_ipds, &$complete_file_ipds) {
                // Mengiterasi setiap transaksi dalam akun
                $akun->transaksi()->each(function ($transaksi) use (&$nilai_trans_ipds) {
                    // Ambil nilai transaksi dari transaksi saat ini
                    $nilai = $transaksi->nilai_trans;

                    // Periksa jika nilai transaksi tidak null dan tidak kosong
                    if ($nilai !== null && $nilai !== '') {
                        // Menghapus titik sebagai pemisah ribuan
                        $nilai = str_replace('.', '', $nilai);

                        // Konversi string ke nilai numerik (float)
                        $numericTransNilai = (float) $nilai;

                        // Tambahkan nilai transaksi ke $nilai_trans_ipds
                        $nilai_trans_ipds += $numericTransNilai;
                    }
                });
            });

            // Tambahan perhitungan untuk variabel lainnya
            $amount_file_ipds += $kegiatan->amount_file;
            $complete_file_ipds += $kegiatan->complete_file;
        }

        // Format nilai $nilai_trans_ipds tanpa titik di belakang nol
        $nilai_trans_ipds = number_format($nilai_trans_ipds, 0, ',', '.');

        // Hitung progres IPDS
        $progresIpds = $amount_file_ipds > 0 ? number_format(($complete_file_ipds / $amount_file_ipds) * 100, 2) : 0;


        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        return view('page.administrasi.monitoring', [
            'years' => $years,
            'fungsi' => '',
            'tahun' => $session,

            //umum
            'progresUmum' => $progresUmum,
            'amount_file_umum' => $amount_file_umum,
            'complete_file_umum' => $complete_file_umum,

            //produksi
            'progresProduksi' => $progresProduksi,
            'amount_file_produksi' => $amount_file_produksi,
            'complete_file_produksi' => $complete_file_produksi,

            //neraca
            'progresNeraca' => $progresNeraca,
            'amount_file_neraca' => $amount_file_neraca,
            'complete_file_neraca' => $complete_file_neraca,

            //distribusi
            'progresDistribusi' => $progresDistribusi,
            'amount_file_distribusi' => $amount_file_distribusi,
            'complete_file_distribusi' => $complete_file_distribusi,

            //sosial
            'progresSosial' => $progresSosial,
            'amount_file_sosial' => $amount_file_sosial,
            'complete_file_sosial' => $complete_file_sosial,

            //IPDS
            'progresIpds' => $progresIpds,
            'amount_file_ipds' => $amount_file_ipds,
            'complete_file_ipds' => $complete_file_ipds,

            // Data transaksi 
            'nilai_trans_umum' => $nilai_trans_umum,
            'nilai_trans_produksi' => $nilai_trans_produksi,
            'nilai_trans_distribusi' => $nilai_trans_distribusi,
            'nilai_trans_sosial' => $nilai_trans_sosial,
            'nilai_trans_ipds' => $nilai_trans_ipds,
            'nilai_trans_neraca' => $nilai_trans_neraca
        ]);
    }
}
