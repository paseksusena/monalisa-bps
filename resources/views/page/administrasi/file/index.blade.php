<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('storage/img/icontab.png') }}" type="image/x-icon">

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
                    href="/administrasi/kegiatan?fungsi={{$fungsi}}">
                        {{ $fungsi }}
                    </a>
                    <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                   
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                    href="/administrasi/akun?kegiatan={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&fungsi={{ $fungsi }}">
                    {{ $kegiatan->nama }}
                    </a>
                    <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                    href="/administrasi/transaksi?akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&fungsi={{ $fungsi }}">
                    {{ $akun->nama }}
                    </a>
                    <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"></path>
                    </svg>
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500">
                    {{ $transaksi->nama }}
                    </a>
                </li>

            </ol>
        </nav>

        <!-- Card -->
        <!-- Input Periode -->
        <div class="iflex justify-between items-center w-full">
            <!-- Container for Button and Progress Indicators -->
            <div class="flex justify-between items-center w-full">
                <!-- Button -->

               <!-- Progress Indicators Container -->
               <div class="flex items-center space-x-4">
                <!-- Fraction Indicator -->
                <div class="flex items-center bg-blue-100 rounded-full p-1">
                    <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                        {{$transaksi->complete_file}}/  {{$transaksi->amount_file}}
                    </div>
                    <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Uploaded</span>
                </div>


                <!-- Percentage Indicator -->
                <div class="flex items-center bg-blue-100 rounded-full p-1">
                    <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                        {{$transaksi->progres}}%
                    </div>
                    <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                </div>
                </div>
                
                <!-- Filter -->
                <div class="hs-dropdown hs-dropdown-example relative inline-flex">
                    <button id="hs-dropdown-example" type="button" class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(61, 61, 61, 1);transform: ;msFilter:;">
                            <path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path>
                        </svg>
                        {{$status}}                       
                         <svg class="hs-dropdown-open:rotate-180 size-4 text-gray-600 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>
                    
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 w-56 hidden z-10 mt-2 min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" aria-labelledby="hs-dropdown-example">
                        <a class="filter-option flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#" data-status="Semua">
                            Semua
                        </a>
                        <a class="filter-option flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#" data-status="Sudah">
                            Sudah
                        </a>
                        <a class="filter-option flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#" data-status="Belum">
                            Belum
                        </a>
                    </div>
                </div>
                
                <!-- End Filter -->

            </div>

        </div>
        @include('page.administrasi.file.create-pdf')

        <!-- Card Tahunan -->
        @include('page.administrasi.file.create-excel')

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
                                    {{$transaksi->nama}}
                                </h2>
                                <p class="text-base text-gray-600 dark:text-neutral-400">
                                    {{$transaksi->no_kwt}}
                                </p>
                            </div>

                            <div>
                                <div class="inline-flex gap-x-2">
                                    <button type="button"
                                        class="py-2 px-2 pr-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                                        data-hs-overlay="#hs-sign-out-alert">
                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        Import Excel
                                    </button>
                                
                                    <button type="button"
                                        class="py-2 px-2 pr-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                        data-hs-overlay="#hs-slide-down-animation-modal-folder">
                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        Tambah Laci
                                    </button>
                                    
                                    @include('page.administrasi.file.create')
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                       
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
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
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Nama File
                                                </span>
                                            </div>
                                     </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Penanggung Jawab
                                                </span>
                                            </div>
                                     </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Status
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Action
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Verifikasi
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Update
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    Ukuran File
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($files as $file => $doc)
                                <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-4 mr-8">
                                                    {{$file + 1}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        @if ($doc->status == 0)
                                        <div class="px-1 py-3 flex items-center gap-x-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path>
                                                <path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                {{$doc->judul}}
                                            </span>
                                        </div>
                                        @else
                                        <div class="px-1 py-3 flex items-center gap-x-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" style="fill: rgba(224, 17, 17, 1);transform: ;msFilter:;">
                                                <path d="M8.267 14.68c-.184 0-.308.018-.372.036v1.178c.076.018.171.023.302.023.479 0 .774-.242.774-.651 0-.366-.254-.586-.704-.586zm3.487.012c-.2 0-.33.018-.407.036v2.61c.077.018.201.018.313.018.817.006 1.349-.444 1.349-1.396.006-.83-.479-1.268-1.255-1.268z"></path>
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM9.498 16.19c-.309.29-.765.42-1.296.42a2.23 2.23 0 0 1-.308-.018v1.426H7v-3.936A7.558 7.558 0 0 1 8.219 14c.557 0 .953.106 1.22.319.254.202.426.533.426.923-.001.392-.131.723-.367.948zm3.807 1.355c-.42.349-1.059.515-1.84.515-.468 0-.799-.03-1.024-.06v-3.917A7.947 7.947 0 0 1 11.66 14c.757 0 1.249.136 1.633.426.415.308.675.799.675 1.504 0 .763-.279 1.29-.663 1.615zM17 14.77h-1.532v.911H16.9v.734h-1.432v1.604h-.906V14.03H17v.74zM14 9h-1V4l5 5h-4z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                {{$doc->namaFile}}
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    {{$doc->penanggung_jwb}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        @if ($doc->status == 0)
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-bold bg-red-300 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                <svg class="size-2.5 text-neutral-700" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" class="fill-red-600" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" class="text-red-300"/>
                                                  </svg>                                                                                             
                                               Belum
                                            </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-bold bg-green-200 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                   Sudah
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <form id="delete-form-{{$doc->id}}"
                                                action="/administrasi/file/{{ $doc->id }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" value="{{ $fungsi }}" name="fungsi">
                                                    <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan">
                                                    <input type="hidden" value="{{ $akun->id }}" name="akun">
                                                    <input type="hidden" value="{{ $transaksi->id }}" name="transaksi">
                                                <button type="button"
                                                    onclick="confirmDelete({{$doc->id}})"
                                                    class="bg-red-600 hover:bg-red-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                        style="fill: rgba(255, 252, 252, 1);transform: ;msFilter:;">
                                                        <path
                                                            d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                        </path>
                                                        <path d="M9 10h2v8H9zm4 0h2v8h-2z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @if ($doc->status == 1)
                                            <a href="/download-file?nama_file={{ $doc->namaFile }}&transaksi={{ $transaksi->id }}&akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&fungsi={{ $fungsi }}" class="text-blue-600 hover:underline">
                                                <button type="button"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg>
                                                </button>
                                            </a>

                                            <a href="{{ route('view-file', [
                                                'nama_file' => $doc->namaFile,
                                                'transaksi' => $transaksi->id,
                                                'akun' => $akun->id,
                                                'kegiatan' => $kegiatan->id,
                                                'fungsi' => $fungsi
                                            ]) }}" target="_blank" class="text-gray-600 hover:underline">
                                                <button type="button" class="bg-cyan-700 hover:bg-cyan-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                    </svg>
                                                </button>
                                            </a>                                            
                                            @endif
                                            <a></a> 
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        @if(auth()->check() && auth()->user()->isOrganik())
                                        <div class="px-1 py-3 text-start">
                                        @if ($doc->ceklist == 1)
                                            <div class="flex items-center">
                                                <input disabled checked id="disabled-checked-checkbox" type="checkbox" value="" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <input disabled id="disabled-checkbox" type="checkbox" value="" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        @endiF
                                        </div>  
                                        @endif

                                        @if(auth()->check() && auth()->user()->isAdmin())
                                        @if ($doc->ceklist == 0)  
                                        <div class="flex items-center">
                                            <input type="hidden" value="{{$doc->id}}" name="id" class="ceklist_id">
                                            <input class="link-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" value="">
                                            <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Verifikasi</label>
                                        </div>
                                        @else
                                        <div class="flex items-center">
                                            <input type="hidden" value="{{$doc->id}}" name="id" class="ceklist_id">
                                            <input class="link-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" value="" checked>
                                            <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Verifikasi</label>
                                        </div>                                        
                                        @endif
                                        @endif
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                   @if ($doc->status == 1)
                                                   {{$doc->update}}

                                                       @else 

                                                   @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-2 mr-8">
                                                    {{$doc->ukuran_file}} Mb
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- End Card -->
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
   $(document).ready(function() {
    $('.link-checkbox').on('change', function() {
        const id = $(this).siblings('.ceklist_id').val();
        const isChecked = $(this).prop('checked');
        console.log(isChecked);
        $.ajax({
            url: `/administrasi/file/ceklist/${id}`, 
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                isChecked: isChecked

                // isChecked: isChecked ? true : false  // Ubah nilai menjadi boolean
            },
            success: function(response) {
                console.log(response.message);
                // Tambahkan tindakan lebih lanjut di sini jika sukses
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                // Tangani kesalahan di sini
            }
        });
    });
});

