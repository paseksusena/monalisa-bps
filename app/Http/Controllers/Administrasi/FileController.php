<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FileImport;
use App\Models\KegiatanAdministrasi;
use App\Models\PeriodeAdministrasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as File2;


class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $fungsi = request('fungsi');
        // $periode = request('periode');
        // $kegiatan = request('kegiatan');
        // $akun = request('akun');

        $periode = PeriodeAdministrasi::where('slug', request('periode'))->first();
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
        $akun = Akun::where('id', request('akun'))->first();
        $transaksi = Transaksi::where('id', request('transaksi'))->first();
        $search = request('search');
        //melakukan penambahan parameter
        $previousQuery = request()->except(['search']);
        $query = array_merge($previousQuery, ['search' => $search]);


        $this->progres($transaksi->id);

        // $files = File::where('transaksi_id', $transaksi_id)->get();
        return view('administrasi.file.index', [
            'transaksi' => $transaksi,
            'fungsi' => $fungsi,
            'files' => File::where('transaksi_id', $transaksi->id)
                ->filter($query)
                ->paginate(10)
                ->appends(['search' => $search]),
            'periode' => $periode,
            'kegiatan' => $kegiatan,
            'akun' => $akun,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transaksi = request('transaksi');
        $akun = request('akun');
        $kegiatan = request('kegiatan');
        $periode = request('periode');
        $fungsi = request('fungsi');
        // dd( $akun, $kegiatan, $periode, $fungsi);

        return view('administrasi.file.create', [
            'transaksi' => $transaksi,
            'akun' => $akun,
            'kegiatan' => $kegiatan,
            'periode' => $periode,
            'fungsi' => $fungsi,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        try {
            $requestValidasi = $request->validate([
                'judul' => 'required|max:50',
                'transaksi_id' => 'required'

            ]);
            $fungsi = $request->fungsi;
            $periode = $request->periode;
            $kegiatan = $request->kegiatan;
            $akun = $request->akun;
            $transaksi_id = $request->transaksi_id;

            $file = File::where('transaksi_id', $transaksi_id)->get();
            $find = $file->where('judul', $request->judul)->first();
            if ($find) {
                return back()->with('error', 'Nama laci ' . $request->judul . ' telah tersedia!');
            }

            File::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }
        return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateFileRequest $request, File $file)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file, StoreFileRequest $request)
    {

        if ($file->status === 1) {

            //menghapus di folder
            $fungsi = $request->fungsi;
            $periode = PeriodeAdministrasi::where('slug', $request->periode)->first();
            $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
            $akun = Akun::where('id', $request->akun)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $filePath = "administrasi/$fungsi/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$file->namaFile}";

            File2::delete(public_path($filePath));
            $fileOld = $file->namaFile;
            //upadate di tabel file
            $file->file = null;
            $file->ukuran_file = 0;
            $file->namaFile = null;
            $file->status = false;
            $file->save();
            $this->progres($transaksi->id);
            return back()->with('success', 'File ' . $fileOld . ' berhasi dihapus!');
        } else {
            $file->delete();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $this->progres($transaksi->id);
            return back()->with('success', 'Laci ' . $file->judul . ' berhasil dihapus!');
        }
    }

    public function exportExcel($id)
    {
        $fungsi = request('fungsi');
        $periode = request('periode');
        $kegiatan = request('kegiatan');
        $akun_id = request('akun');
        $transaksi_id = request('transaksi');
        return view('administrasi.file.create-excel', [
            'transaksi_id' => $id,
            'fungsi' => $fungsi,
            'periode' => $periode,
            'kegiatan' => $kegiatan,
            'akun' => $akun_id,


        ]);
    }

    public function stroreExcel(StoreFileRequest $request)
    {
        try {
            // Simpan file ke direktori "DataFile"
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DataFile', $fileName);

            // Ambil data dari permintaan
            $fungsi = $request->fungsi;
            $periode = $request->periode;
            $kegiatan = $request->kegiatan;
            $akun = $request->akun;
            $transaksi_id = $request->transaksi_id;
            // Import file Excel
            Excel::import(new FileImport($transaksi_id), public_path('/DataFile/' . $fileName));


            // Redirect kembali dengan pesan sukses
            return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Berhasil diimport');
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }
    }


    // public function addFile(StoreFileRequest $request)
    // {
    //     //Validasi pertama
    //     $request->validate([
    //         'file' => 'required|file|max:20480|mimes:pdf' // max:20480 untuk batas 20 MB (20 * 1024 = 20480 KB)
    //     ]);
    //     //Untuk membuat direktori penyimpanan file
    //     $fungsi = (request('fungsi'));
    //     $periode = PeriodeAdministrasi::where('slug', request('periode'))->first();
    //     $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
    //     $akun = Akun::where('id', request('akun'))->first();
    //     $transaksi = Transaksi::where('id', request('transaksi'))->first();


    //     //Validasi kedua, cari nama judul pada laci administrasi berdasarkan nama file
    //     $file = $request->file('file');
    //     $fileInfo = pathinfo($request->file('file')->getClientOriginalName());
    //     $file = File::where('transaksi_id', $transaksi->id)->get();
    //     $namaFile = $fileInfo['filename'];

    //     $updateFile = $file->where('judul', $namaFile)->first();

    //     if ($updateFile) {
    //         // dd('hola');
    //         //nama file yang berisikan formatnya
    //         $namaFile = $fileInfo['basename'];
    //         $ukuranFile = $request->file('file')->getSize(); // Ukuran dalam byte
    //         $ukuranFileMB = round($ukuranFile / 1024 / 1024, 2); // Ubah ke megabyte dan round to 2 desimal
    //         $path = public_path('administrasi/' . $fungsi . '/' . $periode->nama . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $transaksi->nama);
    //         $request->file('file')->move($path, $namaFile);
    //         // dd($namaFile, $ukuranFileMB . ' MB');
    //         $requestValidasi['namaFile'] = $namaFile;
    //         $requestValidasi['file'] = $path;
    //         $requestValidasi['ukuran_file'] = $ukuranFileMB;

    //         $updateFile->namaFile = $requestValidasi['namaFile'];
    //         $updateFile->file = $requestValidasi['file'];
    //         $updateFile->ukuran_file = $requestValidasi['ukuran_file'];
    //         $updateFile->status = true;
    //         $updateFile->save();

    //         return redirect('/administrasi/file?transaksi=' . $transaksi->id . '&akun= ' . $akun->id . '&kegiatan=' . $kegiatan->id . '&periode= ' . $periode->id . '&fungsi=Sosial');


    //     } else {
    //         return back()->with('error', 'Nama File ' . $namaFile . ' tidak dapat ditemukan dalam laci transaksi!');
    //     }









    // }


    //Untuk membuat direktori penyimpanan file

    public function addFile(StoreFileRequest $request)
    {
        // Lakukan validasi untuk setiap file yang diunggah
        try {
            $validatedData = $request->validate([
                'files.*' => 'required|file|max:20480|mimes:pdf'
            ], [
                'files.*.mimes' => 'File harus PDF.',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'File harus PDF.');
        }

        foreach ($request->file('files') as $uploadedFile) {

            $fileInfo = pathinfo($uploadedFile->getClientOriginalName());
            $namaFile = $fileInfo['filename'];

            //  Untuk membuat direktori penyimpanan file
            $fungsi = request('fungsi');
            $periode = PeriodeAdministrasi::where('slug', request('periode'))->first();
            $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
            $akun = Akun::where('id', request('akun'))->first();
            $transaksi = Transaksi::where('id', request('transaksi'))->first();

            $file = File::where('transaksi_id', $transaksi->id)->where('judul', $namaFile)->first();
            if ($file) {
                $namaFile = $fileInfo['basename'];
                $ukuranFile = $uploadedFile->getSize(); // Ukuran dalam byte
                $ukuranFileMB = round($ukuranFile / 1024 / 1024, 2); // Ubah ke megabyte dan round to 2 desimal
                $path = public_path('administrasi/' . $request->fungsi . '/' . $periode->nama . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $transaksi->nama);
                $uploadedFile->move($path, $namaFile);
                $file->namaFile = $namaFile;
                $file->file = $path;
                $file->ukuran_file = $ukuranFileMB;
                $file->status = true;
                $file->save();

                $this->progres($transaksi->id);
            } else {
                return back()->with('error', 'Nama File ' . $namaFile . ' tidak dapat ditemukan dalam laci transaksi!');
            }
        }

        return redirect('/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $akun->id . '&kegiatan=' . $kegiatan->id . '&periode=' . $periode->slug . '&fungsi=' . $fungsi);
    }


    public function download()
    {
        $fungsi = request('fungsi');
        $periode = PeriodeAdministrasi::where('slug', request('periode'))->first();
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
        $akun = Akun::where('id', request('akun'))->first();
        $transaksi = Transaksi::where('id', request('transaksi'))->first();

        if (!$periode || !$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        $nama_file = request('nama_file');

        // Bentuk path file
        $path = public_path("administrasi/$fungsi/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/$nama_file");

        // Periksa apakah file ada
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Lakukan pengunduhan file
        return response()->download($path);
    }

    // menghitung progres
    public function progres($transaksi_id)
    {
        $totalFiles = File::where('transaksi_id', $transaksi_id)->count();
        $completedFiles = File::where('transaksi_id', $transaksi_id)->where('status', 1)->count();

        // Menghindari pembagian dengan nol
        if ($totalFiles > 0) {
            // Menghitung persentase progres
            $progres = ($completedFiles / $totalFiles) * 100;
        } else {
            $progres = 0; // Jika tidak ada file, progres diatur menjadi 0
        }
        $transaksi = Transaksi::find($transaksi_id);
        $transaksi['progres'] = $progres;
        $transaksi['amount_file'] = $totalFiles;
        $transaksi['complete_file'] = $completedFiles;
        $transaksi->save();

        return 0;
    }
}
