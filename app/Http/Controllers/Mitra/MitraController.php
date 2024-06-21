<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\KegiatanTeknis;
use Illuminate\Http\Request;
use App\Models\UserMitra;
use Illuminate\Support\Facades\Auth;
use App\Models\PemutakhiranRumahTangga;
use App\Models\RutaRumahTangga;


class MitraController extends Controller
{
    public function index_pemutakhiran()
    {
        // $session = session('selected_year');
        $session = 2024;
        $id = Auth::guard('usermitra')->user()->ppl_id;
        $kegiatanPemutakhiranRumahTangga = KegiatanTeknis::where('kategori', 'rumah_tangga')->where('tahun', $session)->get();
        $kegiatanPemutakhiranPetani = KegiatanTeknis::where('kategori', 'petani')->where('tahun', $session)->get();
        $kegiatanPemutakhiranPerusahaan = KegiatanTeknis::where('kategori', 'perusahaan')->where('tahun', $session)->get();



        return view('page.mitra.pemutakhiran.index', [
            'active' => 'pemutakhiran',
            'kegiatanPemutakhiranRumahTanggas' => $kegiatanPemutakhiranRumahTangga,
            'kegiatanPemutakhiranPetanis' => $kegiatanPemutakhiranPetani,
            'kegiatanPemutakhiranPerusahaans' => $kegiatanPemutakhiranPerusahaan
        ]);
    }

    public function index_pencacahan()
    {
        // $session = session('selected_year');
        $session = 2024;
        $id = Auth::guard('usermitra')->user()->ppl_id;
        $kegiatanPencacahanRumahTangga = KegiatanTeknis::where('kategori', 'rumah_tangga')->where('tahun', $session)->get();
        $kegiatanPencacahanPetani = KegiatanTeknis::where('kategori', 'petani')->where('tahun', $session)->get();
        $kegiatanPencacahanPerusahaan = KegiatanTeknis::where('kategori', 'perusahaan')->where('tahun', $session)->get();


        return view('page.mitra.pencacahan.index', [
            'active' => 'pencacahan',
            'kegiatanPencacahanRumahTanggas' => $kegiatanPencacahanRumahTangga,
            'kegiatanPencacahanPetanis' => $kegiatanPencacahanPetani,
            'kegiatanPencacahanPerusahaans' => $kegiatanPencacahanPerusahaan
        ]);
    }

    public function input_pemutakhiran_rumah($id)
    {
        $pemutakhiran = PemutakhiranRumahTangga::find($id);
        $ruta = RutaRumahTangga::where('pemutakhiran_id', $id)->get();

        return response()->json([
            'pemutakhiran' => $pemutakhiran,
            'ruta' => $ruta,

        ]);
    }

    public function login_mitra()
    {
        $mitra = Auth::guard('usermitra')->user();
        if ($mitra) {
            return redirect('/mitra-pemutakhiran');
        }
        return view('page.mitra.login');
    }

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
        // dd('ddddkk567kd');


        // Jika autentikasi berhasil, redirect ke halaman mitra
        return redirect('/mitra-pemutakhiran');
    }

    public function logout()
    {
        Auth::guard('usermitra')->logout(); // Logout user dari guard 'usermitra'
        return redirect('/mitra/login'); // Redirect ke halaman login atau halaman lain yang sesuai
    }
}
