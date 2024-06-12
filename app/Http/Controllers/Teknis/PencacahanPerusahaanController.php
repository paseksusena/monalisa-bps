<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;

use App\Models\PencacahanPerusahaan;
use App\Http\Requests\StorePencacahanPerusahaanRequest;
use App\Http\Requests\UpdatePencacahanPerusahaanRequest;
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
        $kegiatan = request('kegiatan');

        $pemutakhirans = PencacahanPerusahaan::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        return view('', [
            'pemutakhirans' => $pemutakhirans

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kegiatan_id)
    {
        $pencacahan = PencacahanPerusahaan::where('kegiatan_id', $kegiatan_id)->first();

        if ($pencacahan) {
            return response()->json($pencacahan);
        } else {
            return response()->json(null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $requestValidasi = $request->validate([
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_sbr' => 'required',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
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

        PencacahanPerusahaan::create($requestValidasi);

        return back()->with('success', 'Data berasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PencacahanPerusahaan $pencacahanPerusahaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pencacahan = PencacahanPerusahaan::find($id);
        return response()->json($pencacahan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $requestValidasi = $request->validate([
            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_sbr' => 'required',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'kegiatan_id' => 'required'
        ]);

        PencacahanPerusahaan::where('id', $request->id)->update($requestValidasi);

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
        $pencacahan = PencacahanPerusahaan::find($request->id)->first();
        $sucsses = PencacahanPerusahaan::destroy($pencacahan->id);
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


            Excel::import(new PencacahanPerusahaanImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

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
