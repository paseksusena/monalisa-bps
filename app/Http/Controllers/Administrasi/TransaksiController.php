<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransaksiImport;
use App\Models\PeriodeAdministrasi;
use App\Models\KegiatanAdministrasi;
use Illuminate\Support\Facades\File;



class TransaksiController extends Controller
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
        $akun = $this->getAkun();
        // $transaksis = Transaksi::where('akun_id', $akun_id)->get();

        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);



        $this->progres($akun->id);
        return view('page.administrasi.transaksi.index', [
            'akun' => $akun,
            'transaksis' => Transaksi::where('akun_id', $akun->id)
                ->filter($query)
                ->paginate(10)
                ->appends(['search' => $search]),
            'fungsi' => $fungsi,
            'periode' => $periode,
            'kegiatan' => $kegiatan,


        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $akun = request('akun');
        $kegiatan = request('kegiatan');
        $periode = request('periode');
        $fungsi = request('fungsi');
        // dd( $akun, $kegiatan, $periode, $fungsi);

        return view('administrasi.transaksi.create', [
            'akun' => $akun,
            'kegiatan' => $kegiatan,
            'periode' => $periode,
            'fungsi' => $fungsi,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaksiRequest $request)
    {
        try {
            $requestValidasi = $request->validate([
                'nama' => 'required|max:50',
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'akun_id' => 'required'

            ]);
            $fungsi = $request->fungsi;
            $periode = $request->periode;
            $kegiatan = $request->kegiatan;
            $akun_id = $request->akun_id;



            Transaksi::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }

        return redirect('/administrasi/transaksi?akun=' . $akun_id . '&kegiatan=' . $kegiatan . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi, StoreTransaksiRequest $request)
    {
        $fungsi = $request->fungsi;

        $periode = PeriodeAdministrasi::where('slug', $request->periode)->first();
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $akun = Akun::where('id', $request->akun)->first();
        $filePath = "administrasis/$fungsi/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}";
        File::deleteDirectory($filePath);

        $transaksi->file()->delete();

        $transaksi->delete();
        return back()->with('success', 'Transaksi ' . $transaksi->nama . ' berhasil dihapus!');
    }
    public function exportExcel($id)
    {

        $fungsi = $this->getFungsi();
        $periode = $this->getPeriode();
        $kegiatan = $this->getKegiatan();
        $akun = $this->getAkun();
        return view('administrasi.transaksi.create-excel', [
            'akun_id' => $akun->id,
            'fungsi' => $fungsi,
            'periode' => $periode,
            'kegiatan' => $kegiatan,

        ]);
    }
    public function storeExcel(StoreTransaksiRequest $request)
    {
        try {

            $fungsi = $request->fungsi;
            $periode = $request->periode;
            $kegiatan = $request->kegiatan;
            $akun_id = $request->akun_id;
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DataTransaksi', $fileName);

            // Move uploaded file to storage
            $fileName = $file->getClientOriginalName();
            // Excel::import(new PemutakhiranSusenasImport($request->id_periode, $tgl_awal, $tgl_akhir), public_path('/DataPemuktahiranSusenas/' . $fileName));
            Excel::import(new TransaksiImport($akun_id), public_path('/DataTransaksi/' . $fileName));
            $this->progres($akun_id);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }

        return redirect('/administrasi/transaksi?akun=' . $akun_id . '&kegiatan=' . $kegiatan . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Data excel berhasil diimpor!');
    }

    public function getAkun()
    {
        $akun = Akun::where('id', request('akun'))->first();
        return $akun;
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


    //menghitung progres

    public function progres($akun_id)
    {
        $transaksis = Transaksi::where('akun_id', $akun_id)->get();


        $totalFiles = 0;
        $complete_file = 0;
        foreach ($transaksis as $transaksi) {
            // Pastikan nilai progres dalam rentang 0 hingga 100
            $totalFiles += $transaksi['amount_file'];
            $complete_file += $transaksi['complete_file'];
        }

        $progres = $totalFiles > 0 ? ($complete_file / $totalFiles) * 100 : 0;


        // Update nilai progres di tabel Akun
        $akun = Akun::find($akun_id);
        $akun->progres = $progres;
        $akun['amount_file'] = $totalFiles;
        $akun['complete_file'] = $complete_file;
        $akun->save();

        return 0;
    }
}
