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
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as File2;
use Illuminate\Http\Request;



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
        $this->progresAkun();
        $this->progresKegiatan();
        $this->progresPeriode();

        // $files = File::where('transaksi_id', $transaksi_id)->get();
        return view('page.administrasi.file.index', [
            'transaksi' => $transaksi,
            'fungsi' => $fungsi,
            'files' => File::where('transaksi_id', $transaksi->id)
                ->filter($query)
                ->paginate(200)
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
        $this->progres($request->transaksi_id);
        return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&periode=' . $periode . '&fungsi=' . $fungsi)->with('success', 'Berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($fungsi, $periode, $kegiatan, $akun, $transaksi, $filename)
    {
        // Path ke file PDF
        $path = public_path("administrasis/{$fungsi}/{$periode}/{$kegiatan}/{$akun}/{$transaksi}/{$filename}");

        // Cek apakah file PDF ada
        if (!file_exists($path)) {
            dd('File not found');
        }

        // Tampilkan file PDF
        return response()->file($path, ['Content-Type' => 'application/pdf']);
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
                'files.*.max' => 'Ukuran file maksimal 20 mb.',

            ]);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
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
                $path = public_path('administrasis/' . $request->fungsi . '/' . $periode->nama . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $transaksi->nama);
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

        return redirect('/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $akun->id . '&kegiatan=' . $kegiatan->id . '&periode=' . $periode->slug . '&fungsi=' . $fungsi)->with('success', 'File ' . $namaFile . ' Berhasil Ditambahkan!');
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
        $path = public_path("administrasis/$fungsi/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/$nama_file");

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

    public function viewFile(Request $request)
    {
        $fungsi = $request->query('fungsi');
        $periode = PeriodeAdministrasi::where('slug', $request->query('periode'))->first();
        $kegiatan = KegiatanAdministrasi::find($request->query('kegiatan'));
        $akun = Akun::find($request->query('akun'));
        $transaksi = Transaksi::find($request->query('transaksi'));

        if (!$periode || !$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        $nama_file = $request->query('nama_file');

        // Bentuk path file
        $path = public_path("administrasis/{$fungsi}/{$periode->nama}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$nama_file}");

        // Periksa apakah file ada
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        return response()->file($path);
    }
}
