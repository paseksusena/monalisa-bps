<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KegiatanAdministrasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;



class TahunAdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', 2019)->year;
        $years = range($startYear, $currentYear);



        foreach ($years as $year) {
            $amount_file = 0;
            $complete_file = 0;
            $kegiatans = KegiatanAdministrasi::where('tahun', $year)->get();
            $progres[$year] = 0;
            $amount[$year] = 0;
            $complete[$year] = 0;
            foreach ($kegiatans as $kegiatan) {
                $amount_file = $kegiatan->amount_file + $amount_file;
                $complete_file = $kegiatan->complete_file + $complete_file;
                $amount[$year] = $amount_file;
                $complete[$year] = $complete_file;
            }
            $progres[$year] = $amount_file > 0 ? number_format(($complete_file / $amount_file) * 100, 2) : 0;
        };

        return view('page.admin.tambah-tahun', [
            'tahuns' => $years,
            'progres' => $progres,
            'amount_file' => $amount,
            'complete_file' => $complete,

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
    public function destroy(Request $request)
    {

        $kegiatans = KegiatanAdministrasi::where('tahun', $request->tahun)->get();

        $filePath = "storage/administrasis/$request->tahun";
        File::deleteDirectory($filePath);

        foreach ($kegiatans as $kegiatan) {
            $kegiatan->Akun()->each(function ($akun) {
                $akun->transaksi()->each(function ($transaksi) {
                    $transaksi->file()->delete();
                    $transaksi->delete();
                });
                $akun->delete();
            });

            $kegiatan->delete();
        }

        return back()->with('success', 'Tahun  ' . $request->tahun . ' berhasil dihapus!');
    }
}
