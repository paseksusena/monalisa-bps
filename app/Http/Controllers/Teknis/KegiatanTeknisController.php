<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\KegiatanTeknis;
use App\Models\PemutakhiranPerusahaan;
use App\Models\PemutakhiranPetani;
use App\Models\PemutakhiranRumahTangga;
use App\Models\PencacahanPerusahaan;
use App\Models\PencacahanPetani;
use App\Models\PencacahanRumahTangga;
use Illuminate\Http\Request;
use Carbon\Carbon;


class KegiatanTeknisController extends Controller
{

    // menampilkan halaman kegiatan teknis
    public function index()
    {
        // Mendapatkan tahun saat ini
        $currentYear = Carbon::now()->year;

        // Membuat rentang tahun dari lima tahun terakhir hingga tahun sekarang
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);

        // Jika tidak ada tahun yang dipilih dalam sesi, gunakan tahun sekarang
        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        // Mendapatkan tahun yang dipilih dari sesi
        $session = session('selected_year');

        // Mendapatkan fungsi dari permintaan
        $fungsi = request('fungsi');
        $allowedFungsi = ['Produksi', 'Sosial', 'Distribusi', 'IPDS', 'Umum', 'Neraca'];

        if (!in_array($fungsi, $allowedFungsi)) {
            return redirect('/');
        }

        // Mengambil kegiatan teknis berdasarkan fungsi dan tahun yang dipilih
        $kegiatans = KegiatanTeknis::where('fungsi', $fungsi)
            ->where('tahun', $session)
            ->get();


