<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PemutakhiranPerusahaan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PemutakhiranPerusahaanImport;
use App\Models\KegiatanTeknis;
use App\Models\UserMitra;


class PemutakhiranPerusahaanController extends Controller
{

    // function untuk menampilkan halaman  pemutakhiran perusahaan
    public function index()
    {
        // Ambil kegiatan dari permintaan
        $kegiatan = request('kegiatan');
        $tgl_awal = null;
        $tgl_akhir = null;
        $search = null;

        // Periksa apakah ada parameter pencarian dalam permintaan
        if (request('search')) {
            $search = request('search');
        }

        // Filter dan ambil data pemutakhiran perusahaan berdasarkan kegiatan dan pencarian
        $pemutakhirans = PemutakhiranPerusahaan::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        // Ambil data kegiatan berdasarkan ID kegiatan
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        // Temukan pemutakhiran perusahaan pertama untuk mendapatkan tanggal awal dan akhir
        $find = PemutakhiranPerusahaan::where('kegiatan_id', $kegiatan->id)->first();
        if ($find) {
            $tgl_akhir = $find->tgl_akhir;
            $tgl_awal = $find->tgl_awal;
        }

        // Kembalikan tampilan dengan data yang ditemukan dengan alamat di bawah ini
        return view('page.teknis.perusahaan.pemutakhiran.index', [
            'search' => $search,
            'pemutakhirans' => $pemutakhirans,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'kegiatan' => $kegiatan
        ]);
    }

    // function untuk menampilkan form create. Tujuannya untuk mengetahui apakah sudah ada pemutakhiran dibuat atau belum sebelumnya. Jika ada ambil tanggal awal dan akhirnya pemutakhiran sebelumnya
    public function create($kegiatan_id)
    {
        // Cari data pemutakhiran perusahaan berdasarkan ID kegiatan
        $pemutakhiran = PemutakhiranPerusahaan::where('kegiatan_id', $kegiatan_id)->first();

        // Jika data pemutakhiran ditemukan, kembalikan respon JSON dengan data tersebut
        if ($pemutakhiran) {
            return response()->json($pemutakhiran);
        } else {
            // Jika tidak ada data pemutakhiran ditemukan, kembalikan respon JSON dengan nilai 0
            return response()->json(0);
        }
    }

    // Function untuk melakukan penyimpanan pemutakhiran perusahaan ke tabel pemutakhiran perusahaan
    public function store(Request $request)
    {
        // Validasi input request
        $requestValidasi = $request->validate([
            'kegiatan_id' => 'required',
            'id_pml' => 'required',
            'pml' => 'required|max:100',
            'id_ppl' => 'required',
            'ppl' => 'required|max:100',
            'kode_sbr' => 'required|max:10',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20',
            'desa' => 'required|max:20',
            'kode_desa' => 'required|max:4',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
            'perusahaan' => 'required'
        ]);


        // DISINI MERUPAKAN PROSES PEMBUATAN AKUN MITRA
        // Cari UserMitra berdasarkan id_ppl
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();

        // Jika tidak ditemukan, buat entri UserMitra baru
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        // Buat entri PemutakhiranPerusahaan baru dengan data yang sudah divalidasi
        PemutakhiranPerusahaan::create($requestValidasi);

        // Kembali dengan pesan sukses
        return back()->with('success', 'Berhasil diupload');
    }

    // function untuk menampilkan data yang akan diedit
    public function edit($id)
    {
        // Cari data PemutakhiranPerusahaan berdasarkan ID
        $pemutakhiran = PemutakhiranPerusahaan::find($id);

        // Kembalikan data sebagai respons JSON
        return response()->json($pemutakhiran);
    }

    /**
     * Melaukan update putakhirkan di penyimpanan.
     */
    public function update(Request $request, PemutakhiranPerusahaan $pemutakhiranPerusahaan)
    {
        // Validasi data masukan dari request
        $validatedData = $request->validate([
            'kegiatan_id' => 'required',
            'id_pml' => 'required',
            'pml' => 'required|max:100',
            'id_ppl' => 'required',
            'ppl' => 'required|max:100',
            'kode_sbr' => 'required|max:10',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20',
            'desa' => 'required|max:20',
            'kode_desa' => 'required|max:4',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
            'perusahaan' => 'required'
        ]);

        // Update catatan PemutakhiranPerusahaan berdasarkan ID-nya
        PemutakhiranPerusahaan::where('id', $request->id)->update($validatedData);

        // Update atau buat entri baru untuk record UserMitra berdasarkan ppl_id
        UserMitra::updateOrCreate(
            ['ppl_id' => $validatedData['id_ppl']],
            ['name' => $validatedData['ppl']]
        );

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Pemutakhiran berhasil diupdate!');
    }

    /**
     * Hapus sumber daya yang diputakhirkan dari penyimpanan.
     */
    public function destroy(Request $request)
    {
        // Cari PemutakhiranPerusahaan berdasarkan ID yang diberikan
        $pemutakhiran = PemutakhiranPerusahaan::find($request->id);

        // Jika pemutakhiran ditemukan, hapus berdasarkan ID-nya
        if ($pemutakhiran) {
            $success = $pemutakhiran->delete();

            // Jika penghapusan berhasil
            if ($success) {
                return back()->with('success', 'ID-' . $request->id . ' berhasil dihapus!');
            } else {
                return back()->with('error', 'Terjadi kesalahan saat menghapus. Silakan coba lagi.');
            }
        } else {
            return back()->with('error', 'Pemutakhiran dengan ID-' . $request->id . ' tidak ditemukan.');
        }
    }


    /**
     * Menyimpan data dari file Excel yang diunggah ke dalam database.
     */
    public function store_excel(Request $request)
    {
        try {
            // Validasi input request
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls', // Validasi jenis file Excel yang diizinkan
                'kegiatan_id' => 'required',
                'tgl_awal' => 'required|date',
                'tgl_akhir' => 'required|date',
            ]);

            // Pindahkan file yang diunggah ke direktori 'ExcelTeknis'
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('ExcelTeknis', $fileName);

            // Ambil periode dan parse tanggal
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);

            // Impor data dari Excel menggunakan kelas import yang telah dibuat
            Excel::import(new PemutakhiranPerusahaanImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('ExcelTeknis/' . $fileName));

            // Hapus file setelah selesai diimpor
            $filePath = public_path('ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Redirect kembali dengan pesan sukses
            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            // Tangkap pengecualian jika terjadi kesalahan saat impor file
            return redirect()->back()->withInput()->with('error', 'File yang diupload harus sesuai dengan format Excel yang valid.');
        }
    }

    /**
     * Memperbarui status ceklist pada entri PemutakhiranPerusahaan.
     */
    public function ceklist(Request $request, $id)
    {
        try {
            $isChecked = $request->isChecked;

            // Cari entri PemutakhiranPerusahaan berdasarkan ID
            $file = PemutakhiranPerusahaan::findOrFail($id);

            // Ubah nilai ceklist sesuai dengan isChecked yang dikirimkan
            $file->status = $isChecked ? 1 : 0; // Misalkan kolom 'status' digunakan untuk menyimpan ceklist

            // Simpan perubahan
            $file->save();

            return response()->json(['message' => 'Status ceklist berhasil diperbarui', 'isChecked' => $isChecked]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Tangani kesalahan jika terjadi
        }
    }
}
