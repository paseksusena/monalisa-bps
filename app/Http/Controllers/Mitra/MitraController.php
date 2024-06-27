<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\KegiatanTeknis;
use Illuminate\Http\Request;
use App\Models\UserMitra;
use Illuminate\Support\Facades\Auth;
use App\Models\PemutakhiranRumahTangga;
use App\Models\PencacahanRumahTangga;
use App\Models\RutaRumahTangga;
use App\Models\PencacahanPetani;
use App\Models\PemutakhiranPetani;
use App\Models\RutaPetani;
use Carbon\Carbon;



class MitraController extends Controller
{
    //mengambil data pemutakhiran
    public function index_pemutakhiran()
    {
        // memanggil function getYear
        $sessionYear = $this->getYear();
        $session = session('selected_year');
        $id = Auth::guard('usermitra')->user()->ppl_id;
        $search = null;


        // mengambil  data pemutakhiran petani 
        $kegiatanPemutakhiranPetanis = KegiatanTeknis::with([
            'pemutakhiranPetani' => function ($query) {
                $search = request('search');
                $query->filter(['search' => $search]);
            }
        ])->where('tahun', $session)->get();

        // mengambil  data pemutakhiran rumah tangga 
        $kegiatanPemutakhiranRumahTangga = KegiatanTeknis::with([
            'pemutakhiranRumahTangga' => function ($query) {
                $search = request('search');
                $query->filter(['search' => $search]);
            }
        ])->where('tahun', $session)->get();

        // mengambil  data pemutakhiran rumah tangga 
        $kegiatanPemutakhiranPerusahaan = KegiatanTeknis::with([
            'pemutakhiranPerusahaan' => function ($query) {
                $search = request('search');
                $query->filter(['search' => $search]);
            }
        ])->where('tahun', $session)->get();


        // jika ada request search
        if (request('search')) {
            $search = request('search');
        }

        return view('page.mitra.pemutakhiran.index', [
            'search' => $search,
            'years' => $sessionYear,
            'active' => 'pemutakhiran',
            'kegiatanPemutakhiranRumahTanggas' => $kegiatanPemutakhiranRumahTangga,
            'kegiatanPemutakhiranPetanis' => $kegiatanPemutakhiranPetanis,
            'kegiatanPemutakhiranPerusahaans' => $kegiatanPemutakhiranPerusahaan
        ]);
    }

    //mengambil data pencacahan
    public function index_pencacahan()
    {
        $sessionYear = $this->getYear();
        $session = session('selected_year');
        $id = Auth::guard('usermitra')->user()->ppl_id;
        $search = null;



        //jika ada request search
        if (request('search')) {
            $search = request('search');
        }

        // mengambil data pencacah petani
        $kegiatanPencacahanPetani = KegiatanTeknis::with(['pencacahanPetani' => function ($query) use ($search) {
            $query->filter(['search' => $search]);
        }])->where('tahun', $session)->get();

        // mengambil data pencacah rumah tangga
        $kegiatanPencacahanRumahTangga = KegiatanTeknis::with(['pencacahanRumahTangga' => function ($query) use ($search) {
            $query->filter(['search' => $search]);
        }])->where('tahun', $session)->get();

        // mengambil data pencacah perusahaan
        $kegiatanPencacahanPerusahaan = KegiatanTeknis::with(['pencacahanPerusahaan' => function ($query) use ($search) {
            $query->filter(['search' => $search]);
        }])->where('tahun', $session)->get();

        return view('page.mitra.pencacahan.index', [
            'search' => $search,
            'years' => $sessionYear,
            'active' => 'pencacahan',
            'kegiatanPencacahanRumahTanggas' => $kegiatanPencacahanRumahTangga,
            'kegiatanPencacahanPetanis' => $kegiatanPencacahanPetani,
            'kegiatanPencacahanPerusahaans' => $kegiatanPencacahanPerusahaan
        ]);
    }

    // mengambi data pemutakhiran rumah tangga yang akan di input 
    public function input_pemutakhiran_rumah($id)
    {
        $pemutakhiran = PemutakhiranRumahTangga::find($id);
        $ruta = RutaRumahTangga::where('pemutakhiran_id', $id)->get();

        return response()->json([
            'pemutakhiran' => $pemutakhiran,
            'ruta' => $ruta,

        ]);
    }

    // mengambi data pemutakhiran petani yang akan di input 
    public function input_pemutakhiran_petani($id)
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


    // mengambi data pencacahan rumah tangga yang akan di input 
    public function input_pencacahan_rumah($id)
    {
        $pencacahan = PencacahanRumahTangga::find($id);
        return response()->json($pencacahan);
    }

    // mengambi data pencacahan petani yang akan di input 

    public function input_pencacahan_petani($id)
    {
        $pencacahan = PencacahanPetani::find($id);
        return response()->json($pencacahan);
    }



    // function untuk menampilkan login mitra
    public function login_mitra()
    {
        return view('page.mitra.login');
    }


    // function melakukan autentikasi login mitra
    public function autentikasi(Request $request)
    {
        // Validasi input
        $request->validate([
            'ppl_id' => 'required|string',
        ]);

        // Mencari user dengan ppl_id
        $find = UserMitra::where('ppl_id', $request->ppl_id)->first();

        // Jika user tidak ditemukan, kembali dengan pesan error
        if (!$find) {
            return back()->with('error', 'ID mitra tidak ditemukan');
        }

        // Login manual tanpa menggunakan Auth::guard
        Auth::guard('usermitra')->login($find);

        // Jika autentikasi berhasil, redirect ke halaman mitra
        return redirect()->intended('/mitra-pemutakhiran');
    }

    // function logout
    public function logout()
    {
        Auth::guard('usermitra')->logout(); // Logout user dari guard 'usermitra'
        return redirect('/mitra/login'); // Redirect ke halaman login atau halaman lain yang sesuai
    }

    // function untuk mengambil tahun
    public function getYear()
    {
        $currentYear = Carbon::now()->year;
        $startYear = Carbon::createFromFormat('Y', $currentYear - 5)->year;
        $years = range($startYear, $currentYear);

        if (!session('selected_year')) {
            session()->put('selected_year', $currentYear);
        }

        return $years;
    }
}
