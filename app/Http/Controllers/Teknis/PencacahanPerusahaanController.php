<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PencacahanPerusahaan;
use Illuminate\Http\Request;
use App\Models\KegiatanTeknis;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PencacahanPerusahaanImport;
use App\Models\UserMitra;


class PencacahanPerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil nilai parameter kegiatan dari request
        $kegiatan = request('kegiatan');
        $tgl_awal = null;
        $tgl_akhir = null;
        $search = null;

        // Memeriksa apakah terdapat parameter pencarian dalam request
        if (request('search')) {
            $search = request('search');
        }

        // Query untuk mendapatkan data pencacahan perusahaan dengan filter pencarian dan kegiatan tertentu
        $pencacahans = PencacahanPerusahaan::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        // Mengambil informasi kegiatan teknis berdasarkan id kegiatan
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        // Mengambil data pencacahan perusahaan pertama kali untuk mendapatkan tanggal awal dan akhir
        $find = PencacahanPerusahaan::where('kegiatan_id', $kegiatan)->first();
        if ($find) {
            $tgl_akhir = $find->tgl_akhir;
            $tgl_awal = $find->tgl_awal;
        }

        // Menampilkan view dengan data yang sudah dipersiapkan
        return view('page.teknis.perusahaan.pencacahan.index', [
            'search' => $search,
            'pencacahans' => $pencacahans,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'kegiatan' => $kegiatan
        ]);
    }

    /**
     * Tujuan function ini untuk mengambil tgl awal dan akhir dari pencacahan yang sudah dibuat terdahulu
     */
    public function create($kegiatan_id)
    {
        // Mengambil data pencacahan perusahaan berdasarkan id kegiatan
        $pencacahan = PencacahanPerusahaan::where('kegiatan_id', $kegiatan_id)->first();

        // Jika data pencacahan perusahaan ditemukan, mengembalikan response JSON dengan data tersebut. Jika tidak, mengembalikan 0.
        if ($pencacahan) {
            return response()->json($pencacahan);
        } else {
            return response()->json(0);
        }
    }

    /**
     * Menyimpan data baru pencacahan perusahaan ke dalam tabel pencacahan perusahaan
     */
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

        // Cari UserMitra berdasarkan ppl_id
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();

        // Jika tidak difind, buat entri UserMitra baru
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        // Buat entri PencacahanPerusahaan baru dengan data yang sudah divalidasi
        PencacahanPerusahaan::create($requestValidasi);

        // Kembali dengan pesan sukses
        return back()->with('success', 'Berhasil diupload');
    }

    //menampilkan data yang akan di edit
    public function edit($id)
    {
        $pencacahan = PencacahanPerusahaan::find($id);
        return response()->json($pencacahan);
    }


    // function untuk melakukan update data 
    public function update(Request $request, PencacahanPerusahaan $PencacahanPerusahaan)
    {
        // Validasi input request
        $validateRequest = $request->validate([
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
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'perusahaan' => 'required'
        ]);

        // Memperbarui data PencacahanPerusahaan berdasarkan id yang diberikan dalam request
        PencacahanPerusahaan::where('id', $request->id)->update($validateRequest);

        //Perbarui atau buat UserMitra
        UserMitra::updateOrCreate(
            ['ppl_id' => $request->id_ppl],
            ['name' => $request->ppl]
        );

        // Kembali dengan pesan sukses
        return back()->with('success', 'Pencacahan berhasil diperbarui!');
    }

    // function untuk menghapus data
    public function destroy(Request $request)
    {
        // Mengambil data pencacahan perusahaan berdasarkan ID yang diberikan dalam request
        $pencacahan = PencacahanPerusahaan::find($request->id);

        // Menghapus data pencacahan perusahaan dari penyimpanan
        $success = PencacahanPerusahaan::destroy($pencacahan->id);

        // Memberikan respons berdasarkan hasil penghapusan data
        if ($success) {
            return back()->with('success', 'Data dengan ID-' . $request->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan saat menghapus, silakan coba lagi.');
        }
    }
    /**
     * Mengimpor data pencacahan perusahaan dari file Excel ke dalam penyimpanan.
     */
    public function store_excel(Request $request)
    {
        try {
            // Memindahkan file yang diunggah ke penyimpanan
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('ExcelTeknis', $fileName);

            // Mendapatkan periode dan mengurai tanggal
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);

            // Mengimpor file Excel menggunakan class PencacahanPerusahaanImport
            Excel::import(new PencacahanPerusahaanImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            // Menghapus file yang sudah diimpor
            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Kembali dengan pesan sukses jika berhasil mengimpor data
            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            // Mengembalikan ke halaman sebelumnya dengan pesan error dan data input yang sudah dimasukkan
            return redirect()->back()->withInput()->with('error', 'File yang diinput harus sesuai dengan file Excel.');
        }
    }

    // function untuk melaukan ceklist
    public function ceklist(Request $request, $id)
    {
        try {
            $isChecked = $request->isChecked;

            // Cari entri PemutakhiranPerusahaan berdasarkan ID
            $file = PencacahanPerusahaan::findOrFail($id);

            // Ubah nilai ceklist sesuai dengan isChecked yang dikirimkan
            $file->status = $isChecked ? 1 : 0; // Misalkan kolom status digunakan untuk menyimpan ceklist

            // Simpan perubahan
            $file->save();

            return response()->json(['message' => 'Status ceklist berhasil diperbarui', 'isChecked' => $isChecked]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Tangani kesalahan jika terjadi
        }
    }
}
