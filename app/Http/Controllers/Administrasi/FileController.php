<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Models\File;
use App\Http\Requests\StoreFileRequest;
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

    // menampilkan halaman file,  alamat page/administrasi/file/index
    public function index(Request $request)
    {
        // Mengambil parameter dari request
        $fungsi = request('fungsi');
        $kegiatan = KegiatanAdministrasi::findOrFail(request('kegiatan'));
        $akun = Akun::findOrFail(request('akun'));
        $transaksi = Transaksi::findOrFail(request('transaksi'));
        $search = request('search');

        // Query untuk mendapatkan file berdasarkan transaksi dan filter yang diberikan
        $filesQuery = File::where('transaksi_id', $transaksi->id)
            ->filter([
                'status' => request('status'),
                'ceklist' => request('ceklist'),
                'search' => $search,
                'transaksi' => $transaksi->id,
            ]);

        // Paginate hasil query file
        $files = $filesQuery->paginate(20000)
            ->appends(['search' => $search, 'status' => request('status'), 'ceklist' => request('ceklist')]);

        // Memperbarui progres
        $this->progres($transaksi->id);
        $this->progresAkun();
        $this->progresKegiatan();

        // Menentukan status dan ceklist dari request, jika tidak ada default ke 'Semua'
        $status = request('status') ?: 'Semua';
        $ceklist = request('ceklist') ?: 'Semua';

        // Mengembalikan tampilan HTML dengan data yang diperlukan
        return view('page.administrasi.file.index', [
            'transaksi' => $transaksi,
            'fungsi' => $fungsi,
            'files' => $files,
            'kegiatan' => $kegiatan,
            'akun' => $akun,
            'status' => $status,
            'ceklist' => $ceklist
        ]);
    }

    // function untuk menyimpan file di dalam folder transaksi
    public function store(StoreFileRequest $request)
    {
        try {
            // Validasi request
            $requestValidasi = $request->validate([
                'judul' => 'required|max:550',
                'transaksi_id' => 'required',
                'penanggung_jwb' => 'required'
            ]);

            // Mengambil parameter dari request
            $fungsi = $request->fungsi;
            $kegiatan = $request->kegiatan;
            $akun = $request->akun;
            $transaksi_id = $request->transaksi_id;

            // Memeriksa apakah file dengan judul yang sama sudah ada
            $file = File::where('transaksi_id', $transaksi_id)->get();
            $find = $file->where('judul', $request->judul)->first();
            if ($find) {
                // Jika file sudah ada, kembalikan dengan pesan kesalahan
                return back()->with('error', 'Nama laci ' . $request->judul . ' telah tersedia!');
            }

            // Membuat record file baru
            File::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }

        // Memperbarui progres transaksi
        $this->progres($request->transaksi_id);

        // Mengembalikan ke halaman administrasi file dengan pesan sukses
        // return redirect('/administrasi/file?transaksi=' . $transaksi_id . '&akun=' . $akun . '&kegiatan=' . $kegiatan . '&fungsi=' . $fungsi)->with('success', 'Berhasil disimpan');
        return back()->with('success', 'Berhasil disimpan');
    }

    //function untuk show atau preview file 
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

    //function untuk menghapus laci file atau file 
    public function destroy(File $file, StoreFileRequest $request)
    {

        //jika status file = 1, berati ada file di dalam laci, jadi yang dihapus dalah filenya bukan file lacinya
        if ($file->status === 1) {

            // proses penghapus di folder
            //proses mencari alamat di direktori folder file
            $session = session('selected_year');
            $fungsi = $request->fungsi;
            $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
            $akun = Akun::where('id', $request->akun)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$file->namaFile}";


            //proses pengahapusa file pdf di direktori 
            File2::delete(public_path($filePath));


            // proses update status file menjadi 0
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
        }

        //jika status file selain 1, atau 0, berati hapus file lacinya
        else {
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

    //Function untuk upload file di dalam transaksi
    public function addFile(StoreFileRequest $request)
    {
        // Inisialisasi pesan kesalahan dan mengambil tahun dari session
        $errorMessage = '';
        $session = session('selected_year');

        // Loop melalui setiap file yang diunggah
        foreach ($request->file('files') as $uploadedFile) {
            // Periksa ukuran file (maksimal 1 GB)
            $maxFileSize = 1073741824; // 1 GB dalam byte
            if ($uploadedFile->getSize() > $maxFileSize) {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': Ukuran file melebihi batas maksimum (1 GB).' . PHP_EOL;
                continue;
            }

            // Periksa apakah file valid
            if (!$uploadedFile->isValid()) {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': File tidak valid.' . PHP_EOL;
                continue;
            }

            // Periksa apakah file berformat PDF
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            if ($fileExtension !== 'pdf') {
                $errorMessage .= $uploadedFile->getClientOriginalName() . ': File harus berformat PDF.' . PHP_EOL;
                continue;
            }

            // Mendapatkan informasi nama file
            $fileInfo = pathinfo($uploadedFile->getClientOriginalName());
            $namaFile = $fileInfo['filename'];

            // Membuat direktori penyimpanan file berdasarkan parameter request
            $fungsi = request('fungsi');
            $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
            $akun = Akun::where('id', request('akun'))->first();
            $transaksi = Transaksi::where('id', request('transaksi'))->first();

            // Cek apakah file dengan nama tersebut sudah ada
            $file = File::where('transaksi_id', $transaksi->id)->where('judul', $namaFile)->first();
            if ($file) {
                // Jika ada, update informasi file
                $namaFile = $fileInfo['basename'];
                $ukuranFile = $uploadedFile->getSize(); // Ukuran dalam byte
                $ukuranFileMB = round($ukuranFile / 1024 / 1024, 2); // Ubah ke megabyte
                $path = public_path('storage/administrasis/' . $session . '/' . $request->fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $transaksi->nama); //proses penempatan file
                $uploadedFile->move($path, $namaFile);
                $file->namaFile = $namaFile;
                $file->ukuran_file = $ukuranFileMB;
                $file->status = true;
                $file->update = Carbon::now()->toDateString();
                $file->save();

                // Memperbarui progres transaksi
                $this->progres($transaksi->id);
            } else {
                // Jika file tidak ditemukan dalam laci transaksi, tambahkan pesan kesalahan
                $errorMessage .= 'Nama file ' . $namaFile . ' tidak dapat ditemukan dalam laci transaksi!';
                continue;
            }
        }

        // Jika ada pesan kesalahan, kembalikan dengan pesan kesalahan
        if ($errorMessage) {
            return back()->with('error', $errorMessage);
        }

        // Jika sukses, kembalikan ke halaman administrasi file dengan pesan sukses
        return redirect('/administrasi/file?transaksi=' . $transaksi->id . '&akun=' . $akun->id . '&kegiatan=' . $kegiatan->id . '&fungsi=' . $fungsi)->with('success', 'File berhasil ditambahkan!');
    }

    //function untuk download file dalam folder transaksi 
    public function download()
    {
        // Mengambil tahun yang dipilih dari session
        $session = session('selected_year');

        // Mendapatkan parameter 'fungsi' dari request
        $fungsi = request('fungsi');

        // Mencari record 'kegiatan' di database menggunakan ID dari request
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();

        // Mencari record 'akun' di database menggunakan ID dari request
        $akun = Akun::where('id', request('akun'))->first();

        // Mencari record 'transaksi' di database menggunakan ID dari request
        $transaksi = Transaksi::where('id', request('transaksi'))->first();

        // Memeriksa apakah record yang diperlukan ditemukan
        if (!$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        // Mendapatkan parameter 'nama_file' dari request
        $nama_file = request('nama_file');

        // Membentuk path file berdasarkan tahun dari session, fungsi, dan nama record
        $path = public_path("storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/$nama_file");

        // Memeriksa apakah file ada di path yang dibentuk
        if (!file_exists($path)) {
            // Mengembalikan response error 404 jika file tidak ditemukan
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Melakukan pengunduhan file jika file ada
        return response()->download($path);
    }

    // function menghitung progres dan update di transaksi
    public function progres($transaksi_id)
    {
        //hitung semua file berdasarkan transaksi id
        $totalFiles = File::where('transaksi_id', $transaksi_id)->count();

        //hitung semua file yang statusnya 1 berdasarkan transaksi id
        $completedFiles = File::where('transaksi_id', $transaksi_id)->where('status', 1)->count();

        // Menghindari pembagian dengan nol
        if ($totalFiles > 0) {
            // Menghitung persentase progres
            $progres = ($completedFiles / $totalFiles) * 100;
        } else {
            $progres = 0; // Jika tidak ada file, progres diatur menjadi 0
        }

        //proses melakukan update progres, jumlah file, dan jumlah file yang sudah terupload di tabel transaksi
        $transaksi = Transaksi::find($transaksi_id);
        $transaksi['progres'] = $progres;
        $transaksi['amount_file'] = $totalFiles;
        $transaksi['complete_file'] = $completedFiles;
        $transaksi->save();

        return 0;
    }

    //function untuk menghiung dan update progres di akun
    public function progresAkun()
    {
        // ambil semua data akun
        $akuns = Akun::all();

        // melakukan foreach akuns
        foreach ($akuns as $akun) {


            // ambil transaksi berdasarkan akun_id
            $transaksis = Transaksi::where('akun_id', $akun->id)->get();


            //proses melakukan penghitungan progres akun
            $totalFiles = 0; // total file
            $completeFile = 0; // total file terupload

            //lakukan perulangan dan jumlahkan semua total file dan total file terupload 
            foreach ($transaksis as $transaksi) {
                $totalFiles += $transaksi->amount_file;
                $completeFile += $transaksi->complete_file;
            }

            // cek jika 0 maka prosesnya 0, jika tidak maka lakukan penghitungan progres = (file teruload / total file )x 100%

            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // melakukan update ke progres, jumlah file dan file terupload di akun
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

    // funtion untuk menghitung progres kegiatan adminidtrasi
    public function progresKegiatan()
    {
        // Ambil semua data kegiatan
        $kegiatans = KegiatanAdministrasi::all();

        // Melakukan loop melalui setiap kegiatan
        foreach ($kegiatans as $kegiatan) {
            // Ambil semua akun yang terkait dengan kegiatan saat ini
            $akuns = Akun::where('kegiatan_id', $kegiatan->id)->get();

            // Inisialisasi variabel total file dan file yang sudah terupload
            $totalFiles = 0;
            $completeFile = 0;

            // Melakukan loop melalui setiap akun untuk menghitung total file dan file yang sudah terupload
            foreach ($akuns as $akun) {
                $totalFiles += $akun->amount_file;
                $completeFile += $akun->complete_file;
            }

            // Hitung progres kegiatan, jika total file lebih dari 0, jika tidak maka progres 0
            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // Melakukan update progres, jumlah file dan file yang sudah terupload di tabel KegiatanAdministrasi
            $kegiatan = KegiatanAdministrasi::find($kegiatan->id);
            if ($kegiatan) {
                $kegiatan->progres = $progres;
                $kegiatan->amount_file = $totalFiles;
                $kegiatan->complete_file = $completeFile;
                $kegiatan->save();
            }
        }

        // Mengembalikan 0 sebagai indikasi fungsi selesai
        return 0;
    }

    public function viewFile(Request $request)
    {

        $fungsi = $request->query('fungsi');
        $kegiatan = KegiatanAdministrasi::find($request->query('kegiatan'));
        $akun = Akun::find($request->query('akun'));
        $transaksi = Transaksi::find($request->query('transaksi'));
        $session = session('selected_year');

        if (!$kegiatan || !$akun || !$transaksi) {
            return response()->json(['error' => 'Invalid parameters.'], 400);
        }

        $nama_file = $request->query('nama_file');

        // Bentuk path file
        $path = public_path("storage/administrasis/{$session}/{$fungsi}/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}/{$nama_file}");

        // Periksa apakah file ada
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        return response()->file($path);
    }

    //function melakukan update verifikasi
    public function ceklist(Request $request, $id)
    {


        //jika ceklist, maka ubah status menjadi 1, dan sebaliknya
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
