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
     * Menampilkan halaman indeks untuk Pemutakhiran Rumah Tangga.
     */
    public function index()
    {
        $date = [];
        $kegiatan = request('kegiatan');
        $search = null;

        // Ambil data PemutakhiranRumahTangga berdasarkan kegiatan_id
        $pemutakhirans = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan)->get();

        // Jika terdapat pencarian, filter data berdasarkan pencarian dan kegiatan_id
        if (request('search')) {
            $pemutakhirans = PemutakhiranRumahTangga::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        }

        // Ambil data kegiatan teknis berdasarkan id kegiatan
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        // Inisialisasi variabel untuk tanggal dan pencarian
        $semua_tanggal = [];
        $tgl_awal = null;
        $tgl_akhir = null;

        // Jika tidak ada data PemutakhiranRumahTangga, inisialisasikan variabel
        if ($pemutakhirans->isEmpty()) {
            $pemutakhiran = [];
        } else {
            $date = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan->id)->first();
            if (!$date) {
                $tgl_awal = null;
                $tgl_akhir = null;
            } else {
                // Parse tanggal awal dan akhir menggunakan Carbon
                $semua_tanggal = Carbon::parse($date->tgl_awal)->toPeriod($date->tgl_akhir)->map(function ($date) {
                    return $date->format('d/m/Y'); // Mengubah format tanggal menjadi 'hari/bulan'
                });
                $tgl_awal = $date->tgl_awal;
                $tgl_akhir = $date->tgl_akhir;
            }
        }

        // Jika ada pencarian, simpan nilai pencarian
        if (request('search')) {
            $search = request('search');
        }

        // Mengembalikan view dengan data yang diperlukan
        return view('page.teknis.rumah-tangga.pemutakhiran.index', [
            'pemutakhirans' => $pemutakhirans,
            'kegiatan' => $kegiatan,
            "semua_tanggal" => $semua_tanggal,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'search' => $search,
        ]);
    }


    /**
     * function untuk menampilkan data create. Tujuannya untuk mengetahui apakah sudah ada pemutakhiran dibuat atau belum sebelumnya. Jika ada ambil tanggal awal dan akhirnya pemutakhiran sebelumnya
     */
    public function create($kegiatan_id)
    {
        // Cari PemutakhiranRumahTangga berdasarkan kegiatan_id
        $pemutakhiran = PemutakhiranRumahTangga::where('kegiatan_id', $kegiatan_id)->first();

        // Jika ditemukan, kembalikan data pemutakhiran dalam format JSON. Tunu
        if ($pemutakhiran) {
            return response()->json($pemutakhiran);
        } else {
            // Jika tidak ditemukan, kembalikan nilai 0 dalam format JSON
            return response()->json(0);
        }
    }


    /**
     * Function untuk menyimpan data pemutakhiran rumah tangga.
     */
    public function store(Request $request)
    {
        // Validasi data masukan
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
            'kegiatan_id' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
        ]);

        // Proses identifikasi keluarga awal dan akhir sebagai 0
        $requestValidasi['keluarga_awal'] = 0;
        $requestValidasi['keluarga_akhir'] = 0;

        // Proses membuat ruta secara otomatis berdasarkan tanggal awal dan akhir kegiatan
        $kegiatan = KegiatanTeknis::findOrFail($request->kegiatan_id);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);
        $pemutakhiranRumahTanggaOld = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

        // Membuat entri baru PemutakhiranRumahTangga
        PemutakhiranRumahTangga::create($requestValidasi);

        // Mendapatkan ID dari pemutakhiran yang sudah ada
        $existingPemutakhiranIds = $pemutakhiranRumahTanggaOld->pluck('id')->toArray();

        // Menyatukan pemutakhiran yang lama dan baru
        if (!$pemutakhiranRumahTanggaOld->isEmpty()) {
            $pemutakhiranRumahTanggaNew = collect();
            $pemutakhiranRumahTanggaNew = $pemutakhiranRumahTanggaNew->merge($pemutakhiranRumahTanggaOld);
            $pemutakhiranRumahTanggaNewUpdated = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

            // Memasukkan pemutakhiran yang baru
            foreach ($pemutakhiranRumahTanggaNewUpdated as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranRumahTanggaNew->push($p);
                }
            }
        } else {
            $pemutakhiranRumahTanggaNew = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();
        }

        // Membuat ruta untuk setiap pemutakhiran yang baru
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

        // Cari atau buat entri UserMitra berdasarkan ppl_id
        $find = UserMitra::where('ppl_id', $request->id_ppl)->first();

        // Jika tidak ditemukan, buat entri baru UserMitra
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir data yang akan diedit
     */
    public function edit($id)
    {
        // Temukan pemutakhiran rumah tangga berdasarkan ID
        $pemutakhiran = PemutakhiranRumahTangga::find($id);

        // Temukan semua ruta yang terkait dengan pemutakhiran ini
        $ruta = RutaRumahTangga::where('pemutakhiran_id', $id)->get();

        // Kembalikan respons JSON dengan data pemutakhiran dan ruta
        return response()->json([
            'pemutakhiran' => $pemutakhiran,
            'ruta' => $ruta,
        ]);
    }

    /**
     * Melakukan update pemutakhiran rumah tangga
     */
    public function update(Request $req)
    {
        // Validasi request input
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

        // Validasi untuk ruta
        $this->validate($req, [
            'ruta.*' => 'nullable|integer|max:99999', // Integer dengan maksimal 5 digit, opsional (nullable)
        ], [
            'ruta.*.integer' => 'Nilai Ruta harus berupa bilangan bulat.',
            'ruta.*.max' => 'Nilai Ruta tidak boleh lebih dari 5 digit.'
        ]);

        // Update progres pada ruta yang diberikan
        if ($req->has('id_ruta')) {
            $rutas = $req->input('id_ruta');
            $rutaValues = $req->input('ruta');

            foreach ($rutas as $key => $id_ruta) {
                RutaRumahTangga::where('id', $id_ruta)->update([
                    'progres' => $rutaValues[$key]
                ]);
            }
        }

        // Menghitung status dan persentase penyelesaian berdasarkan ruta
        $rutas = RutaRumahTangga::where('pemutakhiran_id', $id)->get();
        $status = 0;

        foreach ($rutas as $ruta) {
            $status += $ruta->progres;
        }

        $progres = ($status / $req->beban_kerja) * 100;
        $progres = floatval(number_format($progres, 1));

        // Menetapkan nilai persentase dan status ke dalam array requestValidasi
        $requestValidasi["penyelesaian_ruta"] = $progres;

        if ($status >= $req->beban_kerja) {
            $requestValidasi['status'] = true;
        } else {
            $requestValidasi['status'] = false;
        }
        //Perbarui atau buat UserMitra
        UserMitra::updateOrCreate(
            ['ppl_id' => $req->id_ppl],
            ['name' => $req->ppl]
        );

        // Update data PemutakhiranRumahTangga berdasarkan ID
        PemutakhiranRumahTangga::where('id', $req->id)->update($requestValidasi);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Pemutakhiran berhasil diupdate!');
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

    // function untuk melakukan penyimpanan melalui excel
    public function store_excel(Request $request)
    {
        try {
            // Memindahkan file yang diunggah ke dalam direktori penyimpanan
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('ExcelTeknis', $fileName);

            // Mendapatkan periode dan mem-parsir tanggal-tanggal
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);
            $pemutakhiranRumahTanggaOld = PemutakhiranRumahTangga::where('kegiatan_id', $request->kegiatan_id)->get();

            // Menghitung estimasi jumlah hari
            $tanggalAwal = strtotime($tgl_awal);
            $tanggalAkhir = strtotime($tgl_akhir);
            $estimasi = ($tanggalAkhir - $tanggalAwal) / (60 * 60 * 24);

            // Mengimpor file Excel
            Excel::import(new pemutakhiranRumahTanggaImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            // Menghapus file Excel yang diunggah setelah selesai mengimpor
            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Mengambil ID pemutakhiran lama untuk digunakan dalam pembuatan ruta otomatis
            $existingPemutakhiranIds = $pemutakhiranRumahTanggaOld->pluck('id')->toArray();

            // Menggabungkan data pemutakhiran lama dan baru
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

            // Membuat entri ruta otomatis untuk setiap pemutakhiran baru
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
            return redirect()->back()->withInput()->with('error', 'File yang diunggah harus sesuai dengan format Excel yang diharapkan.');
        }
    }
}
