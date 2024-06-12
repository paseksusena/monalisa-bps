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





class PemutakhiranPetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kegiatan = request('kegiatan');

        $pemutakhirans = PemutakhiranPetani::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();

        return view('', [
            'pemutakhirans' => $pemutakhirans

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
            'kode_kec' => 'required|max:4',
            'kecamatan' => 'required|max:20|string',
            'kode_desa' => 'required|max:4',
            'desa' => 'required|max:20|string',
            'nbs' => 'required|max:100',
            'nks' => 'required|max:100',
            'nama_sls' => 'required|max:100|string',
            'beban_kerja' => 'required|integer|max:99999',
            'keluarga_awal' => 'integer|max:99999',
            'keluarga_akhir' => 'integer|max:99999',
            'kegiatan_id' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required'

        ]);

        //proses membuat ruta otomatis berdasarkan tgl awal sampai akhir Kegiatan

        $kegiatan = KegiatanTeknis::findOrFail($request->kegiatan_id);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);
        $pemutakhiranPetaniTanggaOld = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

        PemutakhiranPetani::create($requestValidasi);

        $existingPemutakhiranIds = $pemutakhiranPetaniTanggaOld->pluck('id')->toArray();

        if (!$pemutakhiranPetaniTanggaOld->isEmpty()) {
            $pemutakhiranPetaniTanggaNew = collect();
            $pemutakhiranPetaniTanggaNew = $pemutakhiranPetaniTanggaNew->merge($pemutakhiranPetaniTanggaOld);
            $pemutakhiranPetaniTanggaNewUpdated = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

            foreach ($pemutakhiranPetaniTanggaNewUpdated as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranPetaniTanggaNew->push($p);
                }
            }
        } else {
            $pemutakhiranPetaniTanggaNew = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();
        }

        foreach ($pemutakhiranPetaniTanggaNew as $p) {
            if (!in_array($p->id, $existingPemutakhiranIds)) {
                $currentDate = $tgl_awal->copy();
                $endDate = $tgl_akhir->copy();
                $estimasi = $endDate - $currentDate;

                for ($i = 0; $i < $estimasi; $i++) {
                    while ($currentDate <= $endDate) {
                        $ruta = [
                            'pemutakhiaran_id' => $p->id,
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
        return response()->json($pemutakhiran);
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
            'keluarga_awal' => 'integer|max:99999',
            'keluarga_akhir' => 'integer|max:99999',

        ]);
        $find = PemutakhiranPetani::where('id', $req->id)->first();

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

        $rutas = RutaPetani::where('pemutakhiran_id', $find->id)->get();
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
        $pemutakhiran = PemutakhiranPetani::find($request->id)->first();
        $sucsses = PemutakhiranPetani::destroy($pemutakhiran->id);
        if ($sucsses) {
            RutaPetani::where('pemutakhiran_id', $pemutakhiran->id)->delete();
            return back()->with('success', 'Id-' . $request->id . " berhasil dihapus!");
        } else {
            return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
        }
    }

    public function strore_excel(Request $request)
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

            //MENGHITUNG ESTIMASI
            $tanggalAwal = strtotime($tgl_awal);
            $tanggalAkhir = strtotime($tgl_akhir);
            $estimasi = ($tanggalAkhir - $tanggalAwal) / (60 * 60 * 24);


            Excel::import(new PemutakhiranPetaniImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $existingPemutakhiranIds = $pemutakhiranPetaniTanggaOld->pluck('id')->toArray();

            if (!$pemutakhiranPetaniTanggaOld->isEmpty()) {
                $pemutakhiranPetaniTanggaNew = collect();
                $pemutakhiranPetaniTanggaNew = $pemutakhiranPetaniTanggaNew->merge($pemutakhiranPetaniTanggaOld);
                $pemutakhiranPetaniTanggaNewUpdated = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

                foreach ($pemutakhiranPetaniTanggaNewUpdated as $p) {
                    if (!in_array($p->id, $existingPemutakhiranIds)) {
                        $pemutakhiranPetaniTanggaNew->push($p);
                    }
                }
            } else {
                $pemutakhiranPetaniTanggaNew = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();
            }

            foreach ($pemutakhiranPetaniTanggaNew as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $currentDate = $tgl_awal->copy();
                    $endDate = $tgl_akhir->copy();

                    for ($i = 0; $i < $estimasi; $i++) {
                        while ($currentDate <= $endDate) {
                            $ruta = [
                                'id_pemutakhiran' => $p->id,
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
            return redirect()->back()->withInput()->with('error', 'File yang dinput harus sesuai dengan file Excel.');
        }
    }
}
