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
use Illuminate\Support\Facades\File as File2;
use Illuminate\Http\Request;
use Carbon\Carbon;



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

        // $files = File::where('transaksi_id', $transaksi_id)->get();
        return view('page.administrasi.file.index', [
            'transaksi' => $transaksi,
            'fungsi' => $fungsi,
            'files' => File::where('transaksi_id', $transaksi->id)
                ->filter($query)
                ->paginate(200)
                ->appends(['search' => $search]),
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
        $fungsi = request('fungsi');
        // dd( $akun, $kegiatan, $periode, $fungsi);

        return view('administrasi.file.create', [
            'transaksi' => $transaksi,
            'akun' => $akun,
            'kegiatan' => $kegiatan,
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
                'judul' => 'required|max:550',
                'transaksi_id' => 'required',
                'penanggung_jwb' => 'required'

            ]);
            $fungsi = $request->fungsi;
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
        return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&fungsi=' . $fungsi)->with('success', 'Berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($fungsi, $kegiatan, $akun, $transaksi, $filename)
    {
        // Path ke file PDF
        $path = public_path("administrasis/{$fungsi}/{$kegiatan}/{$akun}/{$transaksi}/{$filename}");

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
            $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
            $akun = Akun::where('id', $request->akun)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $filePath = "storage/administrasi/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$file->namaFile}";

            File2::delete(public_path($filePath));

            $fileOld = $file->namaFile;
            //upadate di tabel file
            $file->file = null;
            $file->ukuran_file = 0;
            $file->namaFile = null;
            $file->status = false;
            $file->update = null;
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


    public function stroreExcel(StoreFileRequest $request)
    {
        try {
            // Simpan file ke direktori "DataFile"
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DataFile', $fileName);

            // Ambil data dari permintaan
            $fungsi = $request->fungsi;
            $kegiatan = $request->kegiatan;
            $akun = $request->akun;
            $transaksi_id = $request->transaksi_id;
            // Import file Excel
            Excel::import(new FileImport($transaksi_id), public_path('/DataFile/' . $fileName));


            // Redirect kembali dengan pesan sukses
            return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&fungsi=' . $fungsi)->with('success', 'Berhasil diimport');
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
        $errorMessage = '';

        foreach ($request->file('files') as $uploadedFile) {
            // Periksa apakah file diunggah

            $maxFileSize = 1073741824; // 1 GB dalam kilobyte
            if ($uploadedFile->getSize() > $maxFileSize) {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': Ukuran file melebihi batas maksimum (1 GB).' . PHP_EOL;
                continue;
            }

            // Periksa apakah file diunggah
            if (!$uploadedFile->isValid()) {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': File tidak valid.' . PHP_EOL;
                continue;
            }

            // Periksa apakah file memiliki ekstensi yang benar
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            if ($fileExtension !== 'pdf') {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': File harus berformat PDF.' . PHP_EOL;
                continue;
            }
            $fileInfo = pathinfo($uploadedFile->getClientOriginalName());
            $namaFile = $fileInfo['filename'];

            //  Untuk membuat direktori penyimpanan file
            $fungsi = request('fungsi');
            $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
            $akun = Akun::where('id', request('akun'))->first();
            $transaksi = Transaksi::where('id', request('transaksi'))->first();

            $file = File::where('transaksi_id', $transaksi->id)->where('judul', $namaFile)->first();
            if ($file) {
                $namaFile = $fileInfo['basename'];
                $ukuranFile = $uploadedFile->getSize(); // Ukuran dalam byte
                $ukuranFileMB = round($ukuranFile / 1024 / 1024, 2); // Ubah ke megabyte dan round to 2 desimal
                $path = public_path('storage/administrasis/' . $request->fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $transaksi->nama);
                $uploadedFile->move($path, $namaFile);
                $file->namaFile = $namaFile;
                $file->file = $path;
                $file->ukuran_file = $ukuranFileMB;
                $file->status = true;
                $file->update = Carbon::now()->toDateString();
                $file->save();

                $this->progres($transaksi->id);
            } else {
                $errorMessage .= 'Nama file ' . $namaFile . ' tidak dapat ditemukan dalam laci transaksi!';
                continue;
            }
        }

        if ($errorMessage) {
            // Jika terjadi kesalahan validasi, kembalikan dengan pesan kesalahan
            return back()->with('error', $errorMessage);
        }

        return redirect('/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $akun->id . '&kegiatan=' . $kegiatan->id . '&fungsi=' . $fungsi)->with('success', 'File berhasil ditambahkan!');
    }

    public function download()
    {
        $fungsi = request('fungsi');
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
        $akun = Akun::where('id', request('akun'))->first();
        $transaksi = Transaksi::where('id', request('transaksi'))->first();

        if (!$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        $nama_file = request('nama_file');

        // Bentuk path file
        $path = public_path("storage/administrasis/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/$nama_file");

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
        $kegiatan = KegiatanAdministrasi::find($request->query('kegiatan'));
        $akun = Akun::find($request->query('akun'));
        $transaksi = Transaksi::find($request->query('transaksi'));

        if (!$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        $nama_file = $request->query('nama_file');

        // Bentuk path file
        $path = public_path("storage/administrasis/{$fungsi}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$nama_file}");

        // Periksa apakah file ada
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        return response()->file($path);
    }
    public function ceklist(Request $request, $id)
    {
        // $request->validate([
        //     'isChecked' => 'required|boolean',
        // ], [
        //     'isChecked.required' => 'The isChecked field is required.',
        //     'isChecked.boolean' => 'The isChecked field must be true or false.',
        // ]);

        $isChecked = $request->isChecked;
        $file = File::find($id);
        if ($file['ceklist'] === 1) {
            $file['ceklist'] = 0;
        } else {
            $file['ceklist'] = 1;
        }
        $file->save();

        return response()->json(['message' => $id, 'isChecked' => $isChecked]);
    }
}
