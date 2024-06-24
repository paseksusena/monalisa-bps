<?php

use App\Http\Controllers\Admin\TahunAdministrasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Administrasi\AdministrasiController;
use App\Http\Controllers\Administrasi\AkunController;
use App\Http\Controllers\Administrasi\FileController;
use App\Http\Controllers\Administrasi\KegiatanAdministrasiController;
use App\Http\Controllers\Administrasi\TransaksiController;
use App\Http\Controllers\Mitra\MitraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teknis\KegiatanTeknisController;
use App\Http\Controllers\Teknis\PemutakhiranPerusahaanController;
use App\Http\Controllers\Teknis\PemutakhiranPetaniController;
use App\Http\Controllers\Teknis\PemutakhiranRumahTanggaController;
use App\Http\Controllers\Teknis\PencacahanPerusahaanController;
use App\Http\Controllers\Teknis\PencacahanPetaniController;
use App\Http\Controllers\Teknis\PencacahanRumahTanggaController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/administrasi/preview', function () {
    return view('page.administrasi.partials.preview');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//setSession
Route::post('/set-year-session', [KegiatanTeknisController::class, 'setYearSession']);
//search
Route::get('/teknis/kegiatan/search', [KegiatanTeknisController::class, 'search']);

// ROUTE AUTENTIKASI USER
//Autentikasi User Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROUTE AUTENTIKASI ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Rute-rute yang memerlukan autentikasi dan akses admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users-edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users-update', [UserController::class, 'update'])->name('admin.users.update');
    Route::resource('/admin', UserController::class);
    Route::post('/user/store_excel', [UserController::class, 'storeExcel'])->name('user.storeExcel');

    // Rute untuk tambah-tahun dengan middleware admin
    Route::get('/admin-tambah-tahun', [TahunAdministrasiController::class, 'index'])->name('tahun.index');
    Route::delete('/hapus-arsip-tahun', [TahunAdministrasiController::class, 'destroy']);
    Route::get('/download-zip', [TahunAdministrasiController::class, 'download_zip']);
});

// ROUTE ADMINISTRASI MONALISA
Route::middleware('auth')->group(function () {

    // set session
    Route::post('/set-year-session', [AdministrasiController::class, 'setYearSession']);

    //index administrasi
    Route::get('/administrasi',  [AdministrasiController::class, 'index_administrasi']);

    //download excel notif
    Route::get('/download-notifinasi-excel',  [AdministrasiController::class, 'download_notif_excel']);

    Route::get('/notifications', [AdministrasiController::class, 'getNotifications'])->name('notifications');

    //search 
    Route::get('/administrasi/kegiatan/search', [AdministrasiController::class, 'search']);

    //Monitoring
    Route::get('/administrasi-monitoring', [AdministrasiController::class, 'monitoring']);

    //kegiatan
    Route::get('/administrasi/kegiatan/{id}', [KegiatanAdministrasiController::class, 'edit']);
    Route::put('/administrasi/kegiatan', [KegiatanAdministrasiController::class, 'update']);
    Route::resource('/administrasi/kegiatan', KegiatanAdministrasiController::class);
    Route::get('/administrasi/kegiatan/create-excel/{slug}', [KegiatanAdministrasiController::class, 'exportExcel']);
    Route::post('/administrasi/kegiatan/store_excel', [KegiatanAdministrasiController::class, 'storeExcel']);

    //akun
    Route::get('/administrasi/akun/{id}', [AkunController::class, 'edit']);
    Route::put('/administrasi/akun', [AkunController::class, 'update']);
    Route::resource('/administrasi/akun', AkunController::class);
    Route::get('/administrasi/akun/create-excel/{slug}', [AkunController::class, 'exportExcel']);
    Route::post('/administrasi/akun/destroy_excel', [AkunController::class, 'storeExcel']);

    //transaksi
    Route::get('/administrasi/transaksi/{id}', [TransaksiController::class, 'edit']);
    Route::put('/administrasi/transaksi', [TransaksiController::class, 'update']);
    Route::resource('/administrasi/transaksi', TransaksiController::class);
    Route::get('/administrasi/transaksi/create-excel/{id}', [TransaksiController::class, 'exportExcel']);
    Route::post('/administrasi/transaksi/destroy_excel', [TransaksiController::class, 'storeExcel']);

    //file
    Route::resource('/administrasi/file', FileController::class);
    Route::post('/administrasi/file/ceklist/{id}', [FileController::class, 'ceklist']);
    Route::get('/administrasi/file/create-excel/{id}', [FileController::class, 'exportExcel']);
    Route::post('/administrasi/file/store_excel', [FileController::class, 'stroreExcel']);
    Route::post('/administrasi/file/addFile', [FileController::class, 'addFile']);

    //download
    Route::get('/download-file', [FileController::class, 'download']);

    Route::get('/view-file', [FileController::class, 'viewFile'])->name('view-file');
    Route::get('/download-excel-template', [AdministrasiController::class, 'downloadTemlate']);
});

