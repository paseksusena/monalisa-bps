<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;


use App\Models\PemutakhiranPetani;
use Illuminate\Http\Request;
use App\Models\KegiatanTeknis;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PemutakhiranPetaniImport;
use App\Models\RutaPetani;
use App\Models\UserMitra;
use App\Models\PencacahanPetani;





class PemutakhiranPetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $date = [];
        $kegiatan = request('kegiatan');
        $pemutakhirans = PemutakhiranPetani::where('kegiatan_id', $kegiatan)->get();
        if (request('search')) {
            $pemutakhirans = PemutakhiranPetani::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        }
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();
        $this->progres_kegiatan($kegiatan->id);

        if ($pemutakhirans->isEmpty()) {
            $pemutakhiran = [];
            $semua_tanggal = [];
            $tgl_awal = null;
            $tgl_akhir = null;
        } else {
            $date = PemutakhiranPetani::where('kegiatan_id', $kegiatan->id)->first();
            if (!$date) {
                $tgl_awal = null;
                $tgl_akhir = null;
            } else {
                $semua_tanggal = Carbon::parse($date->tgl_awal)->toPeriod($date->tgl_akhir)->map(function ($date) {
                    return $date->format('d/m/Y'); // Mengubah format tanggal menjadi 'hari/bulan'
                });
                $tgl_awal = $date->tgl_awal;
                $tgl_akhir = $date->tgl_akhir;
            }
        }

        return view('page.teknis.petani.pemutakhiran.index', [
            'pemutakhirans' => $pemutakhirans,
            'kegiatan' => $kegiatan,
            "semua_tanggal" => $semua_tanggal ?? [],
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kegiatan_id)
    {
        $pemutakhiran = PemutakhiranPetani::where('kegiatan_id', $kegiatan_id)->first();

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
        $requestValidasi = $request->validate([

            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nbs' => 'required|max:100',
            'nks' => 'required|max:100',
            'nama_sls' => 'required|max:100|string',
            'beban_kerja' => 'required|integer|max:99999',
            // 'keluarga_awal' => 'integer|max:99999',
            // 'keluarga_akhir' => 'integer|max:99999',
            'kegiatan_id' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required'

        ]);

        //proses membuat ruta otomatis berdasarkan tgl awal sampai akhir Kegiatan

        $kegiatan = KegiatanTeknis::findOrFail($request->kegiatan_id);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);
        $pemutakhiranPetaniOld = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();


        $requestValidasi['keluarga_awal'] = 0;
        $requestValidasi['keluarga_akhir'] = 0;
        PemutakhiranPetani::create($requestValidasi);

        $existingPemutakhiranIds = $pemutakhiranPetaniOld->pluck('id')->toArray();

        if (!$pemutakhiranPetaniOld->isEmpty()) {
            $pemutakhiranPetaniNew = collect();
            $pemutakhiranPetaniNew = $pemutakhiranPetaniNew->merge($pemutakhiranPetaniOld);
            $pemutakhiranPetaniNewUpdated = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

            foreach ($pemutakhiranPetaniNewUpdated as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranPetaniNew->push($p);
                }
            }
        } else {
            $pemutakhiranPetaniNew = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();
        }

        foreach ($pemutakhiranPetaniNew as $p) {
            if (!in_array($p->id, $existingPemutakhiranIds)) {
                $currentDate = $tgl_awal->copy();
                $endDate = $tgl_akhir->copy();
                $estimasi = $endDate->diffInDays($currentDate);

                for ($i = 0; $i < $estimasi; $i++) {
                    while ($currentDate <= $endDate) {
                        $ruta = [
                            'pemutakhiran_id' => $p->id,
                            'tanggal' => $currentDate->toDateString(),
                            'ruta' => 'Ruta_' . $i,
                        ];
                        RutaPetani::create($ruta);
                        $currentDate->addDay();
                        $i++;
                    }
                }
            }
        }


        // Cari UserMitra berdasarkan ppl_id
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();

        // Jika tidak difind, buat entri UserMitra baru
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        return back()->with('success', 'Data berasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PemutakhiranPetani $pemutakhiranPetaniTangga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pemutakhiran = PemutakhiranPetani::find($id);
        $ruta = RutaPetani::where('pemutakhiran_id', $pemutakhiran->id)->get();

        return response()->json([
            'pemutakhiran' => $pemutakhiran,
            'ruta' => $ruta,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {


        $requestValidasi = $req->validate([

            'pml' => 'required|max:100|string',
            'id_pml' => 'required|max:30',
            'ppl' => 'required|max:100|string',
            'id_ppl' => 'required|max:30',
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nbs' => 'required|max:100',
            'nks' => 'required|max:100',
            'nama_sls' => 'required|max:100|string',
            'beban_kerja' => 'required|integer|max:99999',
            'keluarga_awal' => 'nullable|integer|max:99999',
            'keluarga_akhir' => 'nullable|integer|max:99999',

        ]);
        $id = $req->id;



        //save ruta
        $this->validate($req, [
            'ruta.*' => 'nullable|integer|max:99999', // Integer dengan maksimal 5 digit, opsional (nullable)
        ], [
            'ruta.*.integer' => 'Nilai Ruta harus berupa bilangan bulat.',
            'ruta.*.max' => 'Nilai Ruta tidak boleh lebih dari 5 digit.'
        ]);


        //save ruta
        if ($req->has('id_ruta')) {
            $rutas = $req->input('id_ruta');
            $rutaValues = $req->input('ruta');

            foreach ($rutas as $key => $id_ruta) {
                // Update progres untuk setiap ruta
                RutaPetani::where('id', $id_ruta)->update([

                    'progres' => $rutaValues[$key]
                ]);
            }
        }

        $rutas = RutaPetani::where('pemutakhiran_id', $id)->get();

        $status = 0;
        foreach ($rutas as $ruta) {

            $status = $ruta->progres + $status;
        }

        $progres = ($status / $req->beban_kerja) * 100; // Menghitung persentase
        $progres = floatval(number_format($progres, 1));
        // $progres = number_format($progres, 2) . '%'; // Mengubah nilai ke format persen dengan dua desimal

        // Kemudian, Anda dapat menetapkan nilai persentase ini ke dalam array requestValidasi
        $requestValidasi["penyelesaian_ruta"] = $progres;


        if ($status >= $req->beban_kerja) {

            $requestValidasi['status'] = true;
        } else {
            $requestValidasi['status'] = false;
        }


        UserMitra::where('ppl_id', $req->id_ppl)->update([
            'name' => $req->ppl,
            'ppl_id' => $req->id_ppl
        ]);


        PemutakhiranPetani::where('id', $req->id)->update($requestValidasi);


        return back()->with('success', 'Pemutakhiran berhasi di update!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pemutakhiran = PemutakhiranPetani::find($request->id);
        $sucsses = PemutakhiranPetani::destroy($pemutakhiran->id);
        if ($sucsses) {
            RutaPetani::where('pemutakhiran_id', $pemutakhiran->id)->delete();
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
            $pemutakhiranPetaniTanggaOld = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

            // Calculate estimation
            $tanggalAwal = strtotime($tgl_awal);
            $tanggalAkhir = strtotime($tgl_akhir);
            $estimasi = ($tanggalAkhir - $tanggalAwal) / (60 * 60 * 24);

            // Import Excel file
            Excel::import(new PemutakhiranPetaniImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            // Delete uploaded file
            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $existingPemutakhiranIds = $pemutakhiranPetaniTanggaOld->pluck('id')->toArray();

            // Merge old and new PemutakhiranPetani
            $pemutakhiranPetaniTanggaNew = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();
            foreach ($pemutakhiranPetaniTanggaNew as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranPetaniTanggaOld->push($p);
                }
            }

            for ($i = 0; $i < $estimasi; $i++) {

                // Create RutaPetani for new PemutakhiranPetani
                foreach ($pemutakhiranPetaniTanggaOld as $p) {
                    if (!in_array($p->id, $existingPemutakhiranIds)) {
                        $currentDate = $tgl_awal->copy();
                        while ($currentDate <= $tgl_akhir) {
                            $ruta = [
                                'pemutakhiran_id' => $p->id,
                                'tanggal' => $currentDate->toDateString(),
                                'ruta' => 'Ruta_' . $i,
                            ];
                            RutaPetani::create($ruta);
                            $currentDate->addDay();
                            $i++;
                        }
                    }
                }
            }

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'File yang diinput harus sesuai dengan file Excel.');
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
