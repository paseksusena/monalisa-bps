<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;


use App\Models\PemutakhiranRumahTangga;
use Illuminate\Http\Request;
use App\Models\KegiatanTeknis;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PemutakhiranRumahTanggaImport;
use App\Models\RutaRumahTangga;
use App\Models\UserMitra;





class PemutakhiranRumahTanggaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $date = [];
        $kegiatan = request('kegiatan');
        $pemutakhirans = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan)->get();
        if (request('search')) {
            $pemutakhirans = PemutakhiranRumahTangga::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        }
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        if ($pemutakhirans->isEmpty()) {
            $pemutakhiran = [];
            $semua_tanggal = [];
            $tgl_awal = null;
            $tgl_akhir = null;
        } else {
            $date = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan->id)->first();
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

        return view('page.teknis.rumah-tangga.pemutakhiran.index', [
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
        $pemutakhiran = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan_id)->first();

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
        $pemutakhiranRumahTanggaOld = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

        PemutakhiranRumahTangga::create($requestValidasi);

        $existingPemutakhiranIds = $pemutakhiranRumahTanggaOld->pluck('id')->toArray();

        if (!$pemutakhiranRumahTanggaOld->isEmpty()) {
            $pemutakhiranRumahTanggaNew = collect();
            $pemutakhiranRumahTanggaNew = $pemutakhiranRumahTanggaNew->merge($pemutakhiranRumahTanggaOld);
            $pemutakhiranRumahTanggaNewUpdated = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

            foreach ($pemutakhiranRumahTanggaNewUpdated as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranRumahTanggaNew->push($p);
                }
            }
        } else {
            $pemutakhiranRumahTanggaNew = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();
        }

        foreach ($pemutakhiranRumahTanggaNew as $p) {
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
                        RutaRumahTangga::create($ruta);
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
    public function show(PemutakhiranRumahTangga $pemutakhiranRumahTangga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pemutakhiran = PemutakhiranRumahTangga::find($id);
        $ruta = RutaRumahTangga::where('pemutakhiran_id', $pemutakhiran->id)->get();

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
            'keluarga_awal' => 'integer|max:99999',
            'keluarga_akhir' => 'integer|max:99999',

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
                RutaRumahTangga::where('id', $id_ruta)->update([

                    'progres' => $rutaValues[$key]
                ]);
            }
        }

        $rutas = RutaRumahTangga::where('pemutakhiran_id', $id)->get();

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


        PemutakhiranRumahTangga::where('id', $req->id)->update($requestValidasi);


        return back()->with('success', 'Pemutakhiran berhasi di update!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pemutakhiran = PemutakhiranRumahTangga::find($request->id);
        if ($pemutakhiran) {
            $success = $pemutakhiran->delete();
            if ($success) {
                RutaRumahTangga::where('pemutakhiran_id', $pemutakhiran->id)->delete();
                return back()->with('success', 'Id-' . $request->id . " berhasil dihapus!");
            } else {
                return back()->with('error', 'Terdapat kesalahan, coba ulang lagi');
            }
        } else {
            return back()->with('error', 'Data tidak ditemukan');
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
            $pemutakhiranRumahTanggaOld = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

            //MENGHITUNG ESTIMASI
            $tanggalAwal = strtotime($tgl_awal);
            $tanggalAkhir = strtotime($tgl_akhir);
            $estimasi = ($tanggalAkhir - $tanggalAwal) / (60 * 60 * 24);


            Excel::import(new pemutakhiranRumahTanggaImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $existingPemutakhiranIds = $pemutakhiranRumahTanggaOld->pluck('id')->toArray();

            if (!$pemutakhiranRumahTanggaOld->isEmpty()) {
                $pemutakhiranRumahTanggaNew = collect();
                $pemutakhiranRumahTanggaNew = $pemutakhiranRumahTanggaNew->merge($pemutakhiranRumahTanggaOld);
                $pemutakhiranRumahTanggaNewUpdated = pemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

                foreach ($pemutakhiranRumahTanggaNewUpdated as $p) {
                    if (!in_array($p->id, $existingPemutakhiranIds)) {
                        $pemutakhiranRumahTanggaNew->push($p);
                    }
                }
            } else {
                $pemutakhiranRumahTanggaNew = pemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();
            }

            foreach ($pemutakhiranRumahTanggaNew as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $currentDate = $tgl_awal->copy();
                    $endDate = $tgl_akhir->copy();

                    for ($i = 0; $i < $estimasi; $i++) {
                        while ($currentDate <= $endDate) {
                            $ruta = [
                                'pemutakhiran_id' => $p->id,
                                'tanggal' => $currentDate->toDateString(),
                                'ruta' => 'Ruta_' . $i,
                            ];
                            RutaRumahTangga::create($ruta);
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
