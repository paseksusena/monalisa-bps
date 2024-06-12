<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PemutakhiranPerusahaan;
use App\Http\Requests\StorePemutakhiranPerusahaanRequest;
use App\Http\Requests\UpdatePemutakhiranPerusahaanRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PemutakhiranPerusahaanImport;
use App\Models\UserMitra;



class PemutakhiranPerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $kegiatan = request('kegiatan');

        $pemutakhirans = PemutakhiranPerusahaan::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        return view('', [
            'pemutakhirans' => $pemutakhirans

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
            return response()->json(null);
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
            'tgl_akhir' => 'required|date'
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
            'tgl_akhir' => 'required'
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
        $pemutakhiran = PemutakhiranPerusahaan::find($request->id)->first();
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
}
