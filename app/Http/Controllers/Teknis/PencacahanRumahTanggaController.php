<?php


namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PencacahanRumahTangga;
use Illuminate\Http\Request;
use App\Models\KegiatanTeknis;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PencacahanRumahTanggaImport;
use App\Models\UserMitra;

class PencacahanRumahTanggaController extends Controller
{
    /**
     * Menampilkan daftar data pencacahan rumah tangga, serta halaman pencacahan rumah tangga.
     */
    public function index()
    {
        $tgl_awal = null;
        $tgl_akhir = null;
        $kegiatan = request('kegiatan');

        // Ambil data pencacahan rumah tangga sesuai dengan kegiatan yang dipilih
        $pencacahans = PencacahanRumahTangga::filter(request(['search']))
            ->where('kegiatan_id', $kegiatan)
            ->get();

        // Ambil informasi kegiatan teknis berdasarkan ID kegiatan yang dipilih
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        // Periksa apakah data pencacahan untuk kegiatan tersebut sudah ada
        $pencacahan = PencacahanRumahTangga::where('kegiatan_id', $kegiatan)->first();
        if ($pencacahan) {
            $tgl_akhir = $pencacahan->tgl_akhir;
            $tgl_awal = $pencacahan->tgl_awal;
        }

        // Tampilkan halaman index dengan data yang telah disiapkan
        return view('page.teknis.rumah-tangga.pencacahan.index', [
            'pencacahans' => $pencacahans,
            'kegiatan' => $kegiatan,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }


    // Tujuan function ini untuk mengambil tgl awal dan akhir dari pencacahan yang sudah dibuat terdahulu
    public function create($kegiatan_id)
    {
        $pencacahan = PencacahanRumahTangga::where('kegiatan_id', $kegiatan_id)->first();

        if ($pencacahan) {
            return response()->json($pencacahan);
        } else {
            return response()->json(0);
        }
    }

    /**
     * Menyimpan data pencacahan rumah tangga baru ke dalam database.
     */
    public function store(Request $request)
    {
        // Validasi input request
        $requestValidasi = $request->validate([
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nks' => 'required|max:100',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'kegiatan_id' => 'required'
        ]);

        // Cari atau buat entri UserMitra baru berdasarkan ppl_id
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        // Simpan data pencacahan rumah tangga baru
        PencacahanRumahTangga::create($requestValidasi);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Data berhasil ditambahkan');
    }


    // menampilkan data yang akan diedit
    public function edit($id)
    {
        $pencacahan = PencacahanRumahTangga::find($id);

        return response()->json($pencacahan);
    }

    // function untuk melakukan update pencacah rumah tangga
    public function update(Request $req)
    {
        // Kombinasikan semua aturan validasi
        $rules = [
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nks' => 'required|max:100',
        ];

        // Tambahkan validasi untuk sampel_1 sampai sampel_10
        for ($i = 1; $i <= 10; $i++) {
            $rules['sampel_' . $i] = 'max:100|string|nullable|regex:/^[a-zA-Z\s]*$/';
        };

        // Validasi request
        $requestValidasi = $req->validate($rules);

        // Tentukan status berdasarkan nilai sampel
        $status = true;
        for ($i = 1; $i <= 10; $i++) {
            $sampelValue = $req->input('sampel_' . $i);
            if (empty($sampelValue)) {
                $status = false;
                break;
            }
        }

        // Tambahkan status ke data yang divalidasi
        $requestValidasi['status'] = $status;

        // Perbarui atau buat UserMitra
        UserMitra::updateOrCreate(
            ['ppl_id' => $req->id_ppl],
            ['name' => $req->ppl]
        );

        // Perbarui PencacahanRumahTangga
        PencacahanRumahTangga::where('id', $req->id)->update($requestValidasi);

        // Ambil kembali data yang sudah diperbarui untuk perhitungan progress
        $pencacahanSampel = PencacahanRumahTangga::find($req->id);

        // Hitung progress
        $progres = 0;
        for ($i = 1; $i <= 10; $i++) {
            $sampelField = 'sampel_' . $i;
            if (!empty($pencacahanSampel->$sampelField)) {
                $progres += 10;
            }
        }

        // Perbarui nilai progress
        $pencacahanSampel->progres = $progres;
        $pencacahanSampel->save();

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Pencacahan berhasil diupdate!');
    }

    /**
     * Function untuk menghapus data pencacahan rumah tangga
     */
    public function destroy(Request $request)
    {
        $pencacahan = PencacahanRumahTangga::find($request->id);
        $sucsses = $pencacahan->delete();

        if ($sucsses) {
            return back()->with('success', 'Id-' . $pencacahan->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
        }
    }

    public function store_excel(Request $request)
    {
        $file = $request->file('excel_file');
        $nameFile = $file->getClientOriginalName();
        $file->move('ExcelTeknis', $nameFile);
        Excel::import(new PencacahanRumahTanggaImport($request->kegiatan_id, $request->tgl_awal, $request->tgl_akhir), public_path('/ExcelTeknis/' . $nameFile));
        // hapus file excel ketika sudah diimport
        $filePath = public_path('/ExcelTeknis/' . $nameFile);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return back()->with('success', 'Data berhasil diimport');
    }
}
