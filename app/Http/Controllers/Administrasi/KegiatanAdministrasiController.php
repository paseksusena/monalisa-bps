<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\KegiatanAdministrasi;
use App\Http\Requests\StoreKegiatanAdministrasiRequest;
use App\Http\Requests\UpdateKegiatanAdministrasiRequest;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdministrasiKegiatanImport;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;



class KegiatanAdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startYear = Carbon::createFromFormat('Y', '2023')->year;
        $currentYear = Carbon::now()->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang
        $startYear = Carbon::createFromFormat('Y', '2023')->year;


        $currentYear = Carbon::now()->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang
        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        $selected_year = session('selected_year');

        $search = request('search');
        $fungsi = $this->getFungsi();

        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        return view('page.administrasi.kegiatan.index', [
            'fungsi' => $fungsi,
            'kegiatans' => KegiatanAdministrasi::where('fungsi', $fungsi)
                ->where('tahun', $selected_year)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),
            'years' => $years
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $fungsi = request('fungsi');
        return view('administrasi.kegiatan.create', [
            'fungsi' => $fungsi

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKegiatanAdministrasiRequest $request)
    {
        try {

            $requestValidasi = $request->validate([
                'nama' => 'required|max:550',
                'tahun' => 'required',
                'fungsi' => 'required',
            ]);

            $fungsi = $request->fungsi;

            $kegiatan = KegiatanAdministrasi::where('fungsi', $fungsi)->get();
            $find = $kegiatan->where('nama', $request->nama)->first();
            if ($find) {
                return back()->with('error', 'Nama kegiatan ' . $request->nama . ' telah tersedia!');
            }
            KegiatanAdministrasi::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data:  ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?fungsi=' . $fungsi)->with('success', 'Kegiatan ' . $request->nama . ' berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KegiatanAdministrasi $kegiatanAdministrasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KegiatanAdministrasi $kegiatanAdministrasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateKegiatanAdministrasiRequest $request, KegiatanAdministrasi $kegiatanAdministrasi)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KegiatanAdministrasi $kegiatanAdministrasi, StoreKegiatanAdministrasiRequest $request)
    {
        $fungsi = $request->fungsi;
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();

        $filePath = "storage/administrasis/$fungsi/{$kegiatan->nama}";
        File::deleteDirectory($filePath);

        $kegiatan->Akun()->each(function ($akun) {
            $akun->transaksi()->each(function ($transaksi) {
                $transaksi->file()->delete();
                $transaksi->delete();
            });
            $akun->delete();
        });

        $kegiatan->delete();
        return back()->with('success', 'Kegiatan ' . $kegiatan->nama . ' berhasil dihapus!');
    }



    public function storeExcel(StoreKegiatanAdministrasiRequest $request)
    {
        try {
            $fungsi = $request->fungsi;
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DatakegiatanAdministrasi', $fileName);

            // Move uploaded file to storage
            $fileName = $file->getClientOriginalName();
            Excel::import(new AdministrasiKegiatanImport($fungsi, $request->tahun), public_path('/DatakegiatanAdministrasi/' . $fileName));
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?fungsi=' . $fungsi)->with('success', 'Data Excel berhasil diimpor!');
    }

    public function getFungsi()
    {
        $fungsi = request('fungsi');
        return $fungsi;
    }
}
