<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('storage/img/icontab.png') }}" type="image/x-icon">

</head>

<body class="bg-gray-100">


    <!-- ========== HEADER ========== -->
    @include('page.administrasi.partials.search')
    <!-- ========== END HEADER ========== -->

    <!-- ========== Side Bar ========== -->
    @include('page.administrasi.partials.sidebar')
    <!-- ========== End Side Bar ========== -->

    <div class="w-full lg:ps-64">
        <div class="p-10 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Grid -->
            <div class="mt-3 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:items-center">

                <!-- Card -->
                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            UMUM
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>

                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresUmum }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_umum }} / {{ $amount_file_umum }}
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_umum }}
                    </span>

                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=Umum">
                        Selengkapnya
                    </a>
                </div>


                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            PRODUKSI
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresProduksi }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_produksi }} / {{ $amount_file_produksi }}
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_produksi }}
                    </span>

                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=Produksi">
                        Selengkapnya
                    </a>
                </div>

                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            NERACA
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresNeraca }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_neraca }} / {{ $amount_file_neraca }}
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_neraca }}
                    </span>

                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=Neraca">
                        Selengkapnya
                    </a>
                </div>


                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            DISTRIBUSI
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresDistribusi }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_distribusi }} / {{ $amount_file_distribusi }}
                    </span>
                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_distribusi }}
                    </span>
                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=Distribusi">
                        Selengkapnya
                    </a>
                </div>

                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            SOSIAL
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresSosial }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_sosial }} / {{ $amount_file_sosial }}

                    </span>
                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_sosial }}
                    </span>
                    P
                    </li>
                    </ul>
                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=Sosial">
                        Selengkapnya
                    </a>
                </div>

                <div
                    class="flex flex-col border-2 text-center shadow-xl rounded-xl p-8 bg-slate-50 dark:border-blue-700 hover:border-blue-500 hover:transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <p class="mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-2xl uppercase font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white">
                            IPDS
                        </span>
                    </p>
                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Progres</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $progresIpds }}%
                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">File Uploaded</h4>
                    <span class="mt-5 font-bold text-4xl text-gray-800 dark:text-neutral-200">
                        {{ $complete_file_ipds }} / {{ $amount_file_ipds }}

                    </span>

                    <hr class="my-4 border-gray-600 dark:border-gray-600">

                    <h4 class="font-medium text-lg text-gray-800 dark:text-neutral-200">Total Nilai Transaksi</h4>
                    <span class="mt-5 font-bold text-3xl text-gray-800 dark:text-neutral-200">
                        Rp. {{ $nilai_trans_ipds }}
                    </span>
                    <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="administrasi/kegiatan?fungsi=IPDS">
                        Selengkapnya
                    </a>
                </div>
                <!-- End Card -->
                
            </div>
            <!-- End Grid -->
        </div>
    </div>
    <!-- ========== END MAIN CONTENT ========== -->
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</html>