        // melakukan perulangan kegiatan untuk menghitung progres rumah tangga, perusahaan, dan petani
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->kategori == 'rumah_tangga') {
                // memanggil function progresRumahTangga
                $this->progresRumahTangga($kegiatan->id);
            }
            if ($kegiatan->kategori == 'perusahaan') {
                // memanggil function progresPerusahaan
                $this->progresPerusahaan($kegiatan->id);
            }
            if ($kegiatan->kategori == 'petani') {
                // memanggil function progresPetani 
                $this->progresPetani($kegiatan->id);
            }
        }
        // Mengembalikan tampilan dengan data yang telah diambil
        return view('page.teknis.index', [
            'kegiatans' => $kegiatans,
            'fungsi' => $fungsi,
            'years' => $years,
        ]);
    }


    // function untuk menyimpan kegiatan teknis
    public function store(Request $request)
    {
        // Validasi permintaan
        $validasiRequest = $request->validate([
            'nama' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'fungsi' => 'required',
            'kategori' => 'required',
            'tahun' => 'required'
        ]);

        // Menambahkan nilai 'link' jika ada
        $validasiRequest['link'] = $request->link;

        // Memeriksa apakah nama kegiatan sudah ada
        $find = KegiatanTeknis::where('nama', $request->nama)->first();
        if ($find) {
            return back()->with('error', 'Nama kegiatan ' . $request->nama . ' sudah tersedia!');
        }

        // Membuat kegiatan teknis baru
        KegiatanTeknis::create($validasiRequest);

        // Mengembalikan respon sukses
        return back()->with('success', 'Kegiatan ' . $request->nama . ' berhasil di upload');
    }

    // function untum menampilkan data kegiatan yang akan diedit 
    public function edit($id)
    {
        // Mengambil data kegiatan teknis berdasarkan ID
        $kegiatan = KegiatanTeknis::find($id);

        // Mengembalikan data kegiatan dalam bentuk JSON
        return response()->json($kegiatan);
    }
    /**
     * Function untuk melakukan pembaruan kegiatan yang ditentukan di penyimpanan.
     */
    public function update(Request $request)
    {
        // Validasi data yang diterima dari permintaan
        $validasiRequest = $request->validate([
            'nama' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'fungsi' => 'required',
            'kategori' => 'required',
        ]);

        // Menambahkan link ke array data yang divalidasi
        $validasiRequest['link'] = $request->link;

        // Periksa apakah ada record dengan 'nama' dan 'fungsi' yang sama, tapi bukan record yang sedang diperbarui
        $existingRecord = KegiatanTeknis::where('nama', $request->nama)->where('fungsi', $request->fungsi)
            ->where('id', '!=', $request->id)
            ->first();

        // Jika ada record yang sama, kembalikan pesan error
        if ($existingRecord) {
            return back()->with('error', 'Nama kegiatan ' . $request->nama . ' sudah tersedia!');
        }

        // Perbarui record dengan data yang telah divalidasi
        KegiatanTeknis::where('id', $request->id)->update($validasiRequest);

        // Kembalikan pesan sukses
        return back()->with('success', 'Kegiatan ' . $request->nama . ' berhasil diubah');
    }


    // function untuk menghapus kegiatan
    public function destroy($id, Request $request)
    {
        // Cari kegiatan berdasarkan ID
        $kegiatan = KegiatanTeknis::where('id', $request->kegiatan)->first();

        // Jika kegiatan ditemukan, lanjutkan proses penghapusan
        if ($kegiatan) {
            //jika ketegori adalah petani, maka hapus pemutakhiran, pencacahan petani, dan ruta petani
            if ($kegiatan->kategori == 'petani') {
                // Menghapus pemutakhiran petani dan data terkait
                $kegiatan->pemutakhiranPetani()->each(function ($pemutakhiran) {
                    $pemutakhiran->rutaPetani()->each(function ($ruta) {
                        $ruta->delete();
                    });
                    $pemutakhiran->delete();
                });
                // Menghapus pencacah petani
                $kegiatan->pencacahanPetani()->each(function ($pencacahan) {
                    $pencacahan->delete();
                });
                $kegiatan->delete();
            }

            //jika ketegori adalah rumah tangga, maka hapus pemutakhiran, pencacahan rumah tangga, dan ruta rumah tangga

            if ($kegiatan->kategori == 'rumah_tangga') {
                // Menghapus pemutakhiran RumahTangga dan data terkait
                $kegiatan->pemutakhiranRumahTangga()->each(function ($pemutakhiran) {
                    $pemutakhiran->rutaRumahTangga()->each(function ($ruta) {
                        $ruta->delete();
                    });
                    $pemutakhiran->delete();
                });
                // Menghapus pencacah RumahTangga
                $kegiatan->pencacahanRumahTangga()->each(function ($pencacahan) {
                    $pencacahan->delete();
                });
                $kegiatan->delete();
            }

            //jika ketegori adalah perusahaan, maka hapus pemutakhiran dan pencacahan perusahaan
            if ($kegiatan->kategori == 'perusahaan') {
                // Menghapus pemutakhiran Perusahaan
                $kegiatan->pemutakhiranPerusahaan()->each(function ($pemutakhiran) {
                    $pemutakhiran->delete();
                });
                // Menghapus pencacah Perusahaan
                $kegiatan->pencacahanPerusahaan()->each(function ($pencacahan) {
                    $pencacahan->delete();
                });
                $kegiatan->delete();
            }
            //jika ketegori adalah direct link, maka hapus kegiatan direck link
            if ($kegiatan->kategori == 'direct_link') {
                $kegiatan->delete();
            }

            // Kembalikan pesan sukses
            return back()->with('success', 'Kegiatan ' . $kegiatan->nama . ' berhasil dihapus!');
        }
    }

    // function untuk mendownload templete xlsx 
    public function downloadTemplate()
    {
        // Ambil nama file template dari permintaan
        $nameFile = request('template');

        // Tentukan path file berdasarkan nama template
        $path = public_path('storage/template/' . $nameFile . '.xlsx');

        // Kembalikan respon untuk mengunduh file
        return response()->download($path);
    }


    // function untuk search kegiatan
    public function search(Request $request)
    {
        // Inisialisasi array untuk menyimpan hasil pencarian
        $searchResults = [];

        // Ambil data pencarian dari permintaan
        $searchTerm = $request->input('search');
        $session = session('selected_year');

        // Lakukan pencarian di berbagai model dan simpan hasilnya ke dalam array
        $kegiatans = KegiatanTeknis::where('nama', 'like', '%' . $searchTerm . '%')->where('tahun', $session)
            ->get();;


        // Loop melalui setiap hasil pencarian dan tambahkan ke array hasil pencarian. Tujuan untuk mendapatkan nama alamat, dan url dari kegiatan
        foreach ($kegiatans as $kegiatan) {
            if ($kegiatan->tahun == $session) {
                $searchResults[] = [
                    'name' => $kegiatan->nama,
                    'url' => "/teknis/kegiatan/rumah-tangga/pemutakhiran?kegiatan=" . $kegiatan->id,
                    'alamat' => $kegiatan->fungsi . '/' . $kegiatan->nama,
                ];
            }
        }


        // Kirim hasil pencarian sebagai respons JSON
        return response()->json($searchResults);
    }



    public function progresRumahTangga($kegiatan_id)
    {
        // Menghitung progres all pemutakhiran
        $pemutakhirans = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan_id)->get();

        $beban_kerja_pemutakhiran_all = 0;
        $ruta_progres_pemutakhiran = 0;

        foreach ($pemutakhirans as $pemutakhiran) {
            $beban_kerja_pemutakhiran_all += $pemutakhiran->beban_kerja;

            foreach ($pemutakhiran->rutaRumahTangga as $ruta) {
                $ruta_progres_pemutakhiran += $ruta->progres;
            }
        }

        // Menghitung progres all pencacahan
        $pencacahans = PencacahanRumahTangga::where('kegiatan_id', $kegiatan_id)->get();

        $beban_kerja_pencacahan_all = $pencacahans->count() * 10;
        $progres_pencacahan = 0;

        foreach ($pencacahans as $pencacahan) {
            $progres_pencacahan = ($pencacahan->progres / 10) + $progres_pencacahan;
        }

        $total_beban_kerja = $beban_kerja_pemutakhiran_all + $beban_kerja_pencacahan_all;

        // Avoid division by zero
        if ($total_beban_kerja > 0) {
            $progres_kegiatan = (($progres_pencacahan + $ruta_progres_pemutakhiran) / $total_beban_kerja) * 100;
            $progres_kegiatan = floatval(number_format($progres_kegiatan, 1));
        } else {
            $progres_kegiatan = 0;
        }

        // Save the result
        $kegiatan = KegiatanTeknis::find($kegiatan_id);
        $kegiatan['progres'] = $progres_kegiatan;
        $kegiatan->save();

        return 0;
    }

    public function progresPetani($kegiatan_id)
    {
        // Menghitung progres all pemutakhiran
        $pemutakhirans = PemutakhiranPetani::where('kegiatan_id', $kegiatan_id)->get();

        $beban_kerja_pemutakhiran_all = 0;
        $ruta_progres_pemutakhiran = 0;

        foreach ($pemutakhirans as $pemutakhiran) {
            $beban_kerja_pemutakhiran_all += $pemutakhiran->beban_kerja;

            foreach ($pemutakhiran->rutapetani as $ruta) {
                $ruta_progres_pemutakhiran += $ruta->progres;
            }
        }

        // Avoid division by zero
        if ($beban_kerja_pemutakhiran_all > 0) {
            $progres_pemutakhiran = ($ruta_progres_pemutakhiran / $beban_kerja_pemutakhiran_all) * 100;
            $progres_pemutakhiran = floatval(number_format($progres_pemutakhiran, 1));
        } else {
            $progres_pemutakhiran = 0.0;
        }

        // Menghitung progres all pencacahan
        $pencacahans = PencacahanPetani::where('kegiatan_id', $kegiatan_id)->get();

        $beban_kerja_pencacahan_all = $pencacahans->count();
        $progres_pencacahan = 0;

        foreach ($pencacahans as $pencacahan) {
            if ($pencacahan->status == 1) {
                $progres_pencacahan++;
            }
        }

        // Avoid division by zero
        if ($beban_kerja_pencacahan_all > 0) {
            $progres_pencacahan = ($progres_pencacahan / $beban_kerja_pencacahan_all) * 100;
            $progres_pencacahan = floatval(number_format($progres_pencacahan, 1));
        } else {
            $progres_pencacahan = 0.0;
        }

        // Menghitung rata-rata progres kegiatan
        $total_beban_kerja = $beban_kerja_pemutakhiran_all + $beban_kerja_pencacahan_all;

        if ($total_beban_kerja > 0) {
            $progres_kegiatan = (($progres_pencacahan + $progres_pemutakhiran) / 2);
            $progres_kegiatan = floatval(number_format($progres_kegiatan, 1));
        } else {
            $progres_kegiatan = 0.0;
        }

        // Save the result
        $kegiatan = KegiatanTeknis::find($kegiatan_id);
        $kegiatan['progres'] = $progres_kegiatan;
        $kegiatan->save();

        return 0;
    }



    public function progresPerusahaan($kegiatan_id)
    {

        // Menghitung progres all pemutakhiran
        $pemutakhirans = PemutakhiranPerusahaan::where('kegiatan_id', $kegiatan_id)->get();

        $total_pemutakhiran = $pemutakhirans->count();
        $status_selesai_pemutakhiran = 0;

        foreach ($pemutakhirans as $pemutakhiran) {

            if ($pemutakhiran->status == 1) {
                $status_selesai_pemutakhiran = $status_selesai_pemutakhiran + 1;
            }
        }




        // Menghitung progres all pencacahan
        $pencacahans = PencacahanPerusahaan::where('kegiatan_id', $kegiatan_id)->get();

        $total_pencacahan = $pencacahans->count();
        $status_selesai_pencacahan = 0;

        foreach ($pencacahans as $pencacahan) {

            if ($pencacahan->status == 1) {
                $status_selesai_pencacahan = $status_selesai_pencacahan + 1;
            }
        }

        $total_beban_kerja =  $total_pencacahan + $total_pemutakhiran;
        // Avoid division by zero
        if ($total_beban_kerja > 0) {
            $progres_kegiatan = (($status_selesai_pencacahan + $status_selesai_pemutakhiran) / $total_beban_kerja) * 100;
            $progres_kegiatan = floatval(number_format($progres_kegiatan, 1));
        } else {
            $progres_kegiatan = 0.0;
        }

        // Save the result
        $kegiatan = KegiatanTeknis::find($kegiatan_id);
        $kegiatan['progres'] = $progres_kegiatan;
        $kegiatan->save();



        return 0;
    }

    public function setYearSession(Request $request)
    {
        $year = $request->input('year');
        $request->session()->put('selected_year', $year);
        return response()->json(['success' => true]);
    }

    public function ceklist(Request $request, $id)
    {
        try {
            $isChecked = $request->isChecked;

            // Cari entri PemutakhiranPerusahaan berdasarkan ID
            $kegiatan = KegiatanTeknis::findOrFail($id);

            // Ubah nilai ceklist sesuai dengan isChecked yang dikirimkan
            $kegiatan->status = $isChecked ? 1 : 0; // Misalkan kolom 'status' digunakan untuk menyimpan ceklist

            // Simpan perubahan
            $kegiatan->save();

            return response()->json(['message' => 'Status ceklist berhasil diperbarui', 'isChecked' => $isChecked]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Tangani kesalahan jika terjadi
        }
    }
}
