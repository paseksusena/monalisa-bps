<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\File;
use App\Models\KegiatanAdministrasi;
use App\Models\PeriodeAdministrasi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_administrasi()
    {
        $startYear = Carbon::createFromFormat('Y', '2023')->year;
        //file
        $searchNames[] = null;
        $searchUrls[] = null;
        $searchLinks[] = null;


        if (request('search')) {
            $search = request('search');


            $files = File::where('judul', 'like', '%' . $search . '%')->get();
            $transaksis = Transaksi::where('nama', 'like', '%' . $search . '%')->get();
            $akuns = Akun::where('nama', 'like', '%' . $search . '%')->get();
            $kegiatans = KegiatanAdministrasi::where('nama', 'like', '%' . $search . '%')->get();
            $periodes = PeriodeAdministrasi::where('nama', 'like', '%' . $search . '%')->get();


            foreach ($files as $file) {
                $transaksiFile = $file->transaksi;
                $akunFile = $transaksiFile->akun;
                $kegiatanFile = $akunFile->kegiatanAdministrasi;
                $periodeFile = $kegiatanFile->periodeAdministrasi;
                $fungsi = $periodeFile->nama_fungsi;

                $searchLink = "/administrasi/file?transaksi=$transaksiFile->id&akun=$akunFile->id&kegiatan=$kegiatanFile->id&periode=$periodeFile->slug&fungsi=$fungsi";
                $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                $searchNames[] = $file->judul;
                $searchUrls[] =  $fungsi . '/' . $periodeFile->nama . '/' . $kegiatanFile->nama . '/' . $akunFile->nama . '/' . $transaksiFile->nama . '/' . $file->namaFile;
            }

            foreach ($transaksis as $transaksi) {

                $akunTranskasi = $transaksi->akun;
                $kegiatanTransaksi = $akunTranskasi->kegiatanAdministrasi;
                $periodeTransaksi = $kegiatanTransaksi->periodeAdministrasi;
                $fungsi = $periodeTransaksi->nama_fungsi;

                $searchLink = "/administrasi/file?transaksi=$transaksi->id&akun=$akunTranskasi->id&kegiatan=$kegiatanTransaksi->id&periode=$periodeTransaksi->slug&fungsi=$fungsi";
                $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                $searchNames[] = $transaksi->nama;
                $searchUrls[] = $fungsi . '/' .  $periodeTransaksi->nama . '/' . $kegiatanTransaksi->nama . '/' . $akunTranskasi->nama . '/' . $transaksi->nama;
            }

            foreach ($akuns as $akun) {

                $kegiatanAkun = $akun->kegiatanAdministrasi;
                $periodeAkun = $kegiatanAkun->periodeAdministrasi;
                $fungsi = $periodeAkun->nama_fungsi;

                $searchLink = "/administrasi/transaksi?akun=$akun->id&kegiatan=$kegiatanAkun->id&periode=$periodeAkun->slug&fungsi=$fungsi";
                $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                $searchNames[] = $akun->nama;
                $searchUrls[] = $fungsi . '/' . $periodeAkun->nama . '/' . $kegiatanAkun->nama . '/' . $akun->nama;
            }

            // /administrasi/akun?kegiatan=44&periode=tahun-2024-tahunan-wB67t-1714014341&fungsi=Sosial

            foreach ($kegiatans as $kegiatan) {

                $periodeKegiatan = $kegiatan->periodeAdministrasi;
                $fungsi = $periodeKegiatan->nama_fungsi;

                $searchLink = "/administrasi/akun?kegiatan=$kegiatan->id&periode=$periodeKegiatan->slug&fungsi=$fungsi";
                $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                $searchNames[] = $kegiatan->nama;
                $searchUrls[] = $fungsi . '/' . $periodeKegiatan->nama . '/' . $kegiatan->nama;
            }


            foreach ($periodes as $periode) {

                $fungsi = $periode->nama_fungsi;

                // http://127.0.0.1:8000/administrasi/kegiatan?periode=tahun-2024-tahunan-wB67t-1714014341&fungsi=Sosial
                $searchLink = "/administrasi/kegiatan?periode=$periode->slug&fungsi=$fungsi";
                $searchLinks[] = $searchLink; // Tetapkan nilai $fileLink ke dalam array
                $searchNames[] = $periode->nama;
                $searchUrls[] = $fungsi . '/' . $periode->nama;
            }
        }

        $currentYear = Carbon::now()->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang
        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }
        return view('page.administrasi.index', [
            'years' => $years,
            'searchNames' => $searchNames,
            'searchLinks' => $searchLinks,
            'searchUrls' => $searchUrls,


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
}
