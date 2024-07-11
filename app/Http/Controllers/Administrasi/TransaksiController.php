<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
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
     * Menampilkan halaman transaksi, dengan  alamat page/administrasi/transaksi/index
     */
    public function index()
    {
        $search = request('search');
        $order_kwt = request('order-no-kwt'); // Mendapatkan parameter urutan no_kwt
        $order_nama = request('order-nama'); // Mendapatkan parameter urutan nama
        $order_progres = request('order-progres'); // Mendapatkan parameter urutan progres
        $order_bulan_arsip = request('order-bulan-arsip'); // Mendapatkan parameter urutan bulan arsip
        $order_tanggal_akhir = request('order-tanggal-akhir'); // Mendapatkan parameter urutan tanggal akhir
        $order_nilai = request('order-nilai'); // Mendapatkan parameter urutan nilai transaksi

        $akun = $this->getAkun(); // Mendapatkan data akun
        $fungsi = $this->getFungsi(); // Mendapatkan data fungsi
        $kegiatan = $this->getKegiatan(); // Mendapatkan data kegiatan

        $this->progresAkun(); // Memanggil method untuk memproses akun
        $this->progresKegiatan(); // Memanggil method untuk memproses kegiatan

        // Mendapatkan data transaksi berdasarkan akun id dan filter
        $transaksisQuery = Transaksi::where('akun_id', $akun->id)->filter(request()->except(['search']));

        // Mengatur pengurutan berdasarkan no_kwt jika parameter urutan no_kwt tersedia
        if ($order_kwt) {
            $transaksisQuery->orderBy('no_kwt', $order_kwt);
        }

        // Mengatur pengurutan berdasarkan nama jika parameter urutan nama tersedia
        if ($order_nama) {
            $transaksisQuery->orderBy('nama', $order_nama);
        }

        // Mengatur pengurutan berdasarkan progres jika parameter urutan progres tersedia
        if ($order_progres) {
            $transaksisQuery->orderBy('progres', $order_progres);
        }

        // Mengatur pengurutan berdasarkan bulan arsip jika parameter urutan bulan arsip tersedia
        if ($order_bulan_arsip) {
            $transaksisQuery->orderBy('bln_arsip', $order_bulan_arsip);
        }

        // Mengatur pengurutan berdasarkan tanggal akhir jika parameter urutan tanggal akhir tersedia
        if ($order_tanggal_akhir) {
            $transaksisQuery->orderBy('tgl_akhir', $order_tanggal_akhir);
        }

        // Mengatur pengurutan berdasarkan nilai transaksi jika parameter urutan nilai transaksi tersedia
        if ($order_nilai) {
            $transaksisQuery->orderBy('nilai_trans', $order_nilai);
        }

        // Mendapatkan data transaksi
        $transaksis = $transaksisQuery->get();

        $nilai_total_trans = $this->monitoring_nilai_transaksi($akun->id);

        // Mengirimkan data ke view
        return view('page.administrasi.transaksi.index', compact('akun', 'transaksis', 'fungsi', 'kegiatan', 'nilai_total_trans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Menampilkan halaman form untuk membuat transaksi baru.
     */
    public function create()
    {
        // Mengambil nilai 'akun', 'kegiatan', dan 'fungsi' dari permintaan (request)
        $akun = request('akun');
        $kegiatan = request('kegiatan');
        $fungsi = request('fungsi');

        // Mengembalikan view 'administrasi.transaksi.create' dengan data yang diteruskan
        return view('administrasi.transaksi.create', [
            'akun' => $akun,
            'kegiatan' => $kegiatan,
            'fungsi' => $fungsi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Menyimpan data transaksi baru ke dalam database.
     */
    public function store(StoreTransaksiRequest $request)
    {
        try {
            // Validasi data yang diterima dari request
            $requestValidasi = $request->validate([
                'nama' => 'required|max:550',
                'tgl_akhir' => 'required',
                'bln_arsip' => 'required',
                'no_kwt' => 'required',
                'akun_id' => 'required',
                'nilai_trans' => 'required'
            ]);

            // Mengambil nilai fungsi, kegiatan, dan id akun dari request
            $fungsi = $request->fungsi;
            $kegiatan = $request->kegiatan;
            $akun_id = $request->akun_id;

            // Mencari akun berdasarkan id
            $akun = Akun::where('id', $akun_id)->first();

            // Mencari transaksi dengan nama yang sama di dalam akun yang sama
            $transaksi = Transaksi::where('akun_id', $akun->id)->get();
            $find = $transaksi->where('nama', $request->nama)->first();

            // Jika transaksi dengan nama yang sama sudah ada, kembalikan dengan pesan error
            if ($find) {
                return back()->with('error', 'Nama transaksi ' . $request->nama . ' telah tersedia!');
            }

            // Simpan data transaksi baru ke dalam database
            Transaksi::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian apabila terjadi error dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }

        //     // Redirect ke halaman daftar transaksi dengan parameter akun, kegiatan, dan fungsi
        //     return redirect('/administrasi/transaksi?akun=' . $akun_id . '&kegiatan=' . $kegiatan . '&fungsi=' . $fungsi)->with('success', 'Transaksi berhasil disimpan!');
        // 
        return back()->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Mengembalikan data transaksi yang akan diedit dalam bentuk JSON.
     */
    public function edit($id)
    {
        // Mengambil transaksi berdasarkan ID
        $transaksi = Transaksi::find($id);

        // Mengembalikan data transaksi dalam format JSON
        return response()->json($transaksi);
    }


    /**
     * Memperbarui data transaksi yang ada berdasarkan request yang diterima.
     */
    public function update(Request $request)
    {
        // Validasi request
        $requestValidasi = $request->validate([
            'nama' => 'required|max:550',
            'no_kwt' => 'required',
            'tgl_akhir' => 'required',
            'bln_arsip' => 'required',
            'nilai_trans' => 'required'
        ]);

        // Mendapatkan nilai fungsi, kegiatan, akun, dan session (jika digunakan)
        $fungsi = $request->fungsi;
        $kegiatan = $request->kegiatan;
        $akun = $request->akun;
        $session = session('selected_year');

        // Memeriksa apakah ada transaksi dengan nama yang sama di dalam akun yang sama
        $existingTransaksi = Transaksi::where('akun_id', $akun)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        // Jika transaksi dengan nama yang sama sudah ada, kembalikan dengan pesan kesalahan
        if ($existingTransaksi) {
            return back()->with('error', 'Nama transaksi ' . $requestValidasi['nama'] . ' sudah tersedia!');
        }

        // Mengambil data kegiatan dan akun berdasarkan ID
        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();
        $akun = Akun::where('id', $akun)->first();

        // Mengambil data transaksi berdasarkan ID yang diterima
        $transaksi = Transaksi::findOrFail($request->id);

        // Menyiapkan path folder lama dan baru untuk penyimpanan file
        $oldFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->nama;

        // Jika nama transaksi diubah, lakukan pengubahan path dan penyimpanan ulang file
        if ($request->nama !== $request->oldNama) {
            $pathOld = ($oldFolderPath);
            $files = Storage::files($pathOld);
            foreach ($files as $file) {
                // Mengganti jalur lama dengan jalur baru
                $file = Str::of($file)->replace(
                    'storage/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->oldNama,
                    'storage/administrasis/' . $session . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $akun->nama . '/' . $request->oldNama
                );
                $file_content = Storage::get($file);
                $file_name_parts = explode("/", $file);
                if (count($file_name_parts) > 0) {
                    $file_name = $file_name_parts[count($file_name_parts) - 1];
                    $file_path = ($newFolderPath . '/' . $file_name);
                    $storage = Storage::put($file_path, $file_content);
                    $delete = Storage::deleteDirectory($oldFolderPath);
                }
            }
        }

        // Memperbarui data transaksi dengan data yang valid dari request
        $transaksi->update($requestValidasi);

        // Redirect ke halaman index transaksi dengan menyertakan query string fungsi, kegiatan, dan akun
        // return redirect('/administrasi/transaksi?fungsi=' . $fungsi . '&kegiatan=' . $kegiatan->id . '&akun=' . $akun->id)->with('success', $requestValidasi['nama'] . ' berhasil diubah!');
        return back()->with('success', $requestValidasi['nama'] . ' berhasil diubah!');
    }


    /**
     * Menghapus data transaksi yang telah dipilih dari penyimpanan.
     */
    public function destroy(Transaksi $transaksi, StoreTransaksiRequest $request)
    {
        // Mendapatkan nilai fungsi dan session
        $fungsi = $request->fungsi;
        $session = session('selected_year');

        // Mengambil data kegiatan dan akun berdasarkan ID yang diterima dari request
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $akun = Akun::where('id', $request->akun)->first();

        // Menyiapkan path folder untuk direktori file transaksi yang akan dihapus
        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}/{$transaksi->nama}";

        // Menghapus direktori dan seluruh isinya dari penyimpanan
        File::deleteDirectory($filePath);

        // Menghapus semua file terkait dari model Transaksi
        $transaksi->file()->delete();

        // Menghapus data transaksi dari penyimpanan
        $transaksi->delete();

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses bahwa transaksi telah dihapus
        return back()->with('success', 'Transaksi ' . $transaksi->nama . ' berhasil dihapus!');
    }


    /**
     * Mengimpor data transaksi dari file Excel yang diunggah.
     */
    public function storeExcel(StoreTransaksiRequest $request)
    {
        try {
            // Mendapatkan nilai fungsi, kegiatan, dan id akun dari request
            $fungsi = $request->fungsi;
            $kegiatan = $request->kegiatan;
            $akun_id = $request->akun_id;

            // Mengambil file yang diunggah dari request
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();

            // Pindahkan file yang diunggah ke folder 'Excel' di dalam public path
            $file->move('Excel', $fileName);

            // Menyiapkan path lengkap dari file yang diunggah
            $filePath = public_path('Excel/' . $fileName);

            // Mengimpor data dari file Excel ke dalam database menggunakan class TransaksiImport
            Excel::import(new TransaksiImport($akun_id), $filePath);

            // Setelah selesai mengimpor, hapus file Excel dari server lokal (jika file masih ada)
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses bahwa data Excel berhasil diimpor
        return back()->with('success', 'Data Excel berhasil diimpor!');
    }


    public function getAkun()
    {
        // Mengambil nilai ID akun dari request
        $akunId = request('akun');

        // Menggunakan Eloquent untuk mencari Akun berdasarkan ID
        $akun = Akun::where('id', $akunId)->first();

        return $akun;
    }
    public function getKegiatan()
    {
        // Mengambil nilai ID kegiatan dari request
        $kegiatanId = request('kegiatan');

        // Menggunakan Eloquent untuk mencari KegiatanAdministrasi berdasarkan ID
        $kegiatan = KegiatanAdministrasi::where('id', $kegiatanId)->first();

        return $kegiatan;
    }

    public function getFungsi()
    {
        // Mengambil nilai fungsi dari request
        $fungsi = request('fungsi');

        return $fungsi;
    }


    /**
     * Menghitung dan memperbarui progres untuk setiap Akun berdasarkan total file dan file yang sudah selesai.
     *
     */
    public function progresAkun()
    {
        // Ambil semua data Akun
        $akuns = Akun::all();

        // Looping setiap Akun
        foreach ($akuns as $akun) {
            // Ambil transaksi berdasarkan akun_id
            $transaksis = Transaksi::where('akun_id', $akun->id)->get();

            // Inisialisasi total file dan file yang sudah selesai
            $totalFiles = 0;
            $completeFile = 0;

            // Looping setiap transaksi untuk menghitung total file dan file yang sudah selesai
            foreach ($transaksis as $transaksi) {
                $totalFiles += $transaksi->amount_file;
                $completeFile += $transaksi->complete_file;
            }

            // Hitung progres, pastikan untuk menangani pembagian dengan nol
            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // Update progres, jumlah file, dan file yang sudah selesai di Akun terkait
            $akunToUpdate = Akun::find($akun->id);
            if ($akunToUpdate) {
                $akunToUpdate->progres = $progres;
                $akunToUpdate->amount_file = $totalFiles;
                $akunToUpdate->complete_file = $completeFile;
                $akunToUpdate->save();
            }
        }

        // Mengembalikan nilai 0 sebagai penanda bahwa proses selesai
        return 0;
    }

    /**
     * Menghitung dan memperbarui progres untuk setiap KegiatanAdministrasi berdasarkan total file dan file yang sudah selesai.
     */
    public function progresKegiatan()
    {
        // Ambil semua data KegiatanAdministrasi
        $kegiatans = KegiatanAdministrasi::all();

        // Looping setiap KegiatanAdministrasi
        foreach ($kegiatans as $kegiatan) {
            // Ambil semua Akun yang terkait dengan kegiatan ini
            $akuns = Akun::where('kegiatan_id', $kegiatan->id)->get();

            // Inisialisasi total file dan file yang sudah selesai
            $totalFiles = 0;
            $completeFile = 0;

            // Looping setiap Akun untuk menghitung total file dan file yang sudah selesai
            foreach ($akuns as $akun) {
                $totalFiles += $akun->amount_file;
                $completeFile += $akun->complete_file;
            }

            // Hitung progres, pastikan untuk menangani pembagian dengan nol
            $progres = $totalFiles > 0 ? ($completeFile / $totalFiles) * 100 : 0;

            // Update progres, jumlah file, dan file yang sudah selesai di KegiatanAdministrasi terkait
            $kegiatanToUpdate = KegiatanAdministrasi::find($kegiatan->id);
            if ($kegiatanToUpdate) {
                $kegiatanToUpdate->progres = $progres;
                $kegiatanToUpdate->amount_file = $totalFiles;
                $kegiatanToUpdate->complete_file = $completeFile;
                $kegiatanToUpdate->save();
            }
        }

        // Mengembalikan nilai 0 sebagai penanda bahwa proses selesai
        return 0;
    }

    public function monitoring_nilai_transaksi($id){
        $akuns = Akun::find($id);
        $nilai_trans = 0;
    
        foreach($akuns->transaksi as $transaksi){
            // Retrieve the transaction value
            $nilai = $transaksi->nilai_trans;
    
            // Check if the transaction value is not null and not empty
            if ($nilai !== null && $nilai !== '') {
                // Remove dot as thousand separator
                $nilai = str_replace('.', '', $nilai);
    
                // Convert string to numeric value (float)
                $numericTransNilai = (float) $nilai;
    
                // Add transaction value to $nilai_trans
                $nilai_trans += $numericTransNilai;
            }
        }
    
        // Debug to see the final total transaction value
        $nilai_trans = number_format($nilai_trans, 0, ',', '.');


    
        // Return the total transaction value
        return $nilai_trans;
    }
    
}
