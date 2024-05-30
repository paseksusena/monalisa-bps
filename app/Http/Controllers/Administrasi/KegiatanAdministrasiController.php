<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\KegiatanAdministrasi;
use App\Http\Requests\StoreKegiatanAdministrasiRequest;
use App\Http\Requests\UpdateKegiatanAdministrasiRequest;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdministrasiKegiatanImport;
use App\Models\Akun;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class KegiatanAdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchResults = [];

        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang

        $amount_file = 0;
        $complete_file = 0;
        $progres = 0;

        $currentYear = Carbon::now()->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang
        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        $selected_year = session('selected_year');

        $search = request('search');
        $fungsi = $this->getFungsi();



        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);
        $kegiatans = KegiatanAdministrasi::where('fungsi', $fungsi)
            ->where('tahun', $selected_year)
            ->filter($query)
            ->paginate(200)
            ->appends(['search' => $search]);

        foreach ($kegiatans as $kegiatan) {
            $amount_file = $kegiatan->amount_file + $amount_file;
            $complete_file = $kegiatan->complete_file + $complete_file;
        }

        $progres = $amount_file > 0 ? number_format(($complete_file / $amount_file) * 100, 2) : 0;

        return view('page.administrasi.kegiatan.index', [
            'fungsi' => $fungsi,
            'kegiatans' => $kegiatans,
            'years' => $years,
            'searchResults' => $searchResults,
            'amount_file' => $amount_file,
            'complete_file' => $complete_file,
            'progres' => $progres
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
    public function edit($id)
    {
        $kegiatan = KegiatanAdministrasi::find($id);
        return response()->json($kegiatan);
    }

    public function update(Request $request)
    {
        $requestValidasi = $request->validate([
            'nama' => 'required|max:550',
        ]);

        $fungsi = $request->fungsi;
        $session = session('selected_year');


        $existingKegiatan = KegiatanAdministrasi::where('fungsi', $fungsi)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingKegiatan) {
            return back()->with('error', 'Nama kegiatan ' . $requestValidasi['nama'] . ' telah tersedia!');
        }


        $kegiatan = KegiatanAdministrasi::findOrFail($request->id);
        $oldFolderPath = 'public/administrasis/'  . $session . '/' . $fungsi . '/'  . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session . '/' .  $fungsi . '/'  . $request->nama;
        // dd($request->oldNama);
        // Rename the folder
        if ($request->nama !== $request->oldNama) {

            $akuns = Akun::where('kegiatan_id', $request->id)->get();

            foreach ($akuns as $akun) {
                $transaksis = $akun->transaksi;
                foreach ($transaksis as $transaksi) {
                    $pathOld = ($oldFolderPath . '/' . $akun->nama . '/' . $transaksi->nama);
                    $files = Storage::files($pathOld);

                    foreach ($files as $file) {
                        // Mengganti jalur lama dengan jalur baru
                        $file = Str::of($file)->replace(
                            'storage/administrasis/'  . $session .  '/'  . $fungsi . '/' . $request->oldNama,
                            'storage/administrasis/' . $session  . '/' . $fungsi . '/'  . $request->oldNama
                        );
                        $file_content = Storage::get($file);
                        $file_name_parts = explode("/", $file);
                        if (count($file_name_parts) > 0) {
                            $file_name = $file_name_parts[count($file_name_parts) - 1];
                            $file_path = ($newFolderPath . '/' . $akun->nama . '/' . $transaksi->nama . '/' . $file_name);
                            $storage = Storage::put($file_path, $file_content);
                            $delete = Storage::deleteDirectory($oldFolderPath);
                        }
                    }
                }
            }
        }
        $kegiatan->update($requestValidasi);



        return redirect('/administrasi/kegiatan?fungsi=' . $fungsi)->with('success', 'Kegiatan ' . $requestValidasi['nama'] . ' berhasil diubah!');
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
        $session = session('selected_year');

        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}";
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
