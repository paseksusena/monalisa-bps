<?php

namespace App\Http\Controllers\Administrasi;

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
    /**
     * Display a listing of the resource.
     */
    public function index_administrasi()
    {
        $searchNames[] = null;
        $searchUrls[] = null;
        $searchLinks[] = null;


        $session = session('selected_year');


        if (request('search')) {
            $search = request('search');


            $files = File::where('judul', 'like', '%' . $search . '%')->get();
            $transaksis = Transaksi::where('nama', 'like', '%' . $search . '%')->get();
            $akuns = Akun::where('nama', 'like', '%' . $search . '%')->get();
            $kegiatans = KegiatanAdministrasi::where('nama', 'like', '%' . $search . '%')->get();


            foreach ($files as $file) {
                $transaksiFile = $file->transaksi;
                $akunFile = $transaksiFile->akun;
                $kegiatanFile = $akunFile->kegiatanAdministrasi;
                $fungsi = $kegiatanFile->fungsi;
                if ($kegiatanFile->tahun === $session) {
                    $searchLink = "/administrasi/file?transaksi=$transaksiFile->id&akun=$akunFile->id&kegiatan=$kegiatanFile->id&fungsi=$fungsi";
                    $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                    $searchNames[] = $file->judul;
                    $searchUrls[] =  $fungsi .  '/' . $kegiatanFile->nama . '/' . $akunFile->nama . '/' . $transaksiFile->nama . '/' . $file->namaFile;
                }
            }

            foreach ($transaksis as $transaksi) {

                $akunTranskasi = $transaksi->akun;
                $kegiatanTransaksi = $akunTranskasi->kegiatanAdministrasi;
                $fungsi = $kegiatanTransaksi->fungsi;
                if ($kegiatanTransaksi->tahun === $session) {
                    $searchLink = "/administrasi/file?transaksi=$transaksi->id&akun=$akunTranskasi->id&kegiatan=$kegiatanTransaksi->id&fungsi=$fungsi";
                    $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                    $searchNames[] = $transaksi->nama;
                    $searchUrls[] = $fungsi . '/' . $kegiatanTransaksi->nama . '/' . $akunTranskasi->nama . '/' . $transaksi->nama;
                }
            }

            foreach ($akuns as $akun) {

                $kegiatanAkun = $akun->kegiatanAdministrasi;
                $fungsi = $kegiatanAkun->fungsi;

                if ($kegiatanAkun->tahun === $session) {
                    $searchLink = "/administrasi/transaksi?akun=$akun->id&kegiatan=$kegiatanAkun->id&fungsi=$fungsi";
                    $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                    $searchNames[] = $akun->nama;
                    $searchUrls[] = $fungsi . '/' . $kegiatanAkun->nama . '/' . $akun->nama;
                }
            }

            // /administrasi/akun?kegiatan=44&periode=tahun-2024-tahunan-wB67t-1714014341&fungsi=Sosial

            foreach ($kegiatans as $kegiatan) {
                $fungsi = $kegiatan->fungsi;
                if ($kegiatan->tahun === $session) {

                    $searchLink = "/administrasi/akun?kegiatan=$kegiatan->id&fungsi=$fungsi";
                    $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                    $searchNames[] = $kegiatan->nama;
                    $searchUrls[] = $fungsi . '/'  . $kegiatan->nama;
                }
            }
        }

        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang

        //jika tidak ada session selected_year pakai tahun sekarang

        if (request('tahun')) {
            session()->put('selected_year', request('tahun'));
        }

        if (!session('selected_year') || !request('tahun')) {
            session()->put('selected_year', $currentYear);
        }

        return view('page.administrasi.index', [
            'years' => $years,
            'searchNames' => $searchNames,
            'searchLinks' => $searchLinks,
            'searchUrls' => $searchUrls,
            'fungsi' => null,


        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function setYearSession(Request $request)
    {
        $year = $request->input('year');
        $request->session()->put('selected_year', $year);
        return response()->json(['success' => true]);
    }
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

        $files = File::where('judul', 'like', '%' . $searchTerm . '%')->get();
        $transaksis = Transaksi::where('nama', 'like', '%' . $searchTerm . '%')
            ->orWhere('no_kwt', 'like', '%' . $searchTerm . '%')
            ->get();
        $akuns = Akun::where('nama', 'like', '%' . $searchTerm . '%')->get();

        // Loop melalui setiap hasil pencarian dan tambahkan ke array hasil pencarian
        foreach ($files as $file) {
            if ($file->transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' => $file->judul,
                    'url' => '/administrasi/file?transaksi=' . $file->transaksi->id . '&akun=' . $file->transaksi->akun->id . '&kegiatan=' . $file->transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $file->transaksi->akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $file->transaksi->akun->kegiatanAdministrasi->fungsi .  '/' . $file->transaksi->akun->kegiatanAdministrasi->nama . '/' . $file->transaksi->akun->nama . '/' . $file->transaksi->nama . '/' . $file->judul,
                ];
            }
        }

        foreach ($transaksis as $transaksi) {
            if ($transaksi->akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' =>   '(' . $transaksi->no_kwt . ') ' . $transaksi->nama,
                    'url' => '/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $transaksi->akun->id . '&kegiatan=' . $transaksi->akun->kegiatanAdministrasi->id . '&fungsi=' . $transaksi->akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $transaksi->akun->kegiatanAdministrasi->fungsi .  '/' . $transaksi->akun->kegiatanAdministrasi->nama . '/' . $transaksi->akun->nama . '/' . $transaksi->nama,
                ];
            }
        }

        foreach ($akuns as $akun) {
            if ($akun->kegiatanAdministrasi->tahun == $session) {
                $searchResults[] = [
                    'name' => $akun->nama,
                    'url' => '/administrasi/transaksi?akun=' . $akun->id . '&kegiatan=' . $akun->kegiatanAdministrasi->id . '&fungsi=' . $akun->kegiatanAdministrasi->fungsi,
                    'alamat' => $akun->kegiatanAdministrasi->fungsi .  '/' . $akun->kegiatanAdministrasi->nama . '/' . $akun->nama,
                ];
            }
        }

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


    public function getNotifications()
    {
        $transaksis = Transaksi::all();
        $session = session('selected_year');
        $tgl_now = Carbon::now()->toDateString();
        $lateResults = [];

        // Loop melalui setiap hasil pencarian dan tambahkan ke array hasil pencarian
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

    public function download_notif_excel()
    {

        return Excel::download(new notifikasiExport, 'notifikasi-administrasi.xlsx');

        // Kirim hasil pencarian sebagai respons JSON
    }


    public function downloadTemlate()
    {
        $nameFile = request('template');
        $path = public_path('storage/template/' . $nameFile . '.xlsx');
        return response()->download($path);
    }

    public function monitoring()
    {

        $session = session('selected_year');

        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);
        if (!$session) {
            $session = $currentYear;
        }

        // menghitung progres umum
        $complete_file_umum = 0;
        $amount_file_umum = 0;
        $progresUmum = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Umum')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_umum = $kegiatan->amount_file + $amount_file_umum;
            $complete_file_umum = $kegiatan->complete_file + $complete_file_umum;
        }

        $progresUmum = $amount_file_umum > 0 ? number_format(($complete_file_umum / $amount_file_umum) * 100, 2) : 0;

        // menghitung proses produksi
        $complete_file_produksi = 0;
        $amount_file_produksi = 0;
        $progresProduksi = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Produksi')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_produksi = $kegiatan->amount_file + $amount_file_produksi;
            $complete_file_produksi = $kegiatan->complete_file + $complete_file_produksi;
        }

        $progresProduksi = $amount_file_produksi > 0 ? number_format(($complete_file_produksi / $amount_file_produksi) * 100, 2) : 0;

        // menghitung proses NERACA
        $complete_file_neraca = 0;
        $amount_file_neraca = 0;
        $progresNeraca = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Neraca')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_neraca = $kegiatan->amount_file + $amount_file_neraca;
            $complete_file_neraca = $kegiatan->complete_file + $complete_file_neraca;
        }

        $progresNeraca = $amount_file_neraca > 0 ? number_format(($complete_file_neraca / $amount_file_neraca) * 100, 2) : 0;

        // menghitung proses distribusi
        $complete_file_distribusi = 0;
        $amount_file_distribusi = 0;
        $progresDistribusi = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Distribusi')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_distribusi = $kegiatan->amount_file + $amount_file_distribusi;
            $complete_file_distribusi = $kegiatan->complete_file + $complete_file_distribusi;
        }

        $progresDistribusi = $amount_file_distribusi > 0 ? number_format(($complete_file_distribusi / $amount_file_distribusi) * 100, 2) : 0;

        // menghitung proses sosial
        $complete_file_sosial = 0;
        $amount_file_sosial = 0;
        $progresSosial = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'Sosial')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_sosial = $kegiatan->amount_file + $amount_file_sosial;
            $complete_file_sosial = $kegiatan->complete_file + $complete_file_sosial;
        }

        $progresSosial = $amount_file_sosial > 0 ? number_format(($complete_file_sosial / $amount_file_sosial) * 100, 2) : 0;


        // menghitung proses ipds
        $complete_file_ipds = 0;
        $amount_file_ipds = 0;
        $progresIpds = 0;
        $kegiatans = KegiatanAdministrasi::where('tahun', $session)->where('fungsi', 'IPDS')->get();
        foreach ($kegiatans as $kegiatan) {
            $amount_file_ipds = $kegiatan->amount_file + $amount_file_ipds;
            $complete_file_ipds = $kegiatan->complete_file + $complete_file_ipds;
        }

        $progresIpds = $amount_file_ipds > 0 ? number_format(($complete_file_ipds / $amount_file_ipds) * 100, 2) : 0;

        if (request('tahun')) {
            session()->put('selected_year', request('tahun'));
        }

        if (!session('selected_year') || !request('tahun')) {
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
        ]);
    }
}
