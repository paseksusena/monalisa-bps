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
                    <a class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:text-neutral-500 dark:hover:text-blue-500 dark:focus:text-blue-500">
                        {{ $fungsi }}
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
                <button
                    class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-full border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                    data-hs-overlay="#hs-slide-down-animation-modal-periode">
                    Buat Periode
                </button>

                @include('page.administrasi.periode.create')

                {{-- <!-- Progress Indicators Container -->
                @foreach ($periode as $periodeProgres)
                <div class="flex items-center space-x-4">
                    @if ($periodeProgres->progres !== null)
                    <!-- Fraction Indicator -->
                    <div class="flex items-center bg-blue-100 rounded-full p-1">
                        <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                            {{$periodeProgres->complete_file}}/ {{$periodeProgres->amount_file}}
                        </div>
                        <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Uploaded</span>
                    </div>

                    <!-- Percentage Indicator -->
                    <div class="flex items-center bg-blue-100 rounded-full p-1">
                        <div class="py-1.5 px-1.5 bg-blue-500 text-white rounded-full text-sm mr-1">
                            {{$periodeProgres->progres}}%
                        </div>
                        <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                    </div>
                    @else
                    <div></div>
                    <div></div>
                    @endif
                </div>
                @endforeach --}}

            </div>

        </div>

        <!-- Card Tahunan -->

        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">

                    @foreach ($periode as $periodeAdministrasi)
                    <div
                        class="bg-white border border-gray-200 mb-6 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    {{$periodeAdministrasi->nama}}
                                </h2>
                                <p class="text-xs text-gray-600 dark:text-neutral-400">
                                    {{$periodeAdministrasi->tgl_awal}} -  {{$periodeAdministrasi->tgl_akhir}}
                                </p>
                                <h2 class="text-xl mt-2 font-normal text-blue-700 dark:text-neutral-200">
                                    {{$periodeAdministrasi->periode}}
                                </h2>
                                
                            </div>                                  

                            <div>
                                <div class="inline-flex gap-x-2">
                                    <div class="hs-dropdown relative inline-flex">
                                        <button id="hs-dropdown-custom-icon-trigger" type="button" class="hs-dropdown-toggle flex justify-center items-center size-9 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                          <svg class="flex-none size-4 text-gray-600 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                        </button>
                                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-20 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700" aria-labelledby="hs-dropdown-custom-icon-trigger">
                                            <form id="delete-form-{{$periodeAdministrasi->id}}" action="/administrasi/periode/{{ $periodeAdministrasi->slug }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $fungsi }}" name="fungsi">
                                                    <button  onclick="confirmDelete({{$periodeAdministrasi->id}})" type="button" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base text-red-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-red-400 dark:hover:bg-delete-700 dark:hover:text-red-300 dark:focus:bg-red-700" href="#">
                                                        Delete
                                                    </button>
                                            </form>
                                        </div>
                                      </div>
                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-blue-200 bg-slate-50 text-blue-500 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                        href="#">
                                        {{$periodeAdministrasi->complete_file}}/{{$periodeAdministrasi->amount_file}}
                                    </a>
                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-blue-200 bg-slate-50 text-blue-500 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                        href="#">
                                        {{$periodeAdministrasi->progres}}%
                                    </a>
                                    <button type="button" value="{{$periodeAdministrasi->id}}" id="periode_id_excel_{{$periodeAdministrasi->id}}"
                                        class="py-2 px-2 pr-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                                        data-hs-overlay="#hs-sign-out-alert">
                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        Import
                                    </button>
                                    <button type="button" value="{{$periodeAdministrasi->id}}" id="periode_id_{{$periodeAdministrasi->id}}"
                                        class="py-2 px-2 pr-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                        data-hs-overlay="#hs-slide-down-animation-modal-folder">
                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        Kegiatan
                                    </button>
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->
                    
                        <!-- Table -->
                        @foreach ($periodeAdministrasi->kegiatanAdministrasi as $kegiatanAdministrasi)
                        @include('page.administrasi.kegiatan.create-excel')
                        @include('page.administrasi.kegiatan.create')


                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        {{-- <td>{{$periodeAdministrasi->nama}}</td> --}}
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
                                        <a href="/administrasi/akun?kegiatan={{ $kegiatanAdministrasi->id }}&periode={{ $periodeAdministrasi->slug }}&fungsi={{ $fungsi }}">
                                        <div class="px-3 py-3 text-start">
                                            <div class="flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium text-gray-800 dark:text-neutral-200 ml-3 mr-32">
                                                    {{$kegiatanAdministrasi->nama}}
                                                </span>
                                            </div>
                                        </div>
                                     </a>
                                    </td>

                                    @if ($kegiatanAdministrasi->progres !== null)
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3 mr-10">
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    {{$kegiatanAdministrasi->progres}}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-6 py-3 mr-10">
                                                <span
                                                    class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    {{ $kegiatanAdministrasi->complete_file }}/{{ $kegiatanAdministrasi->amount_file }}
                                                </span>
                                            </div>
                                        </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3 mr-10">
                                            <span class="text-sm text-gray-500 dark:text-neutral-500">
                                                {{$kegiatanAdministrasi->tgl_awal}} - {{$kegiatanAdministrasi->tgl_akhir}}</span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5">
                                            <form id="delete-form-{{$kegiatanAdministrasi->id}}"
                                                action="/administrasi/kegiatan/{{ $kegiatanAdministrasi->id }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $fungsi }}" name="fungsi">
                                                <input type="hidden" value="{{ $periodeAdministrasi->slug }}" name="periode">
                                                <input type="hidden" value="{{ $kegiatanAdministrasi->id }}" name="kegiatan">
                                                <button type="button"
                                                    onclick="confirmDelete({{$kegiatanAdministrasi->id}})"
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
                                        </div>
                                    </td>                                    
                                </tr>

                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    @endforeach

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



            @foreach ($periode as $periodeAdministrasi)
        document.getElementById('periode_id_{{$periodeAdministrasi->id}}').addEventListener('click', function () {
            
            
            let periode = document.querySelector("#periodeId");
            periode.value = {{$periodeAdministrasi->id}};
            // console.log({{$periodeAdministrasi->id}}); 


        });
    @endforeach


    @foreach ($periode as $periodeAdministrasi)
        document.getElementById('periode_id_excel_{{$periodeAdministrasi->id}}').addEventListener('click', function () {
            
            
            let periodeExcel = document.querySelector("#periodeIdExcel");
            periodeExcel.value = {{$periodeAdministrasi->id}};
            // console.log({{$periodeAdministrasi->id}}); 

        });
    @endforeach


    

        
        </script>
    </div>
</div>
<!-- End Content -->
  <!-- ========== END MAIN CONTENT ========== -->
</body>
</html>