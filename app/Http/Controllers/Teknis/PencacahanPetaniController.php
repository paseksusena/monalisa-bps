<?php


namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PencacahanPetani;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PencacahanPetaniImport;
use App\Models\UserMitra;
use App\Models\KegiatanTeknis;


class PencacahanPetaniController extends Controller
{
    /**
     * Menampilkan daftar atau halaman pencacahan petani berdasarkan kegiatan teknis. */
    public function index()
    {
        $tgl_awal = null;
        $tgl_akhir = null;
        $kegiatan = request('kegiatan');
        $search = null;

        // Mengatur pencarian jika ada
        if (request('search')) {
            $search = request('search');
        }

        // Mengambil data pencacahan petani berdasarkan filter pencarian dan kegiatan
        $pencacahans = PencacahanPetani::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        // Mengambil data pencacahan petani pertama berdasarkan kegiatan untuk mendapatkan tanggal awal dan akhir
        $pencacahan = PencacahanPetani::where('kegiatan_id', $kegiatan)->first();

        // Mendapatkan data kegiatan teknis berdasarkan id kegiatan
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();


        // Jika data pencacahan ditemukan, atur tanggal awal dan akhir
        if ($pencacahan) {
            $tgl_akhir = $pencacahan->tgl_akhir;
            $tgl_awal = $pencacahan->tgl_awal;
        }

        // Mengembalikan tampilan index dengan data pencarian, pencacahan, kegiatan, tanggal awal, dan tanggal akhir
        return view('page.teknis.petani.pencacahan.index', [
            'search' => $search,
            'pencacahans' => $pencacahans,
            'kegiatan' => $kegiatan,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }

    // Tujuan function ini untuk mengambil tgl awal dan akhir dari pencacahan yang sudah dibuat terdahulu
    public function create($kegiatan_id)
    {
        $pencacahan = PencacahanPetani::where('kegiatan_id', $kegiatan_id)->first();

        if ($pencacahan) {
            return response()->json($pencacahan);
        } else {
            return response()->json(0);
        }
    }

    /**
     * Function untuk menanambahkan data baru pencacahan 
     */
    public function store(Request $request)
    {

        $requestValidasi = $request->validate([
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            // 'kode_sbr' => 'required',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nks' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'kegiatan_id' => 'required'
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

        // melakukan penambahan data baru
        PencacahanPetani::create($requestValidasi);

        return back()->with('success', 'Data berhasil ditambahkan');
    }


    /**
     * Function untuk menampilakan data yang akan diedit
     */
    public function edit($id)
    {
        $pencacahan = PencacahanPetani::find($id);
        return response()->json($pencacahan);
    }

    /**
     * Function untuk melakukan update pencacahan petani
     */
    public function update(Request $request)
    {
        // Validasi input dari request
        $requestValidasi = $request->validate([
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nks' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'kegiatan_id' => 'required',
            'jenis_komoditas' => 'nullable|string',
            'nama_krt' => 'nullable|string',
        ]);

        // Update data PencacahanPetani berdasarkan ID
        PencacahanPetani::where('id', $request->id)->update($requestValidasi);

        //Perbarui atau buat UserMitra
        UserMitra::updateOrCreate(
            ['ppl_id' => $request->id_ppl],
            ['name' => $request->ppl]
        );

        // Ambil data PencacahanPetani setelah diupdate
        $pencacahan = PencacahanPetani::where('id', $request->id)->first();

        // Tentukan status berdasarkan pengisian jenis komoditas dan nama KRT
        if (!empty($pencacahan->jenis_komoditas) && !empty($pencacahan->nama_krt)) {
            $pencacahan['status'] = true;
        } else {
            $pencacahan['status'] = false;
        }

        // Simpan perubahan status
        $pencacahan->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pencacahan berhasil diupdate!');
    }

    /**
     * Menghapus data pencacahan petani dari penyimpanan.
     */
    public function destroy(Request $request)
    {
        // Temukan data pencacahan petani berdasarkan ID
        $pencacahan = PencacahanPetani::find($request->id);

        // Hapus data pencacahan petani berdasarkan ID
        $success = PencacahanPetani::destroy($pencacahan->id);

        // Memberikan respons sesuai hasil penghapusan data
        if ($success) {
            return back()->with('success', 'Id-' . $request->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
        }
    }
    /**
     * Menyimpan data pencacahan petani dari file Excel yang diunggah.
     */
    public function store_excel(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv',
                'kegiatan_id' => 'required|integer',
                'tgl_awal' => 'required|date',
                'tgl_akhir' => 'required|date',
            ]);

            // Pindahkan file yang diunggah ke penyimpanan
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('ExcelTeknis'), $fileName);

            $filePath = public_path('ExcelTeknis/' . $fileName);

            // Impor file Excel
            Excel::import(
                new PencacahanPetaniImport(
                    $request->kegiatan_id,
                    $request->tgl_awal,
                    $request->tgl_akhir
                ),
                $filePath
            );

            // Hapus file setelah diimpor
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
