<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Http\Requests\StoreAkunRequest;
use App\Http\Requests\UpdateAkunRequest;
use App\Http\Controllers\Controller;
use App\Models\KegiatanAdministrasi;
use App\Imports\AkunImport;
use App\Models\PeriodeAdministrasi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;


class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $fungsi = $this->getFungsi();
        $periode = $this->getPeriode();

        $kegiatan = $this->getKegiatan();
        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        // $this->progres($kegiatan->id);
        return view('page.administrasi.akun.index', [
            'kegiatan' => $kegiatan,
            'akuns' => Akun::where('kegiatan_id', $kegiatan->id)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),

            'periode' => $periode,
            'fungsi' => $fungsi,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAkunRequest $request)
    {
        try {

            $requestValidasi = $request->validate([
                'nama' => 'required|max:50',
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'kegiatan_id' => 'required'

            ]);
            $fungsi = $request->fungsi;
            $periode = $request->periode;
            $kegiatan_id = $request->kegiatan_id;

            $file = Akun::where('kegiatan_id', $kegiatan_id)->get();
            $find = $file->where('nama', $request->nama)->first();
            if ($find) {
                return back()->with('error', 'Nama akun ' . $request->nama . ' telah tersedia!');
            }
            Akun::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }
        return redirect('/administrasi/akun?kegiatan=' . $kegiatan_id . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Akun berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Akun $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Akun $akun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateAkunRequest $request, Akun $akun)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Akun $akun, StoreAkunRequest $request)
    {
        $fungsi = $request->fungsi;
        $periode = PeriodeAdministrasi::where('slug', $request->periode)->first();
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();

        $filePath = "administrasis/$fungsi/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}";
        File::deleteDirectory($filePath);

        $akun->transaksi()->each(function ($transaksi) {
            $transaksi->file()->delete();
            $transaksi->delete();
        });

        $akun->delete();
        return back()->with('success', 'Akun ' . $akun->nama . ' berhasil dihapus!');
    }

    public function exportExcel($id)
    {
        $fungsi = $this->getFungsi();
        $periode = $this->getPeriode();
        $kegiatan = $id;

        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();
        return view('administrasi.akun.create-excel', [
            'kegiatan' => $kegiatan,
            'periode' => $periode,
            'fungsi' => $fungsi,

        ]);
    }

    public function storeExcel(StoreAkunRequest $request)
    {
        try {
            //ambil req fungsi, periode, kegiatan
            $fungsi = $this->getFungsi();
            $periode = $this->getPeriode();
            $kegiatan_id = $request->kegiatan_id;


            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DataAkunAdministrasi', $fileName);

            // Move uploaded file to storage
            $fileName = $file->getClientOriginalName();
            // Excel::import(new PemutakhiranSusenasImport($request->id_periode, $tgl_awal, $tgl_akhir), public_path('/DataPemuktahiranSusenas/' . $fileName));
            Excel::import(new AkunImport($kegiatan_id), public_path('/DataAkunAdministrasi/' . $fileName));
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }

        return redirect('/administrasi/akun?kegiatan=' . $kegiatan_id . '&periode=' . $periode->slug . '&fungsi=' . $fungsi)->with('success', 'Akun berhasil diimpor!');
    }

    public function getKegiatan()
    {
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
        return $kegiatan;
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
    // public function progres($kegiatan_id)
    // {
    //     $akuns = Akun::where('kegiatan_id', $kegiatan_id)->get();

    //     $totalFiles = 0;
    //     $completeFile = 0;

    //     foreach ($akuns as $akun) {
    //         $totalFiles += $akun->amount_file;
    //         $completeFile += $akun->complete_file;
    //     }

    //     // Check for division by zero
    //     $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

    //     // Update progress in the KegiatanAdministrasi table
    //     $kegiatan = KegiatanAdministrasi::find($kegiatan_id);
    //     if ($kegiatan) {
    //         $kegiatan->progres = $progres;
    //         $kegiatan->amount_file = $totalFiles;
    //         $kegiatan->complete_file = $completeFile;
    //         $kegiatan->save();
    //     }

    //     return 0;
    // }
}
