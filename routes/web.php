<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Administrasi\AdministrasiController;
use App\Http\Controllers\Administrasi\AkunController;
use App\Http\Controllers\Administrasi\FileController;
use App\Http\Controllers\Administrasi\KegiatanAdministrasiController;
use App\Http\Controllers\Administrasi\PeriodeAdministrasiController;
use App\Http\Controllers\Administrasi\TransaksiController;
use App\Http\Controllers\ProfileController;
use App\Models\Transaksi;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
Route::get('/umum', function () {
    return view('page.umum');
})->name('umum');
Route::get('/produksi', function () {
    return view('page.produksi');
})->name('produksi');
Route::get('/distribusi', function () {
    return view('page.distribusi');
})->name('distribusi');
Route::get('/neraca', function () {
    return view('page.neraca');
})->name('neraca');
Route::get('/sosial', function () {
    return view('page.sosial');
})->name('sosial');
Route::get('/ipds', function () {
    return view('page.ipds');
})->name('ipds');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ADMIN USER
//Autentikasi User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Autentikasi Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::resource('/admin', UserController::class);
});

Route::middleware('auth')->group(function () {
    // ADMINISTRASI

    // set session
    Route::post('/set-year-session', [AdministrasiController::class, 'setYearSession']);

    //index administrasi
    Route::get('/administrasi',  [AdministrasiController::class, 'index_administrasi']);

    //download excel notif
    Route::get('/download-notifinasi-excel',  [AdministrasiController::class, 'download_notif_excel']);



    //periode
    // Route::delete('/administrasi/periode/{periodeAdministrasi}', [PeriodeAdministrasiController::class, 'destroy']);
    // Route::get('/administrasi/periode/{periodeAdministrasi}/edit', [PeriodeAdministrasiController::class, 'edit']);
    // Route::resource('/administrasi/periode', PeriodeAdministrasiController::class);
    //notifikasi
    Route::get('/notifications', [AdministrasiController::class, 'getNotifications'])->name('notifications');


    //search 
    Route::get('/administrasi/kegiatan/search', [AdministrasiController::class, 'search']);

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
    // Route::get('/administrasi/file/{filename}', [FileController::class, 'show'])->name('pdf.show');
    // Route::get('/administrasi/file/{fungsi}/{periode}/{kegiatan}/{akun}/{transaksi}/{filename}', [FileController::class, 'show'])->name('pdf.show');

    //download
    Route::get('/download-file', [FileController::class, 'download']);

    Route::get('/view-file', [FileController::class, 'viewFile'])->name('view-file');
});


require __DIR__ . '/auth.php';
