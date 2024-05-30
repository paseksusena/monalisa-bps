<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransaksiImport;
use App\Models\KegiatanAdministrasi;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $fungsi = $this->getFungsi();
        $kegiatan = $this->getKegiatan();
        $akun = $this->getAkun();

        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        $this->progresAkun();
        $this->progresKegiatan();
        return view('page.administrasi.transaksi.index', [
            'akun' => $akun,
            'transaksis' => Transaksi::where('akun_id', $akun->id)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),
            'fungsi' => $fungsi,
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
        $fungsi = request('fungsi');
        // dd( $akun, $kegiatan, $periode, $fungsi);

        return view('administrasi.transaksi.create', [
            'akun' => $akun,
            'kegiatan' => $kegiatan,
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
                'nama' => 'required|max:550',
                'tgl_akhir' => 'required',
                'bln_arsip' => 'required',
                'no_kwt' => 'required',
                'akun_id' => 'required',
                'nilai_trans' => 'required'

            ]);
            $fungsi = $request->fungsi;
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

        return redirect('/administrasi/transaksi?akun=' . $akun_id . '&kegiatan=' . $kegiatan .  '&fungsi=' . $fungsi)->with('success', 'Transaksi berhasil disimpan!');
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
    public function edit($id)
    {
        $transaksi = Transaksi::find($id);
        return response()->json($transaksi);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $requestValidasi = $request->validate([
            'nama' => 'required|max:550',
            'no_kwt' => 'required',
            'tgl_akhir' => 'required',
            'bln_arsip' => 'required',
            'nilai_trans' => 'required'

        ]);


        $fungsi = $request->fungsi;
        $kegiatan = $request->kegiatan;
        $akun = $request->akun;
        $session = session('selected_year');


        $existingAkun = Transaksi::where('akun_id', $akun)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingAkun) {
            return back()->with('error', 'Nama kegiatan ' . $requestValidasi['nama'] . ' telah tersedia!');
        }
        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();
        $akun = Akun::where('id', $akun)->first();


        $transaksi = Transaksi::findOrFail($request->id);
        $oldFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/'  . $akun->nama . '/' . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/'  . $kegiatan->nama . '/'  . $akun->nama . '/' . $request->nama;
        // Rename the folder
        if ($request->nama !== $request->oldNama) {
            $pathOld = ($oldFolderPath);
            $files = Storage::files($pathOld);
            foreach ($files as $file) {
                // Mengganti jalur lama dengan jalur baru
                $file = Str::of($file)->replace(
                    'storage/administrasis/' . $session . '/' .  $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->oldNama,
                    'storage/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->oldNama
                );
                $file_content = Storage::get($file);
                $file_name_parts = explode("/", $file);
                if (count($file_name_parts) > 0) {
                    $file_name = $file_name_parts[count($file_name_parts) - 1];
                    $file_path = ($newFolderPath . '/'  . $file_name);
                    $storage = Storage::put($file_path, $file_content);
                    $delete = Storage::deleteDirectory($oldFolderPath);
                }
            }
        }
        $transaksi->update($requestValidasi);



        return redirect('/administrasi/transaksi?fungsi=' . $fungsi . '&kegiatan=' . $kegiatan->id . '&akun=' . $akun->id)->with('success', 'Akun ' . $requestValidasi['nama'] . ' berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi, StoreTransaksiRequest $request)
    {
        $fungsi = $request->fungsi;
        $session = session('selected_year');


        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $akun = Akun::where('id', $request->akun)->first();
        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}";
        File::deleteDirectory($filePath);

        $transaksi->file()->delete();

        $transaksi->delete();
        return back()->with('success', 'Transaksi ' . $transaksi->nama . ' berhasil dihapus!');
    }
    public function exportExcel($id)
    {

        $fungsi = $this->getFungsi();
        $kegiatan = $this->getKegiatan();
        $akun = $this->getAkun();
        return view('administrasi.transaksi.create-excel', [
            'akun_id' => $akun->id,
            'fungsi' => $fungsi,
            'kegiatan' => $kegiatan,

        ]);
    }
    public function storeExcel(StoreTransaksiRequest $request)
    {
        try {

            $fungsi = $request->fungsi;
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

        return redirect('/administrasi/transaksi?akun=' . $akun_id . '&kegiatan=' . $kegiatan  . '&fungsi=' . $fungsi)->with('success', 'Data excel berhasil diimpor!');
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
