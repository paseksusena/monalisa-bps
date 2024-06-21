<?php


namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\PencacahanPetani;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PencacahanPetaniImport;
use App\Models\UserMitra;
use App\Models\KegiatanTeknis;
use App\Models\PemutakhiranPetani;


class PencacahanPetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tgl_awal = null;
        $tgl_akhir = null;
        $kegiatan = request('kegiatan');

        $pencacahans = PencacahanPetani::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        $pencacahan = PencacahanPetani::where('kegiatan_id', $kegiatan)->first();
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();
        $this->progres_kegiatan($kegiatan->id);
        if ($pencacahan) {
            $tgl_akhir = $pencacahan->tgl_akhir;
            $tgl_awal = $pencacahan->tgl_awal;
        }


        return view('page.teknis.petani.pencacahan.index', [
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
        $pencacahan = PencacahanPetani::where('kegiatan_id', $kegiatan_id)->first();

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

        PencacahanPetani::create($requestValidasi);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PencacahanPetani $pencacahanSatuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pencacahan = PencacahanPetani::find($id);
        return response()->json($pencacahan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        // Validasi input
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

        // Update data PencacahanPetani
        PencacahanPetani::where('id', $request->id)->update($requestValidasi);

        // Update data UserMitra
        UserMitra::where('ppl_id', $request->id_ppl)->update([
            'name' => $request->ppl,
            'ppl_id' => $request->id_ppl
        ]);
        $pencacahan = PencacahanPetani::where('id', $request->id)->first();

        if (!empty($pencacahan->jenis_komoditas) && !empty($pencacahan->nama_krt)) {
            $pencacahan['status'] = true;
        } else {
            $pencacahan['status'] = false;
        }
        $pencacahan->save();

        return back()->with('success', 'Pencacahan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pencacahan = PencacahanPetani::find($request->id);
        $sucsses = PencacahanPetani::destroy($pencacahan->id);
        if ($sucsses) {
            return back()->with('success', 'Id-' . $request->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
        }
    }
    public function store_excel(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv',
                'kegiatan_id' => 'required|integer',
                'tgl_awal' => 'required|date',
                'tgl_akhir' => 'required|date',
            ]);

            // Move uploaded file to storage
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('ExcelTeknis'), $fileName);

            $filePath = public_path('ExcelTeknis/' . $fileName);

            // Import the Excel file
            Excel::import(new PencacahanPetaniImport(
                $request->kegiatan_id,
                $request->tgl_awal,
                $request->tgl_akhir
            ), $filePath);

            // Delete the file after import
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function progres_kegiatan($kegiatan_id)
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
        $progres_pencacahan2 = 0; // Initialize the variable outside the loop

        foreach ($pencacahans as $pencacahan) {
            if ($pencacahan->status == 1) {
                $progres_pencacahan++;
            }
        }

        $progres_pencacahan2 = $progres_pencacahan; // Ensure it is assigned here

        // Avoid division by zero
        if ($beban_kerja_pencacahan_all > 0) {
            $progres_pencacahan = ($progres_pencacahan / $beban_kerja_pencacahan_all) * 100;
            $progres_pencacahan = floatval(number_format($progres_pencacahan, 1));
        } else {
            $progres_pencacahan = 0.0;
        }

        // Menghitung rata-rata progres kegiatan
        if (($beban_kerja_pemutakhiran_all + $beban_kerja_pencacahan_all) > 0) {
            $progres_kegiatan = (($progres_pencacahan2 + $ruta_progres_pemutakhiran) / ($beban_kerja_pemutakhiran_all + $beban_kerja_pencacahan_all)) * 100;
            $progres_kegiatan = floatval(number_format($progres_kegiatan, 1));
        } else {
            $progres_kegiatan = 0.0;
        }


        // Save the result
        $kegiatan = KegiatanTeknis::find($kegiatan_id);
        $kegiatan['progres'] = $progres_kegiatan;
        $kegiatan->save();

        return 0;

        // Optionally, you can return the results if needed
        // return [
        //     'progres_pemutakhiran' => $progres_pemutakhiran,
        //     'progres_pencacahan' => $progres_pencacahan,
        //     'progres_kegiatan' => $progres_kegiatan
        // ];
    }
}