// Route Teknis Monalisa
Route::middleware('auth')->group(function () {

    Route::get('/teknis/kegiatan',  [KegiatanTeknisController::class, 'index']);
    Route::post('/teknis/kegiatan', [KegiatanTeknisController::class, 'store']);

    Route::get('/download-excel-template', [KegiatanTeknisController::class, 'downloadTemlate']);
    Route::put('/teknis/kegiatan', [KegiatanTeknisController::class, 'update']);
    Route::get('/teknis/kegiatan-edit/{id}', [KegiatanTeknisController::class, 'edit']);

    Route::delete('/teknis/kegiatan/{id}', [KegiatanTeknisController::class, 'destroy']);


    // Rumah Tangga 
    // Pemutakhiran 
    Route::get('/teknis/rumah-tangga-pemutakhiran-edit/{id}', [PemutakhiranRumahTanggaController::class, 'edit']);
    Route::put('/teknis/kegiatan/rumah-tangga/pemutakhiran', [PemutakhiranRumahTanggaController::class, 'update']);
    Route::resource('/teknis/kegiatan/rumah-tangga/pemutakhiran', PemutakhiranRumahTanggaController::class);
    Route::get('/teknis/rumah-tangga-pemutakhiran-create/{id}', [PemutakhiranRumahTanggaController::class, 'create']);
    Route::post('/teknis/rumah-tangga/pemutakhiran/create-excel', [PemutakhiranRumahTanggaController::class, 'store_excel']);


    //Pencacahan
    Route::get('/teknis/rumah-tangga-pencacahan-edit/{id}', [PencacahanRumahTanggaController::class, 'edit']);
    Route::put('/teknis/kegiatan/rumah-tangga/pencacahan', [PencacahanRumahTanggaController::class, 'update']);
    Route::get('/teknis/rumah-tangga-pencacahan-create/{id}', [PencacahanRumahTanggaController::class, 'create']);
    Route::post('/teknis/rumah-tangga/pencacahan/create-excel', [PencacahanRumahTanggaController::class, 'store_excel']);
    Route::resource('/teknis/kegiatan/rumah-tangga/pencacahan', PencacahanRumahTanggaController::class);

    // Perusahaan 
    //Pemutakhiran 
    Route::get('/teknis/perusahaan-pemutakhiran-edit/{id}', [PemutakhiranPerusahaanController::class, 'edit']);
    Route::put('/teknis/kegiatan/perusahaan/pemutakhiran', [PemutakhiranPerusahaanController::class, 'update']);
    Route::get('/teknis/perusahaan-pemutakhiran-create/{id}', [PemutakhiranPerusahaanController::class, 'create']);
    Route::post('/teknis/perusahaan/pemutakhiran/create-excel', [PemutakhiranPerusahaanController::class, 'store_excel']);
    Route::resource('/teknis/kegiatan/perusahaan/pemutakhiran', PemutakhiranPerusahaanController::class);
    Route::post('/teknis/kegiatan/perusahaan/pemutakhiran/ceklist/{id}', [PemutakhiranPerusahaanController::class, 'ceklist']);

    //Pencacahan 
    Route::get('/teknis/perusahaan-pencacahan-edit/{id}', [PencacahanPerusahaanController::class, 'edit']);
    Route::put('/teknis/kegiatan/perusahaan/pencacahan', [PencacahanPerusahaanController::class, 'update']);
    Route::get('/teknis/kegiatan/perusahaan/pencacahan-create/{id}', [PencacahanPerusahaanController::class, 'create']);
    Route::post('/teknis/perusahaan/pencacahan/create-excel', [PencacahanPerusahaanController::class, 'store_excel']);
    Route::resource('/teknis/kegiatan/perusahaan/pencacahan', PencacahanPerusahaanController::class);
    Route::post('/teknis/kegiatan/perusahaan/pencacahan/ceklist/{id}', [PencacahanPerusahaanController::class, 'ceklist']);

    //Petani
    //Pemutakhiran
    Route::get('/teknis/kegiatan/petani/pemutakhiran-edit/{id}', [PemutakhiranPetaniController::class, 'edit']);
    Route::put('/teknis/kegiatan/petani/pemutakhiran', [PemutakhiranPetaniController::class, 'update']);
    Route::get('/teknis/kegiatan/petani/pemutakhiran-create/{id}', [PemutakhiranPetaniController::class, 'create']);
    Route::post('/teknis/kegiatan/petani/pemutakhiran-create-excel', [PemutakhiranPetaniController::class, 'store_excel']);
    Route::resource('/teknis/kegiatan/petani/pemutakhiran', PemutakhiranPetaniController::class);


    //Pencacahan
    Route::get('/teknis/kegiatan/petani/pencacahan-edit/{id}', [PencacahanPetaniController::class, 'edit']);
    Route::put('/teknis/kegiatan/petani/pencacahan', [PencacahanPetaniController::class, 'update']);
    Route::get('/teknis/kegiatan/petani/pencacahan-create/{id}', [PencacahanPetaniController::class, 'create']);
    Route::post('/teknis/kegiatan/petani/pencacahan-create-excel', [PencacahanPetaniController::class, 'store_excel']);
    Route::resource('/teknis/kegiatan/petani/pencacahan', PencacahanPetaniController::class);
});

// Route Mitra Monalisa
Route::middleware(['usermitra'])->group(function () {
    Route::get('/mitra-pemutakhiran', [MitraController::class, 'index_pemutakhiran']);
    Route::post('/mitra/logout', [MitraController::class, 'logout'])->name('mitra.logout');
    Route::get('/mitra-pencacahan', [MitraController::class, 'index_pencacahan']);

    //pemutakhiran
    //rumah tangga
    Route::get('/mitra/rumah-tangga/pemutakhiran-input/{id}', [MitraController::class, 'input_pemutakhiran_rumah']);
});

// Route Autentikasi Mitra
Route::get('/mitra/login', [MitraController::class, 'login_mitra'])->name('usermitra.login');
Route::post('/mitra/login', [MitraController::class, 'autentikasi']);

require __DIR__ . '/auth.php';
