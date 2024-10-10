<?php

namespace App\Http\Controllers\Administrasi;

use App\Models\KegiatanAdministrasi;
use App\Http\Requests\StoreKegiatanAdministrasiRequest;
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
    public function index()
    {

        $currentYear = Carbon::now()->year;
        $startYear = $currentYear - 5;
        $years = range($startYear, $currentYear);

        // Jika tidak ada session selected_year, gunakan tahun sekarang
        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        $selected_year = session('selected_year');

        $amount_file = 0;
        $complete_file = 0;
        $progres = 0;

        $fungsi = $this->getFungsi();

        if ($fungsi == 0) {
            return redirect('/administrasi');
        }

        $order_nama = request('order-nama');
        $order_progres = request('order-progres');

        $kegiatanQuery = KegiatanAdministrasi::where('fungsi', $fungsi)
            ->where('tahun', $selected_year)
            ->filter(request()->except(['search']));



        //menghitung jumlah verifikasi
        $verifikasi = $this->total_verifikasi($fungsi);

        // akses nilai verifikasi
        $complete_verifikasis = $verifikasi['complete_verifikasis'];
        $total_verifikasis = $verifikasi['total_verifikasis'];
        $all_complete_verifikasis = $verifikasi['all_complete_verifikasis'];
        $all_total_verifikasis = $verifikasi['all_total_verifikasis'];


        // Mengatur pengurutan berdasarkan nama jika parameter urutan nama tersedia
        if ($order_nama) {
            $kegiatanQuery->orderBy('nama', $order_nama);
        }

        // Mengatur pengurutan berdasarkan progres jika parameter urutan progres tersedia
        if ($order_progres) {
            $kegiatanQuery->orderBy('progres', $order_progres);
        }

        // Mendapatkan data kegiatan
        $kegiatans = $kegiatanQuery->get();

        foreach ($kegiatans as $kegiatan) {
            $amount_file += $kegiatan->amount_file;
            $complete_file += $kegiatan->complete_file;
        }

        $progres = $amount_file > 0 ? number_format(($complete_file / $amount_file) * 100, 2) : 0;

        $nilai_trans = [];
        $nilai_trans_all = 0;

        foreach ($kegiatans as $kegiatan) {
            $transaksi_nilai = $this->monitoring_nilai_transaksi($kegiatan->id);
            // Jika monitoring_nilai_transaksi mengembalikan pesan error, lewati

            $nilai_trans[$kegiatan->id] = $transaksi_nilai;

            $transaksi_nilai = str_replace('.', '', $transaksi_nilai);
            $numericTransNilai = (float) $transaksi_nilai;

            $nilai_trans_all = $nilai_trans_all + $transaksi_nilai;

        }

        $nilai_trans_all = number_format($nilai_trans_all, 0, ',', '.');


        return view('page.administrasi.kegiatan.index', compact(
            'fungsi',
            'kegiatans',
            'years',
            'amount_file',
            'complete_file',
            'progres',
            'nilai_trans',
            'nilai_trans_all',
            'complete_verifikasis',
            'total_verifikasis',
            'all_complete_verifikasis',
            'all_total_verifikasis'
        ));
    }
    /**
     * Menyimpan resource baru ke dalam storage.
     */
    public function store(StoreKegiatanAdministrasiRequest $request)
    {
        try {
            $requestValidasi = $request->validate([
                'nama' => [
                    'required',
                    'max:550',
                    'regex:/^[^\/<>:;|?*\\\\"]+$/'
                ],
                'tahun' => 'required',
                'fungsi' => 'required',
            ]);
            $session = session('selected_year');

            $fungsi = $request->fungsi;
            $kegiatan = KegiatanAdministrasi::where('fungsi', $fungsi)->where('tahun', 
            $session)->get();
            $find = $kegiatan->where('nama', $request->nama)->first();

            if ($find) {
                return back()->with('error', 'Nama kegiatan ' . $request->nama . ' telah tersedia!');
            }

            KegiatanAdministrasi::create($requestValidasi);
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat input data: ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?fungsi=' . $fungsi)->with('success', 'Kegiatan ' . $request->nama . ' berhasil ditambahkan!');
    }


    /**
     * Menampilkan form untuk mengedit kegiatan yang ditentukan.
     */
    public function edit($id)
    {
        $kegiatan = KegiatanAdministrasi::find($id);
        return response()->json($kegiatan);
    }

    /**
     * Memperbarui kegiatan yang ditentukan dalam storage.
     */
    public function update(Request $request)
    {
        $requestValidasi = $request->validate([
            'nama' => [
                'required',
                'max:550',
                'regex:/^[^\/<>:;|?*\\\\"]+$/'
            ],        ]);

        $fungsi = $request->fungsi;
        $session = session('selected_year');

        // Memeriksa apakah nama kegiatan sudah ada
        $existingKegiatan = KegiatanAdministrasi::where('fungsi', $fungsi)
            ->where('nama', $requestValidasi['nama'])
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingKegiatan) {
            return back()->with('error', 'Nama kegiatan ' . $requestValidasi['nama'] . ' telah tersedia!');
        }

        $kegiatan = KegiatanAdministrasi::findOrFail($request->id);
        $oldFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/' . $request->oldNama;
        $newFolderPath = 'public/administrasis/' . $session . '/' . $fungsi . '/' . $request->nama;

        // Mengubah nama folder jika nama kegiatan berubah
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
                            'storage/administrasis/' . $session . '/' . $fungsi . '/' . $request->oldNama,
                            'storage/administrasis/' . $session . '/' . $fungsi . '/' . $request->oldNama
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
     * Menghapus resource yang ditentukan dari storage.
     */
    public function destroy(KegiatanAdministrasi $kegiatanAdministrasi, StoreKegiatanAdministrasiRequest $request)
    {
        $fungsi = $request->fungsi;
        $kegiatan = KegiatanAdministrasi::where('id', $request->kegiatan)->first();
        $session = session('selected_year');

        $filePath = "storage/administrasis/$session/$fungsi/{$kegiatan->nama}";
        File::deleteDirectory($filePath);

        // Menghapus data terkait dalam tabel akun dan transaksi
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

    /**
     * Menyimpan data dari file Excel ke dalam tabel kegiatan.
     */
    public function storeExcel(StoreKegiatanAdministrasiRequest $request)
    {
        try {
            $fungsi = $request->fungsi;
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('DatakegiatanAdministrasi', $fileName);

            // Mengimpor data dari file Excel
            Excel::import(new AdministrasiKegiatanImport($fungsi, $request->tahun), public_path('/DatakegiatanAdministrasi/' . $fileName));
        } catch (\Throwable $e) {
            // Tangkap pengecualian dan tampilkan pesan kesalahan
            return redirect()->back()->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }
        return redirect('/administrasi/kegiatan?fungsi=' . $fungsi)->with('success', 'Data Excel berhasil diimpor!');
    }

    /**
     * Mendapatkan nilai fungsi dari request.
     */
    public function getFungsi()
    {
        $fungsi = request('fungsi');
        return $fungsi;
    }

    public function monitoring_nilai_transaksi($id)
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


    public function total_verifikasi($fungsi)
    {
        $kegiatans = KegiatanAdministrasi::where('fungsi', $fungsi)->get();


        $complete_verifikasis = [];
        $total_verifikasis = [];
        $all_complete_verifikasis = 0;
        $all_total_verifikasis = 0;

        foreach ($kegiatans as $kegiatan) {
            $complete_verifikasi = 0;
            $total_verifikasi = 0;
            foreach ($kegiatan->akun as $akun) {
                foreach ($akun->transaksi as $transaksi) {
                    foreach ($transaksi->file as $file) {
                        if ($file->ceklist === 1) {
                            $complete_verifikasi += 1;
                        }
                        $total_verifikasi += 1;
                    }

                }
            }

            $complete_verifikasis[$kegiatan->id] = $complete_verifikasi;
            $total_verifikasis[$kegiatan->id] = $total_verifikasi;
            $all_complete_verifikasis += $complete_verifikasi;
            $all_total_verifikasis += $total_verifikasi;


        }
        return [
            'complete_verifikasis' => $complete_verifikasis,
            'total_verifikasis' => $total_verifikasis,
            'all_complete_verifikasis' => $all_complete_verifikasis,
            'all_total_verifikasis' => $all_total_verifikasis,
        ];



    }
}
