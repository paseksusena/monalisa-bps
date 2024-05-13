<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\KegiatanAdministrasi;
use App\Http\Requests\StoreKegiatanAdministrasiRequest;
use App\Http\Requests\UpdateKegiatanAdministrasiRequest;
use App\Http\Controllers\Controller;
use App\Models\PeriodeAdministrasi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdministrasiKegiatanImport;
use Illuminate\Support\Facades\File;


class KegiatanAdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $periode = $this->getPeriode();
        $fungsi = $this->getFungsi();



        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        $this->progresPeriode();
        return view('page.administrasi.kegiatan.index', [
            'periode' => $periode,
            'fungsi' => $fungsi,
            'kegiatans' => KegiatanAdministrasi::where('periode_id', $periode->id)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $fungsi = request('fungsi');
        $periode = $this->getPeriode();
        return view('administrasi.kegiatan.create', [
            'periode' => $periode,
            'fungsi' => $fungsi

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKegiatanAdministrasiRequest $request)
    {
        try {


            // $periode = PeriodeAdministrasi::where('id', $request->periode_id)->first();
            // dd($periode->nama);
            $requestValidasi = $request->validate([
                'nama' => 'required|max:550',
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'periode_id' => 'required'

            ]);


            $fungsi = $request->fungsi;
            $periode = $request->periode_id;

            $periode = PeriodeAdministrasi::where('id', $periode)->first();
            $kegiatan = KegiatanAdministrasi::where('periode_id', $periode->id)->get();
            $find = $kegiatan->where('nama', $request->nama)->first();
            if ($find) {
                return back()->with('error', 'Nama kegiatan ' . $request->nama . ' telah tersedia!');
            }
            KegiatanAdministrasi::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data:  ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?periode=' . $periode->slug . '&fungsi=' . $fungsi)->with('success', 'Kegiatan ' . $request->nama . ' berhasil ditambahkan!');
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
        $periode = PeriodeAdministrasi::where('slug', $request->periode)->first();
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();

        $filePath = "administrasis/$fungsi/{$periode->nama}/{$kegiatan->nama}";
        File::deleteDirectory($filePath);

        $kegiatan->Akun()->each(function ($akun) {
            $akun->transaksi()->each(function ($transaksi) {
                $transaksi->file()->delete();
                $transaksi->delete();
            });
            $akun->delete();
            $this->progresPeriode();
        });



        $kegiatan->delete();
        return back()->with('success', 'Kegiatan ' . $kegiatan->nama . ' berhasil dihapus!');
    }

    public function exportExcel($slug)
    {
        $fungsi = $this->getFungsi();
        $periode = $slug;

        $periode_id = PeriodeAdministrasi::where('slug', $periode)->first()->id;
        return view('administrasi.kegiatan.create-excel', [
            'periode_id' => $periode_id,
            'fungsi' => $fungsi,
        ]);
    }


    public function storeExcel(StoreKegiatanAdministrasiRequest $request)
    {
        try {
            $fungsi = $request->fungsi;
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DatakegiatanAdministrasi', $fileName);

            // Move uploaded file to storage
            $periode = PeriodeAdministrasi::where('id', $request->periode_id)->first();
            $fileName = $file->getClientOriginalName();
            $periode_id = $request->periode_id;
            Excel::import(new AdministrasiKegiatanImport($periode_id), public_path('/DatakegiatanAdministrasi/' . $fileName));
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?periode=' . $periode->slug . '&fungsi=' . $fungsi)->with('success', 'Data Excel berhasil diimpor!');
    }

    public function getPeriode()
    {
        $periode = PeriodeAdministrasi::where('slug', request('periode'))->first();
        return $periode;
    }
    public function getFungsi()
    {
        $fungsi = request('fungsi');
        return $fungsi;
    }

    public function progresPeriode()
    {
        $periodes = PeriodeAdministrasi::all();

        foreach ($periodes as $periode) {
            $kegiatans = KegiatanAdministrasi::where('periode_id', $periode->id)->get();

            $totalFiles = 0;
            $complete_file = 0;
            foreach ($kegiatans as $kegiatan) {
                // Pastikan nilai progres dalam rentang 0 hingga 100
                $totalFiles += $kegiatan['amount_file'];
                $complete_file += $kegiatan['complete_file'];
            }
            // dd($totalFiles);
            $progres = $totalFiles > 0 ? ($complete_file / $totalFiles) * 100 : 0;

            // dd($progres);

            // Update nilai progres di tabel Akun
            $periode = PeriodeAdministrasi::find($periode->id);
            $periode->progres = $progres;
            $periode['amount_file'] = $totalFiles;
            $periode['complete_file'] = $complete_file;
            $periode->save();
        }


        return 0;
    }
}
