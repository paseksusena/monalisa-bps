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

        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        $this->progresAkun();
        $this->progresKegiatan();
        $this->progresPeriode();
        return view('page.administrasi.transaksi.index', [
            'akun' => $akun,
            'transaksis' => Transaksi::where('akun_id', $akun->id)
                ->filter($query)
                ->paginate(200)
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
            $akun = Akun::where('id', $akun_id)->first();
            $transaksi = Transaksi::where('akun_id', $akun->id)->get();
            $find = $transaksi->where('nama', $request->nama)->first();
            if ($find) {
                return back()->with('error', 'Nama transaksi ' . $request->nama . ' telah tersedia!');
            }
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



    public function progresAkun()
    {
        $akuns = Akun::all();
        foreach ($akuns as $akun) {

            $transaksis = Transaksi::where('akun_id', $akun->id)->get();

            $totalFiles = 0;
            $completeFile = 0;

            foreach ($transaksis as $transaksi) {
                $totalFiles += $transaksi->amount_file;
                $completeFile += $transaksi->complete_file;
            }

            // Check for division by zero
            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // Update progress in the KegiatanAdministrasi table
            $kegiatan = Akun::find($akun->id);
            if ($akun) {
                $akun->progres = $progres;
                $akun->amount_file = $totalFiles;
                $akun->complete_file = $completeFile;
                $akun->save();
            }
        }


        return 0;
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


    public function progresKegiatan()
    {

        $kegiatans = KegiatanAdministrasi::all();

        foreach ($kegiatans as $kegiatan) {
            $akuns = Akun::where('kegiatan_id', $kegiatan->id)->get();


            $totalFiles = 0;
            $completeFile = 0;

            foreach ($akuns as $akun) {
                $totalFiles += $akun->amount_file;
                $completeFile += $akun->complete_file;
            }

            // Check for division by zero
            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // Update progress in the KegiatanAdministrasi table
            $kegiatan = KegiatanAdministrasi::find($kegiatan->id);
            if ($kegiatan) {
                $kegiatan->progres = $progres;
                $kegiatan->amount_file = $totalFiles;
                $kegiatan->complete_file = $completeFile;
                $kegiatan->save();
            }
        }



        return 0;
    }
}
