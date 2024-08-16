<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaksi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('storage/img/icontab.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <!-- ========== HEADER ========== -->
    @include('page.administrasi.partials.search2')
    <!-- ========== END HEADER ========== -->

    <!-- ========== Side Bar ========== -->
    @include('page.administrasi.partials.sidebar')
    <!-- ========== End Side Bar ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <!-- Content -->
    <div class="w-full  lg:ps-64">
        <div class="p-4 sm:p-6 space-y-4 sm:space-y- mb-10">
            <!-- Breadcrumb -->
            <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                aria-label="Breadcrumb">
                <ol class="flex items-center whitespace-nowrap">
                    <li class="inline-flex items-center">
                        <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                            href="/administrasi/kegiatan?fungsi={{ $fungsi }}">
                            {{ $fungsi }}
                        </a>
                        <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                            href="/administrasi/akun?kegiatan={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&fungsi={{ $fungsi }}">
                            {{ $kegiatan->nama }}
                        </a>
                        <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <a
                            class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500">
                            {{ $akun->nama }}
                        </a>
                    </li>

                </ol>
            </nav>


            <div class="iflex justify-between items-center w-full">
                <div class="flex justify-between items-center w-full">
                    <!-- Tombol tambah transaksi -->
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <button type="button"
                            class="w-full sm:w-auto py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                            data-hs-overlay="#hs-slide-down-animation-modal-folder">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            <span class="hidden sm:inline-block">Tambah Transaksi</span>
                        </button>
                        <!-- Tombol import transaksi -->
                        <button type="button"
                            class="w-full sm:w-auto py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                            data-hs-overlay="#hs-sign-out-alert">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            <span class="hidden sm:inline-block">Import Transaksi</span>
                        </button>
                    </div>


                    <!-- Mengambil halaman blade dari create dan create excel -->
                    @include('page.administrasi.transaksi.create')
                    @include('page.administrasi.transaksi.create-excel')

                    <!-- Progress dan jumlah file container -->
                    <div class="flex items-center space-x-4">
                        <!-- Jumlah file -->
                        <div class="flex items-center bg-blue-100 rounded-full p-1">
                            <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                                {{ $akun->complete_file }}/ {{ $akun->amount_file }}
                            </div>
                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Uploaded</span>
                        </div>


                        <!-- Presentase progres -->
                        <div class="flex items-center bg-blue-100 rounded-full p-1">
                            <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                                {{ $akun->progres }}%
                            </div>
                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                        </div>


                          <!-- Presentase verifikasi -->
                          <div class="flex items-center bg-blue-100 rounded-full p-1">
                            <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                                {{ $all_complete_verifikasis }}/{{ $all_total_verifikasis }}

                            </div>
                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Verifikasi</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div
                            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                            <!-- Header -->
                            <div
                                class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                        {{ $akun->nama }}
                                    </h2>
                                </div>
                                <div>
                                    <h1 class="text-base font-semibold text-gray-600">Total Nilai Transaksi</h1>
                                    <h2 class="text-xl font-bold text-gray-800 dark:text-neutral-200">
                                        Rp{{ $nilai_total_trans }}
                                    </h2>
                                </div>
                            </div>
                            <!-- End Header -->

                            <!-- Table -->
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <!-- Kolom Tabel -->
                                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2">
                                                        Nomor
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                        <!-- proses mengirimkan request untuk sorting bagian nomor kuintasi -->

                                                        <a href="{{ request()->fullUrlWithQuery(['order-no-kwt' => request('order-no-kwt') === 'asc' ? 'desc' : 'asc', 'order-nama' => null, 'order-progres' => null, 'order-nilai' => null, 'order-tanggal-akhir' => null, 'order-bulan-arsip' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            No Kuitansi
                                                            @if (request('order-no-kwt') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-no-kwt') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-4 mr-1">
                                                        <!-- proses mengirimkan request untuk sorting bagian nama transaksi -->
                                                        <a href="{{ request()->fullUrlWithQuery(['order-nama' => request('order-nama') === 'asc' ? 'desc' : 'asc', 'order-no-kwt' => null, 'order-progres' => null, 'order-nilai' => null, 'order-tanggal-akhir' => null, 'order-bulan-arsip' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            Nama Transaksi
                                                            @if (request('order-nama') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-nama') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                        <!-- proses mengirimkan request untuk sorting bagian progres -->

                                                        <a href="{{ request()->fullUrlWithQuery(['order-progres' => request('order-progres') === 'asc' ? 'desc' : 'asc', 'order-no-kwt' => null, 'order-nama' => null, 'order-nilai' => null, 'order-tanggal-akhir' => null, 'order-bulan-arsip' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            Progres
                                                            @if (request('order-progres') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-progres') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                        File Progress
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                        Verifikasi
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                        <!-- proses mengirimkan request untuk sorting bagian nilai transaksi -->

                                                        <a href="{{ request()->fullUrlWithQuery(['order-nilai' => request('order-nilai') === 'asc' ? 'desc' : 'asc', 'order-no-kwt' => null, 'order-nama' => null, 'order-progres' => null, 'order-tanggal-akhir' => null, 'order-bulan-arsip' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            Nilai Transaksi
                                                            @if (request('order-nilai') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-nilai') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                        <!-- proses mengirimkan request untuk sorting bagian tanggal akhir -->

                                                        <a href="{{ request()->fullUrlWithQuery(['order-tanggal-akhir' => request('order-tanggal-akhir') === 'asc' ? 'desc' : 'asc', 'order-no-kwt' => null, 'order-nama' => null, 'order-progres' => null, 'order-nilai' => null, 'order-bulan-arsip' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            Tanggal Akhir
                                                            @if (request('order-tanggal-akhir') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-tanggal-akhir') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                        <!-- proses mengirimkan request untuk sorting bagian bulam arsip -->

                                                        <a href="{{ request()->fullUrlWithQuery(['order-bulan-arsip' => request('order-bulan-arsip') === 'asc' ? 'desc' : 'asc', 'order-no-kwt' => null, 'order-nama' => null, 'order-progres' => null, 'order-nilai' => null, 'order-tanggal-akhir' => null]) }}"
                                                            class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            Bulan Arsip
                                                            @if (request('order-bulan-arsip') === 'asc')
                                                                <i class="fas fa-sort-up ml-2"></i>
                                                            @elseif (request('order-bulan-arsip') === 'desc')
                                                                <i class="fas fa-sort-down ml-2"></i>
                                                            @else
                                                                <i class="fas fa-sort ml-2"></i>
                                                            @endif
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                        Action
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- End Kolom Tabel -->
                                    @foreach ($transaksis as $transaksi => $trans)
                                        <!-- Perulangan data yang ada di table -->
                                        <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-1 py-3 text-start">
                                                    <div class="flex items-center gap-x-3">
                                                        <span
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                            {{ $transaksi + 1 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-1 py-3 text-start">
                                                    <div class="flex items-center gap-x-3">
                                                        <span
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                            {{ $trans->no_kwt }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="size-px whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <div class="ps-4 py-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                            height="30" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path>
                                                            <path
                                                                d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="px-3 py-3 text-start">
                                                        <div class="flex items-center gap-x-3">
                                                            <span
                                                                class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                                <a
                                                                    href="/administrasi/file?transaksi={{ $trans->id }}&akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&fungsi={{ $fungsi }}">
                                                                    {{ $trans->nama }}</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            @if ($trans->progres == 100)
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15"
                                                                height="15" viewBox="0 0 24 24"
                                                                style="fill: rgba(28, 133, 17, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->progres }}%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15"
                                                                height="15" viewBox="0 0 24 24"
                                                                style="fill: rgba(28, 133, 17, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->complete_file }}/{{ $trans->amount_file }}
                                                        </span>
                                                    </div>
                                                </td>
                                            @elseif ($trans->progres > 0 && $trans->progres < 100)
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24"
                                                                style="fill: rgb(59, 63, 13);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11H7v-2h10v2z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->progres }}%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24"
                                                                style="fill: rgb(59, 63, 13);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11H7v-2h10v2z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->complete_file }}/{{ $trans->amount_file }}
                                                        </span>
                                                    </div>
                                                </td>
                                            @elseif ($trans->progres <= 0)
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24"
                                                                style="fill: rgba(253, 1, 1, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.207 12.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->progres }}%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 mr-10">
                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24"
                                                                style="fill: rgba(253, 1, 1, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.207 12.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z">
                                                                </path>
                                                            </svg>
                                                            {{ $trans->complete_file }}/{{ $trans->amount_file }}
                                                        </span>
                                                    </div>
                                                </td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif

                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-1 py-3 text-start">
                                                    <div class="flex items-center gap-x-3">

                                                        <span
                                                            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-full
                                                        {{ $complete_verifikasis[$trans->id] == 0 ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $complete_verifikasis[$trans->id] > 0 && $complete_verifikasis[$trans->id] !== $total_verifikasis[$trans->id] ? 'bg-yellow-100' : '' }}
                                                        {{ $complete_verifikasis[$trans->id] == $total_verifikasis[$trans->id] ? 'bg-green-100 text-green-800' : '' }}

                                                        dark:bg-red-500/10">


                                                            @if ($complete_verifikasis[$trans->id] == 0)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" viewBox="0 0 24 24"
                                                                    style="fill: rgba(253, 1, 1, 1);transform: ;msFilter:;">
                                                                    <path
                                                                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.207 12.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z">

                                                                </svg>
                                                            @elseif($complete_verifikasis[$trans->id] > 0 && $complete_verifikasis[$trans->id] !== $total_verifikasis[$trans->id])
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" viewBox="0 0 24 24"
                                                                    style="fill:xrgb(59, 63, 13);transform: ;msFilter:;">
                                                                    <path
                                                                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11H7v-2h10v2z">
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="15"
                                                                    height="15" viewBox="0 0 24 24"
                                                                    style="fill: rgba(28, 133, 17, 1);transform: ;msFilter:;">
                                                                    <path
                                                                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z">
                                                                    </path>
                                                                </svg>
                                                            @endif
                                                            </path>

                                                            {{ $complete_verifikasis[$trans->id] }}/{{ $total_verifikasis[$trans->id] }}
                                                        </span>

                                                    </div>
                                                </div>
                                            </td>

                                            @if ($trans->nilai_trans > 0)
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 text-start">
                                                        <div class="flex items-center gap-x-3">
                                                            <span
                                                                class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                                Rp{{ $trans->nilai_trans }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                <td class="size-px whitespace-nowrap">
                                                    <div class="px-1 py-3 text-start">
                                                        <div class="flex items-center gap-x-3">
                                                            <span
                                                                class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-1 mr-1">
                                                                -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif

                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-6 py-3 text-start flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                        {{ \Carbon\Carbon::parse($trans->tgl_akhir)->format('d/m/Y') }}
                                                    </span>
                                                    @if (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($trans->tgl_akhir)->addDay()) && $trans->progres < 100)
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24"
                                                            style="fill: rgba(255, 13, 13, 1);">
                                                            <path
                                                                d="m17.284 3.707 1.412-1.416 3.01 3-1.413 1.417zm-10.586 0-2.99 2.999L2.29 5.294l2.99-3zM12 4c-4.879 0-9 4.121-9 9s4.121 9 9 9 9-4.121 9-9-4.121-9-9-9zm1 14h-2v-2h2v2zm0-4h-2V8h2v6z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-9 py-3 text-start">
                                                    <div class="flex items-center gap-x-3">
                                                        <span
                                                            class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-4 mr-4">
                                                            {{ $trans->bln_arsip }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- Tombol edit dan delete -->
                                            <td class="size-px whitespace-nowrap">
                                                <div class="px-3 py-1.5 flex space-x-2">
                                                    <form id="delete-form-{{ $trans->id }}"
                                                        action="/administrasi/transaksi/{{ $trans->id }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <input type="hidden" value="{{ $fungsi }}"
                                                            name="fungsi">
                                                        <input type="hidden" value="{{ $kegiatan->id }}"
                                                            name="kegiatan">
                                                        <input type="hidden" value="{{ $akun->id }}"
                                                            name="akun">
                                                        <button type="button"
                                                            onclick="confirmDelete({{ $trans->id }})"
                                                            class="bg-red-600 hover:bg-red-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24"
                                                                style="fill: rgba(255, 252, 252, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                                </path>
                                                                <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <a href="#" class="text-blue-600 hover:underline">
                                                        <button type="button" data-hs-overlay="#edit-transaksi"
                                                            data-id="{{ $trans->id }}"
                                                            onclick="openEditModal(this)"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24"
                                                                style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z">
                                                                </path>
                                                                <path
                                                                    d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </a>
                                                    @include('page.administrasi.transaksi.edit')
                                                </div>
                                            </td>

                                        </tr>
                                        <!-- Perulangan data yang ada di table -->
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Script sweetalert -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                function confirmDelete(id) { //id data untuk konfirmasi delete pada sweetalert
                    Swal.fire({
                        title: 'Apakah Anda yakin menghapus?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus saja!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                }
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif
            </script>
        </div>
    </div>
    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>

</html>
