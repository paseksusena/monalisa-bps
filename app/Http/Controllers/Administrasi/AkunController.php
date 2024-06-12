<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Http\Requests\StoreAkunRequest;
use App\Http\Requests\UpdateAkunRequest;
use App\Http\Controllers\Controller;
use App\Models\KegiatanAdministrasi;
use App\Imports\AkunImport;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $fungsi = $this->getFungsi();

        $kegiatan = $this->getKegiatan();
        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);

        $this->progresKegiatan();
        return view('page.administrasi.akun.index', [
            'kegiatan' => $kegiatan,
            'akuns' => Akun::where('kegiatan_id', $kegiatan->id)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),

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
                'nama' => 'required|max:550',
                'kegiatan_id' => 'required'

            ]);
            $fungsi = $request->fungsi;
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
        return redirect('/administrasi/akun?kegiatan=' . $kegiatan_id . '&fungsi=' . $fungsi)->with('success', 'Akun berhasil ditambahkan!');
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
    public function edit($id)
    {

        $akuns = Akun::find($id);
        return response()->json($akuns);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $requestValidasi = $request->validate([
            'nama' => 'required|max:550',
        ]);

        $fungsi = $request->fungsi;
        $kegiatan = $request->kegiatan;
        $session  = session('selected_year');


        $existingAkun = Akun::where('kegiatan_id', $kegiatan)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingAkun) {
            return back()->with('error', 'Nama kegiatan ' . $requestValidasi['nama'] . ' telah tersedia!');
        }
        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();


        $akun = Akun::findOrFail($request->id);
        $oldFolderPath = 'public/administrasis/'  . $session  . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session  . '/' . $fungsi . '/'  . $kegiatan->nama . '/' . $request->nama;
        // dd($request->oldNama);
        // Rename the folder
        if ($request->nama !== $request->oldNama) {

            $transaksis = Transaksi::where('akun_id', $request->id)->get();
            foreach ($transaksis as $transaksi) {
                $pathOld = ($oldFolderPath  . '/' . $transaksi->nama);
                $files = Storage::files($pathOld);

                foreach ($files as $file) {
                    // Mengganti jalur lama dengan jalur baru
                    $file = Str::of($file)->replace(
                        'storage/administrasis/' . $session  . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $request->oldNama,
                        'storage/administrasis/' . $session  . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $request->oldNama
                    );
                    $file_content = Storage::get($file);
                    $file_name_parts = explode("/", $file);
                    if (count($file_name_parts) > 0) {
                        $file_name = $file_name_parts[count($file_name_parts) - 1];
                        $file_path = ($newFolderPath . '/'  . $transaksi->nama . '/' . $file_name);
                        $storage = Storage::put($file_path, $file_content);
                        $delete = Storage::deleteDirectory($oldFolderPath);
                    }
                }
            }
        }
        $akun->update($requestValidasi);



        return redirect('/administrasi/akun?fungsi=' . $fungsi . '&kegiatan=' . $kegiatan->id)->with('success', 'Akun ' . $requestValidasi['nama'] . ' berhasil diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Akun $akun, StoreAkunRequest $request)
    {
        $fungsi = $request->fungsi;
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $session = session('selected_year');


        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}";
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
        $kegiatan = $id;

        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();
        return view('administrasi.akun.create-excel', [
            'kegiatan' => $kegiatan,
            'fungsi' => $fungsi,

        ]);
    }

    public function storeExcel(StoreAkunRequest $request)
    {
        try {
            $fungsi = $this->getFungsi();
            $kegiatan_id = $request->kegiatan_id;


            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('Excel', $fileName);
            $filePath = public_path('Excel/' . $fileName);

            // Move uploaded file to storage
            $fileName = $file->getClientOriginalName();
            Excel::import(new AkunImport($kegiatan_id), $filePath);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }

        return redirect('/administrasi/akun?kegiatan=' . $kegiatan_id .  '&fungsi=' . $fungsi)->with('success', 'Akun berhasil diimpor!');
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
