<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <!-- ========== HEADER ========== -->
  @include('page.administrasi.partials.header')
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
                        href="#">
                        {{ $fungsi }}
                    </a>
                    <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                    href="#">
                    Periode 1
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
                        {{$transaksi->progres}}
                    </div>
                    <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                </div>
                </div>

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
                                <p class="text-xs text-gray-600 dark:text-neutral-400">
                                    {{$transaksi->tgl_awal}} -  {{$transaksi->tgl_akhir}}
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
                                            <div class="px-3 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                        Nomor
                                                    </span>
                                                </div>
                                         </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Judul File
                                                </span>
                                            </div>
                                         </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Nama File
                                                </span>
                                            </div>
                                     </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Ukuran File
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Status
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Update
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Action
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($files as $file)
                                <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 py-3 ml-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                                viewBox="0 0 24 24"
                                                style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                                <path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z">
                                                </path>
                                                <path
                                                    d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z">
                                                </path>
                                            </svg>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                            <div class="px-3 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                        {{$file->judul}}
                                                    </span>
                                                </div>
                                            </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$file->namaFile}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$file->ukuran_file}} Mb
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        @if ($file->status == 0)
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                   Belum
                                                </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                   Sudah
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$file->updated_at->diffForHumans()}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5">
                                            <button
                                                class="bg-red-600 hover:bg-red-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(255, 252, 252, 1);transform: ;msFilter:;">
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                    </path>
                                                    <path d="M9 10h2v8H9zm4 0h2v8h-2z">
                                                    </path>
                                                </svg>
                                            </button>

                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <a href="/download-file?nama_file={{ $file->namaFile }}&transaksi={{ $transaksi->id }}&akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&periode={{ $periode->slug }}&fungsi={{ $fungsi }}">{{ $file->namaFile }}
                                        <div class="px-3 py-1.5">
                                            <button
                                                class="bg-red-600 hover:bg-red-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24"
                                                    style="fill: rgba(255, 252, 252, 1);transform: ;msFilter:;">
                                                    <path
                                                        d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                    </path>
                                                    <path d="M9 10h2v8H9zm4 0h2v8h-2z">
                                                    </path>
                                                </svg>
                                            </button>
                                            </a>
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
        <script>
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
            @if(session('success'))
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