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
     * Menampilkan daftar pemutakhiran petani untuk kegiatan teknis tertentu.
     */
    public function index()
    {
        $date = []; // Inisialisasi variabel $date
        $kegiatan = request('kegiatan'); // Ambil parameter 'kegiatan' dari request
        $pemutakhirans = PemutakhiranPetani::where('kegiatan_id', $kegiatan)->get(); // Ambil semua pemutakhiran petani berdasarkan 'kegiatan_id'
        $search = null; // Inisialisasi variabel $search

        // Periksa apakah terdapat parameter 'search' dalam request
        if (request('search')) {
            $search = request('search');
        }

        // Jika terdapat parameter 'search', filter hasil pemutakhiran berdasarkan 'search' dan 'kegiatan_id'
        if (request('search')) {
            $pemutakhirans = PemutakhiranPetani::filter(request(['search']))->where('kegiatan_id', $kegiatan)->get();
        }

        // Ambil detail kegiatan teknis berdasarkan 'id' kegiatan
        $kegiatan = KegiatanTeknis::where('id', $kegiatan)->first();

        // Panggil fungsi untuk menghitung dan memperbarui progres kegiatan

        // Inisialisasi variabel untuk tanggal awal dan akhir pemutakhiran petani. tujuan untuk menampilkan tanggal awal dan akhir di halama pemutakhiran petani
        if ($pemutakhirans->isEmpty()) {
            $pemutakhiran = [];
            $semua_tanggal = [];
            $tgl_awal = null;
            $tgl_akhir = null;
        } else {
            // Ambil tanggal pemutakhiran pertama untuk kegiatan teknis tertentu
            $date = PemutakhiranPetani::where('kegiatan_id', $kegiatan->id)->first();
            if (!$date) {
                $tgl_awal = null; // Jika tidak ada data tanggal, atur $tgl_awal menjadi null
                $tgl_akhir = null; // Jika tidak ada data tanggal, atur $tgl_akhir menjadi null
            } else {
                // Konversi rentang tanggal awal dan akhir menjadi format yang diinginkan ('hari/bulan')
                $semua_tanggal = Carbon::parse($date->tgl_awal)->toPeriod($date->tgl_akhir)->map(function ($date) {
                    return $date->format('d/m/Y');
                });
                $tgl_awal = $date->tgl_awal; // Simpan tanggal awal dari pemutakhiran
                $tgl_akhir = $date->tgl_akhir; // Simpan tanggal akhir dari pemutakhiran
            }
        }

        // Kirimkan data ke view 'index' dengan menggunakan Blade template
        return view('page.teknis.petani.pemutakhiran.index', [
            'search' => $search,
            'pemutakhirans' => $pemutakhirans,
            'kegiatan' => $kegiatan,
            "semua_tanggal" => $semua_tanggal ?? [], // Gunakan data tanggal yang telah diinisialisasi atau array kosong jika tidak tersedia
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
        ]);
    }


    // function untuk menampilkan form create. Tujuannya untuk mengetahui apakah sudah ada pemutakhiran dibuat atau belum sebelumnya. Jika ada ambil tanggal awal dan akhirnya pemutakhiran sebelumnya
    public function create($kegiatan_id)
    {
        // Cari entri pemutakhiran petani berdasarkan 'kegiatan_id'
        $pemutakhiran = PemutakhiranPetani::where('kegiatan_id', $kegiatan_id)->first();

        // Jika pemutakhiran ditemukan, kirimkan data pemutakhiran dalam format JSON
        if ($pemutakhiran) {
            return response()->json($pemutakhiran);
        } else {
            // Jika pemutakhiran tidak ditemukan, kirimkan respons JSON dengan nilai 0
            return response()->json(0);
        }
    }


    // function untuk menambahkan data baru pemutakhiran 
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
            'nbs' => 'required|max:100',
            'nks' => 'required|max:100',
            'nama_sls' => 'required|max:100|string',
            'beban_kerja' => 'required|integer|max:99999',
            'kegiatan_id' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required'
        ]);

        // Proses membuat ruta otomatis berdasarkan tgl_awal dan tgl_akhir kegiatan
        $kegiatan = KegiatanTeknis::findOrFail($request->kegiatan_id);
        $tgl_awal = Carbon::parse($request->tgl_awal);
        $tgl_akhir = Carbon::parse($request->tgl_akhir);
        $pemutakhiranPetaniOld = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

        // Inisialisasi kolom keluarga_awal dan keluarga_akhir
        $requestValidasi['keluarga_awal'] = 0;
        $requestValidasi['keluarga_akhir'] = 0;

        // Simpan data pemutakhiran petani baru
        PemutakhiranPetani::create($requestValidasi);

        // Ambil ID dari pemutakhiran petani yang sudah ada sebelumnya
        $existingPemutakhiranIds = $pemutakhiranPetaniOld->pluck('id')->toArray();

        // Update atau tambahkan ruta baru jika ada pemutakhiran petani yang sudah ada
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

        // Iterasi setiap pemutakhiran petani untuk menambahkan ruta sesuai periode kegiatan
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

        // Jika tidak ditemukan, buat entri UserMitra baru
        if (!$find) {
            UserMitra::create([
                'name' => $request->ppl,
                'ppl_id' => $request->id_ppl
            ]);
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Data berhasil ditambahkan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(PemutakhiranPetani $pemutakhiranPetaniTangga)
    {
        //
    }


    // function untuk menampilkan data yang di edit
    public function edit($id)
    {
        // Temukan entri pemutakhiran petani berdasarkan ID
        $pemutakhiran = PemutakhiranPetani::find($id);

        // Temukan semua entri ruta petani yang terkait dengan pemutakhiran ini
        $ruta = RutaPetani::where('pemutakhiran_id', $pemutakhiran->id)->get();

        // Kembalikan response JSON berisi data pemutakhiran dan ruta
        return response()->json([
            'pemutakhiran' => $pemutakhiran,
            'ruta' => $ruta,
        ]);
    }


    // melakukan update pada pemutakhiran petani
    public function update(Request $req)
    {
        // Validasi input request
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

        // Validasi input untuk setiap nilai Ruta
        $this->validate($req, [
            'ruta.*' => 'nullable|integer|max:99999', // Integer dengan maksimal 5 digit, opsional (nullable)
        ], [
            'ruta.*.integer' => 'Nilai Ruta harus berupa bilangan bulat.',
            'ruta.*.max' => 'Nilai Ruta tidak boleh lebih dari 5 digit.'
        ]);

        // Update nilai progres untuk setiap ruta yang ada
        if ($req->has('id_ruta')) {
            $rutas = $req->input('id_ruta');
            $rutaValues = $req->input('ruta');

            foreach ($rutas as $key => $id_ruta) {
                RutaPetani::where('id', $id_ruta)->update([
                    'progres' => $rutaValues[$key]
                ]);
            }
        }

        // Menghitung total progres dari semua ruta
        $rutas = RutaPetani::where('pemutakhiran_id', $id)->get();
        $status = 0;
        foreach ($rutas as $ruta) {
            $status += $ruta->progres;
        }

        // Menghitung persentase penyelesaian berdasarkan beban kerja
        $progres = ($status / $req->beban_kerja) * 100;
        $progres = floatval(number_format($progres, 1));

        // Menambahkan nilai persentase penyelesaian ke dalam requestValidasi
        $requestValidasi["penyelesaian_ruta"] = $progres;

        // Menetapkan status selesai atau belum selesai berdasarkan total progres
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

        // Update data PemutakhiranPetani berdasarkan ID
        PemutakhiranPetani::where('id', $req->id)->update($requestValidasi);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pemutakhiran berhasil diupdate!');
    }

    /**
     * Proses untuk menghapus pemutakhiran petani
     */

    public function destroy(Request $request)
    {
        // Temukan entri PemutakhiranPetani berdasarkan ID yang diterima dari request
        $pemutakhiran = PemutakhiranPetani::find($request->id);

        // Hapus entri PemutakhiranPetani dan simpan status penghapusan
        $success = PemutakhiranPetani::destroy($pemutakhiran->id);

        // Jika penghapusan berhasil, hapus juga semua data RutaPetani terkait
        if ($success) {
            RutaPetani::where('pemutakhiran_id', $pemutakhiran->id)->delete();
            return back()->with('success', 'Data dengan ID-' . $request->id . ' berhasil dihapus!');
        } else {
            // Jika terjadi kesalahan saat penghapusan, beri tahu pengguna dengan pesan error
            return back()->with('error', 'Terdapat kesalahan saat menghapus, coba lagi nanti.');
        }
    }
    /**
     * Menyimpan data yang diimpor dari file Excel ke database pemutakhiran petani.
     */
    public function store_excel(Request $request)
    {
        try {
            // Pindahkan file yang diunggah ke storage
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $file->move('ExcelTeknis', $fileName);

            // Ambil periode dan parse tanggal
            $tgl_awal = Carbon::parse($request->tgl_awal);
            $tgl_akhir = Carbon::parse($request->tgl_akhir);

            // Ambil data PemutakhiranPetani yang sudah ada
            $pemutakhiranPetaniTanggaOld = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();

            // Hitung estimasi jumlah hari
            $tanggalAwal = strtotime($tgl_awal);
            $tanggalAkhir = strtotime($tgl_akhir);
            $estimasi = ($tanggalAkhir - $tanggalAwal) / (60 * 60 * 24);

            // Impor file Excel menggunakan class PemutakhiranPetaniImport
            Excel::import(new PemutakhiranPetaniImport($request->kegiatan_id, $tgl_awal, $tgl_akhir), public_path('/ExcelTeknis/' . $fileName));

            // Hapus file yang diunggah setelah selesai diimpor
            $filePath = public_path('/ExcelTeknis/' . $fileName);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Ambil ID PemutakhiranPetani yang sudah ada
            $existingPemutakhiranIds = $pemutakhiranPetaniTanggaOld->pluck('id')->toArray();

            // Gabungkan data PemutakhiranPetani yang lama dan baru
            $pemutakhiranPetaniTanggaNew = PemutakhiranPetani::where('kegiatan_id', $request->kegiatan_id)->get();
            foreach ($pemutakhiranPetaniTanggaNew as $p) {
                if (!in_array($p->id, $existingPemutakhiranIds)) {
                    $pemutakhiranPetaniTanggaOld->push($p);
                }
            }

            // Looping untuk membuat entri RutaPetani berdasarkan estimasi hari
            for ($i = 0; $i < $estimasi; $i++) {
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
            return redirect()->back()->withInput()->with('error', 'File yang diunggah harus sesuai dengan format Excel yang benar.');
        }
    }
}
