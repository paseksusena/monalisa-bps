<?php



namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\KegiatanTeknis;
use Illuminate\Http\Request;
use Carbon\Carbon;



class KegiatanTeknisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);
        //jika tidak ada session selected_year pakai tahun sekarang

        //jika tidak ada session selected_year pakai tahun sekarang

        // if (request('tahun')) {
        //     session()->put('selected_year', request('tahun'));
        // }

        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }


        $session = session('selected_year');
        $fungsi = request('fungsi');
        $kegiatans = KegiatanTeknis::where('fungsi', $fungsi)->where('tahun', $session)->get();

        return view('page.teknis.index', [
            'kegiatans' => $kegiatans,
            'fungsi' => $fungsi,
            'tahun' =>  $years,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validasiRequest = $request->validate([
            'nama' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'fungsi' => 'required',
            'kategori' => 'required',
            'tahun' => 'required'


        ]);


        // if ($request->kategori == 'direct_link') {
        //     $validasiRequest = [
        //         'link' => 'required'
        //     ];
        // }

        $validasiRequest['link'] = $request->link;
        $find = KegiatanTeknis::where('nama', $request->nama)->first();
        if ($find) {
            return back()->with('error', 'Nama kegaiatan ' . $request->nama . ' sudah tersedia!');
        }

        KegiatanTeknis::create($validasiRequest);

        return back()->with('success', 'Kegiatan ' . $request->nama . ' berhasil di upload');
    }

    /**
     * Display the specified resource.
     */
    public function show(KegiatanTeknis $kegiatanTeknis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kegiatan = KegiatanTeknis::find($id);

        return response()->json($kegiatan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validasiRequest = $request->validate([
            'nama' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'fungsi' => 'required',
            'kategori' => 'required',


        ]);

        if ($request->kategori == 'direct_link') {
            $validasiRequest = [
                'link' => 'required'
            ];
        }
        // Periksa apakah ada record dengan 'nama' yang sama, tapi bukan record yang sedang diperbarui
        $existingRecord = KegiatanTeknis::where('nama', $request->nama)
            ->where('id', '!=', $request->id)
            ->first();

        if ($existingRecord) {
            return back()->with('error', 'Nama kegiatan ' . $request->nama . ' sudah tersedia!');
        }

        // Perbarui record dengan data yang telah divalidasi
        KegiatanTeknis::where('id', $request->id)->update($validasiRequest);

        return back()->with('success', 'Kegiatan ' . $request->nama . ' berhasil diubah');
    }

    public function downloadTemlate()
    {
        $nameFile = request('template');
        $path = public_path('storage/template/' . $nameFile . '.xlsx');
        return response()->download($path);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KegiatanTeknis $kegiatanTeknis)
    {
        //
    }
}
