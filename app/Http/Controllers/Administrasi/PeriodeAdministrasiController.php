<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\PeriodeAdministrasi;
use App\Http\Requests\StorePeriodeAdministrasiRequest;
use App\Http\Requests\UpdatePeriodeAdministrasiRequest;
use App\Http\Controllers\Controller;
use App\Models\KegiatanAdministrasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;





class PeriodeAdministrasiController extends Controller
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
        $fungsi = request('fungsi');



        // Mendapatkan query str    ing yang ada sebelumnya
        $previousQuery = request()->except(['search']);

        // Menggabungkan query string lama dengan parameter search baru
        $query = array_merge($previousQuery, ['search' => $search]);
        // $this->progres();


        return view('page.administrasi.periode.index', [
            'periode' => PeriodeAdministrasi::where('nama_fungsi', $fungsi)
                ->where('tahun', $selected_year)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),
            'fungsi' => $fungsi,
            'years' => $years,
        ]);
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahun = session('selected_year');
        $fungsi = request('fungsi');
        return view('administrasi.periode.create', [
            'fungsi' => $fungsi,
            'tahun' => $tahun
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePeriodeAdministrasiRequest $request)
    {
        try {
            $validationPeriode = $request->validate([
                'nama' => 'required|max:550',
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'periode' => 'required',
                'nama_fungsi' => 'required',
            ]);


            //membuat slug
            $periode = PeriodeAdministrasi::where('nama_fungsi', $request->nama_fungsi)->get();
            $find = $periode->where('nama', $request->nama)->first();
            if ($find) {
                return redirect()->back()->with('error', 'Periode ' . $request->nama .  ' sudah tersedia!');
            }

            $judulKegiatan = Str::slug($request->nama, '-');
            $periode = Str::slug($request->periode, '-');
            $random = Str::random(5);
            $slug = $judulKegiatan . '-' . $periode . '-' . $random . '-' . time();

            $validationPeriode['slug'] = $slug;
            //mengambil tahun
            $validationPeriode['tahun'] = $request->tahun_session;

            PeriodeAdministrasi::create($validationPeriode);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }
        return redirect('/administrasi/periode?fungsi=' . $request->nama_fungsi)->with('success', 'Periode berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeAdministrasi $periodeAdministrasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeAdministrasi $periodeAdministrasi)
    {
        dd("Belum berfungsi, ID = " . $periodeAdministrasi->id);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdatePeriodeAdministrasiRequest $request, PeriodeAdministrasi $periodeAdministrasi)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeAdministrasi $periodeAdministrasi, StorePeriodeAdministrasiRequest $request)
    {
        $fungsi = $request->fungsi;

        $filePath = "administrasis/$fungsi/{$periodeAdministrasi->nama}";
        File::deleteDirectory($filePath);


        // Delete semua tabel yang  berelasi
        $periodeAdministrasi->KegiatanAdministrasi()->each(function ($kegiatanAdministrasi) {
            $kegiatanAdministrasi->akun()->each(function ($akun) {
                $akun->transaksi()->each(function ($transaksi) {
                    $transaksi->file()->delete();
                    $transaksi->delete();
                });
                $akun->delete();
            });
            $kegiatanAdministrasi->delete();
        });

        // Finally, delete the PeriodeAdministrasi
        $periodeAdministrasi->delete();

        return back()->with('success', 'Periode ' . $periodeAdministrasi->nama . ' berhasil dihapus!');
    }
}
