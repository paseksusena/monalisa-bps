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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $kegiatan = request('kegiatan');
        $tgl_awal = null;
        $tgl_akhir = null;
        $search = null;

        if (request('search')) {
            $search = request('search');
        }

        $pemutakhirans = PemutakhiranPerusahaan::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();
        $find = PemutakhiranPerusahaan::where('kegiatan_id', $kegiatan)->first();
        if ($find) {
            $tgl_akhir = $find->tgl_akhir;
            $tgl_awal = $find->tgl_awal;
        }

        return view('page.teknis.perusahaan.pemutakhiran.index', [
            'search' => $search,
            'pemutakhirans' => $pemutakhirans,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'kegiatan' => $kegiatan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kegiatan_id)
    {
        $pemutakhiran = PemutakhiranPerusahaan::where('kegiatan_id', $kegiatan_id)->first();

        if ($pemutakhiran) {
            return response()->json($pemutakhiran);
        } else {
            return response()->json(0);
        }
    }


    /**
     * Store a newly created resource in storage.
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

        // Buat entri PemutakhiranPerusahaan baru dengan data yang sudah divalidasi
        PemutakhiranPerusahaan::create($requestValidasi);

        // Kembali dengan pesan sukses
        return back()->with('success', 'Berhasil diupload');
    }



    /**
     * Display the specified resource.
     */
    public function show($kegiatan_id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pemutakhiran = PemutakhiranPerusahaan::find($id);
        return response()->json($pemutakhiran);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemutakhiranPerusahaan $pemutakhiranPerusahaan)
    {
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

        PemutakhiranPerusahaan::where('id', $request->id)->update($validateRequest);

        UserMitra::where('ppl_id', $request->id_ppl)->update([
            'name' => $request->ppl,
            'ppl_id' => $request->id_ppl
        ]);


        return back()->with('success', 'Pemutakhiran berhasi di update!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pemutakhiran = PemutakhiranPerusahaan::find($request->id);
        $sucsses = PemutakhiranPerusahaan::destroy($pemutakhiran->id);
        if ($sucsses) {
            return back()->with('success', 'Id-' . $request->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
        }
    }

    public function store_excel(Request $request)
    {
        try {

            // Move uploaded file to storage
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('ExcelTeknis', $fileName);

            // Get periode and parse dates
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);


            Excel::import(new PemutakhiranPerusahaanImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'File yang dinput harus sesuai dengan file Excel.');
        }
    }

    public function ceklist(Request $request, $id)
    {
        try {
            $isChecked = $request->isChecked;

            // Cari entri PemutakhiranPerusahaan berdasarkan ID
            $file = PemutakhiranPerusahaan::findOrFail($id);

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