//filter 

$(document).ready(function() {
    $('.filter-option').on('click', function(e) {
        e.preventDefault();
        let status = $(this).data('status');
        let transaksi = {{ $transaksi->id }};
        let akun = {{ $akun->id }};
        let kegiatan = {{ $kegiatan->id }};
        let fungsi = "{{ $fungsi }}";

        // Menambahkan parameter status dalam URL
        let url = '/administrasi/file?transaksi=' + transaksi + '&akun=' + akun + '&kegiatan=' + kegiatan + '&fungsi=' + fungsi + '&status=' + status;

        $.ajax({
            url: url,
            type: 'GET',
            data: { status: status },
            success: function(data) {
                // console.log(data);
                window.location.href = url;

            }
        });
    });
});


// Ini adalah tanda penutup untuk fungsi jQuery $(document).ready()


          function confirmDelete(id) {
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
            function validatePDFFile() {
            var fileInput = document.getElementById('file-input');
            var filePath = fileInput.value;
            var allowedExtensions = /(\.pdf)$/i;
            if (!allowedExtensions.exec(filePath)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format file tidak valid',
                    text: 'File harus berformat PDF!',
                });
                fileInput.value = '';
                return false;
            } else {
                return true;
            }
        }
            function showAlert(title, message, icon) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if(session('error'))
            showAlert('Error', '{{ session('error') }}', 'error');
            @endif
        </script>
    </div>
</div>
<!-- End Content -->
  <!-- ========== END MAIN CONTENT ========== -->
</body>
</html>