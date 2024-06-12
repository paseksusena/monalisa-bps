<?php


namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PencacahanRumahTangga;
use App\Http\Requests\StorePencacahanRumahTanggaRequest;
use App\Http\Requests\UpdatePencacahanRumahTanggaRequest;
use Illuminate\Http\Request;
use App\Models\KegiatanTeknis;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PencacahanRumahTanggaImport;
use App\Models\UserMitra;

use function Laravel\Prompts\error;

class PencacahanRumahTanggaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tgl_awal = null;
        $tgl_akhir = null;
        $kegiatan = request('kegiatan');

        $pencacahans = PencacahanRumahTangga::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        $pencacahan = PencacahanRumahTangga::where('kegiatan_id', $kegiatan)->first();
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();
        if ($pencacahan) {
            $tgl_akhir = $pencacahan->tgl_akhir;
            $tgl_awal = $pencacahan->tgl_awal;
        }


        return view('page.teknis.rumah-tangga.pencacahan.index', [
            'pencacahans' => $pencacahans,
            'kegiatan' => $kegiatan,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

        //     "_token" => "7Tita4tDLHRneIioQqgtjrtTSw3CSsDUysbMi5jn"
        //   "kegiatan_id" => "6"
        //   "tgl_awal" => "2024-06-12"
        //   "tgl_akhir" => "2024-06-13"
        //   "nks" => "ss"
        //   "id_pml" => "s"
        //   "pml" => "s"
        //   "id_ppl" => "s"
        //   "kode_kec" => "s"
        //   "kecamatan" => "s"
        //   "kode_desa" => "s"
        //   "desa" => "s"
        // Cari UserMitra berdasarkan ppl_id
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();

        // Jika tidak difind, buat entri UserMitra baru
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        PencacahanRumahTangga::create($requestValidasi);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PencacahanRumahTangga $pencacahanRumahTangga)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pencacahan = PencacahanRumahTangga::find($id);

        return response()->json($pencacahan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        // Combine all validation rules
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

        for ($i = 1; $i <= 10; $i++) {
            $rules['sampel_' . $i] = 'max:100|string|nullable|alpha';
        }

        // Validate the request
        $requestValidasi = $req->validate($rules);

        // Determine the status based on sampel fields
        $status = true;
        for ($i = 1; $i <= 10; $i++) {
            $sampelValue = $req->input('sampel_' . $i);
            if (empty($sampelValue)) {
                $status = false;
                break;
            }
        }

        // Add status to the validated data
        $requestValidasi['status'] = $status;

        // Update or create UserMitra
        UserMitra::updateOrCreate(
            ['ppl_id' => $req->id_ppl],
            ['name' => $req->ppl]
        );

        // Update PencacahanRumahTangga
        PencacahanRumahTangga::where('id', $req->id)->update($requestValidasi);

        // Retrieve the updated record for progress calculation
        $pencacahanSampel = PencacahanRumahTangga::find($req->id);

        // Calculate the progress
        $progres = 0;
        for ($i = 1; $i <= 10; $i++) {
            $sampelField = 'sampel_' . $i;
            if (!empty($pencacahanSampel->$sampelField)) {
                $progres += 10;
            }
        }

        // Update progress
        $pencacahanSampel->progres = $progres;
        $pencacahanSampel->save();

        // Redirect back with success message
        return back()->with('success', 'Berhasil di ubah!');
    }


    /**
     * Remove the specified resource from storage.
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
