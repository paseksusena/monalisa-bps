<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\Akun;
use App\Http\Requests\StoreAkunRequest;
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
    // menampilkan halaman akun, dengan alamat page/administrasi/akun/index
    public function index()
    {
        $search = request('search');
        $fungsi = $this->getFungsi();
        $kegiatan = $this->getKegiatan();

        // Mendapatkan parameter urutan
        $order_nama = request('order-nama');
        $order_progres = request('order-progres');

        $this->progresKegiatan();

        // Mendapatkan data akun berdasarkan kegiatan_id dan filter
        $akunQuery = Akun::where('kegiatan_id', $kegiatan->id)->filter(request()->except(['search']));

        // Mengatur pengurutan berdasarkan nama jika parameter urutan nama tersedia
        if ($order_nama) {
            $akunQuery->orderBy('nama', $order_nama);
        }

        // Mengatur pengurutan berdasarkan progres jika parameter urutan progres tersedia
        if ($order_progres) {
            $akunQuery->orderBy('progres', $order_progres);
        }

        // Mendapatkan data akun
        $akuns = $akunQuery->get();
        $nilai_trans = [];
        $nilai_trans_all = 0;
        foreach ($kegiatan->akun as $akun) {
            $transaksi_nilai = $this->monitoring_nilai_transaksi($akun->id);
            // Jika monitoring_nilai_transaksi mengembalikan pesan error, lewati
            
            $nilai_trans[$akun->id] = $transaksi_nilai;
            
            $transaksi_nilai = str_replace('.', '', $transaksi_nilai);
            $numericTransNilai = (float) $transaksi_nilai;

            $nilai_trans_all = $nilai_trans_all + $transaksi_nilai;

        }

        $nilai_trans_all = number_format($nilai_trans_all, 0, ',', '.');



        // Mengirimkan data ke view
        return view('page.administrasi.akun.index', [
            'kegiatan' => $kegiatan,
            'akuns' => $akuns,
            'fungsi' => $fungsi,
            'nilai_trans' => $nilai_trans,
            'nilai_trans_all'=> $nilai_trans_all,
        ]);
    }


    // function untuk menyimpan akun 
    public function store(StoreAkunRequest $request)
    {

        try {
            // melakukan validasi nama, kegiatan_id
            $requestValidasi = $request->validate([
                'nama' => 'required|max:550',
                'kegiatan_id' => 'required'
            ]);

            //mengambil request fungsi dan kegiatan_id
            $fungsi = $request->fungsi;
            $kegiatan_id = $request->kegiatan_id;


            //melakukan pencarian nama akun agar tidak dupikat
            $file = Akun::where('kegiatan_id', $kegiatan_id)->get();
            $find = $file->where('nama', $request->nama)->first();

            //jika ditemukan nama yg sama return error 
            if ($find) {
                return back()->with('error', 'Nama akun ' . $request->nama . ' telah tersedia!');
            }

            //jika tidak buat di akun model
            Akun::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }
        return redirect('/administrasi/akun?kegiatan=' . $kegiatan_id . '&fungsi=' . $fungsi)->with('success', 'Akun berhasil ditambahkan!');
    }

    // function untuk menampilkan data yang akan di edit akun
    public function edit($id)
    {
        $akuns = Akun::find($id);
        return response()->json($akuns);
    }

    //function update untuk akun 
    public function update(Request $request)
    {
        // melakuan request validasi nama
        $requestValidasi = $request->validate([
            'nama' => 'required|max:550',
        ]);

        // ambil request fungsi, kegiatan, dan session
        $fungsi = $request->fungsi;
        $kegiatan = $request->kegiatan;
        $session  = session('selected_year');

        //mencari nama akun yang sama dengan akun yang akan dibuat
        $existingAkun = Akun::where('kegiatan_id', $kegiatan)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        // jika ada nama akun yang sama, maka return  error 
        if ($existingAkun) {
            return back()->with('error', 'Nama kegiatan ' . $requestValidasi['nama'] . ' telah tersedia!');
        }


        $kegiatan = KegiatanAdministrasi::where('id', $kegiatan)->first();

        //membuat folder untuk akun
        $akun = Akun::findOrFail($request->id);

        //proses membuat alamat folder akun 
        $oldFolderPath = 'public/administrasis/'  . $session  . '/' . $fungsi . '/' . $kegiatan->nama . '/' . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session  . '/' . $fungsi . '/'  . $kegiatan->nama . '/' . $request->nama;
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
        // melakukan update akun
        $akun->update($requestValidasi);
        return redirect('/administrasi/akun?fungsi=' . $fungsi . '&kegiatan=' . $kegiatan->id)->with('success', 'Akun ' . $requestValidasi['nama'] . ' berhasil diubah!');
    }

    //function untuk mengahpus
    public function destroy(Akun $akun, StoreAkunRequest $request)
    {
        $fungsi = $request->fungsi;
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $session = session('selected_year');

        //mencari file path yang akan dihapus
        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}/{$akun->nama}";
        File::deleteDirectory($filePath);

        //melakukan penghapusan transaksi dan file di dalam akun 
        $akun->transaksi()->each(function ($transaksi) {
            $transaksi->file()->delete();
            $transaksi->delete();
        });

        //menghapus akun
        $akun->delete();
        return back()->with('success', 'Akun ' . $akun->nama . ' berhasil dihapus!');
    }

    // function untuk melakukan import akun menggunakan excel
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
            // memanggil import di AkunImport
            Excel::import(new AkunImport($kegiatan_id), $filePath);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }

        return back()->with('success', 'Akun berhasil diimpor!');
    }

    //fungsi untuk mengambil kegiatan akun
    public function getKegiatan()
    {
        $kegiatan = KegiatanAdministrasi::where('id', request('kegiatan'))->first();
        return $kegiatan;
    }

    //fungsi untuk mengambil fungsi akun

    public function getFungsi()
    {
        $fungsi = request('fungsi');
        return $fungsi;
    }

    // menghitung progres kegiatan administrasi 
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
    public function monitoring_nilai_transaksi($id)
    {
        $akun = Akun::find($id);
        $nilai_trans = 0;
        $total_nilai = 0;
            foreach ($akun->transaksi as $transaksi) {
                $nilai = $transaksi->nilai_trans;

                if ($nilai !== null && $nilai !== '') {
                    $nilai = str_replace('.', '', $nilai);
                    $numericTransNilai = (float) $nilai;
                    $total_nilai += $numericTransNilai;
                }
            }

            // Mengembalikan nilai setelah semua perulangan selesai
            $nilai_trans += $total_nilai;

            $nilai_trans = number_format($nilai_trans, 0, ',', '.');
// dd($nilai_trans);


            return $nilai_trans;
        

        // Format nilai akhir di luar perulangan

    }




    public function monitoring_nilai_transaksi_akun($id)
    {
        $kegiatans = KegiatanAdministrasi::find($id);

        if ($kegiatans === null) {
            return 'Data kegiatan tidak ditemukan.';
        }

        $nilai_trans = 0;

        foreach ($kegiatans->akun as $akun) {
            $total_nilai = 0; // Inisialisasi total nilai untuk setiap akun

            foreach ($akun->transaksi as $transaksi) {
                $nilai = $transaksi->nilai_trans;
                if ($nilai !== null && $nilai !== '') {
                    $nilai = str_replace('.', '', $nilai);
                    $numericTransNilai = (float) $nilai;
                    $total_nilai += $numericTransNilai;
                }
            }

            $nilai_trans += $total_nilai; // Menambahkan total nilai akun ke nilai total keseluruhan
        }

        // Format nilai akhir di luar perulangan
        $nilai_trans = number_format($nilai_trans, 0, ',', '.');

        return $nilai_trans;
    }
}
