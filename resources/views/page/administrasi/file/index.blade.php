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
                    href="/administrasi/akun?kegiatan={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&periode={{ $periode->slug }}&fungsi={{ $fungsi }}">
                    {{ $kegiatan->nama }}
                    </a>
                    <svg class="flex-shrink-0 mx-2 overflow-visible size-4 text-gray-400 dark:text-neutral-600"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500"
                    href="/administrasi/transaksi?akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&periode={{ $periode->slug }}&fungsi={{ $fungsi }}">
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
                                            <div class="px-1 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                        Nomor
                                                    </span>
                                                </div>
                                         </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Judul File
                                                </span>
                                            </div>
                                         </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Nama File
                                                </span>
                                            </div>
                                     </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Ukuran File
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Status
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Update
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-1 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-extrabold text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    Action
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($files as $file => $doc)
                                <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$file + 1}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <div class="ps-6 py-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path>
                                                    <path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path>
                                                </svg>
                                            </div>
                                            <div class="px-3 py-3 text-start">
                                                <div class="flex items-center gap-x-3">
                                                    <span class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                        {{$doc->judul}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$doc->namaFile}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$doc->ukuran_file}} Mb
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap"> 
                                        @if ($doc->status == 0)
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                                <svg class="size-2.5 text-neutral-700" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" class="fill-yellow-600" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" class="text-yellow-300"/>
                                                  </svg>
                                                                                                                                                
                                               Belum
                                            </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="px-3 py-3 text-start">
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
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$doc->updated_at->diffForHumans()}}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <form id="delete-form-{{$doc->id}}"
                                                action="/administrasi/file/{{ $doc->id }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="hidden" value="{{ $fungsi }}" name="fungsi">
                                                    <input type="hidden" value="{{ $periode->slug }}" name="periode">
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
                                            <a href="/download-file?nama_file={{ $doc->namaFile }}&transaksi={{ $transaksi->id }}&akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&periode={{ $periode->slug }}&fungsi={{ $fungsi }}" class="text-blue-600 hover:underline">Download</a>
                                            @endif
                                            <a></a>
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