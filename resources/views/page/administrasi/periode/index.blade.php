<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrasi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body>
  <!-- ========== HEADER ========== -->
  @include('page.administrasi.periode.search')
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
                                        <a href="/administrasi/kegiatan?periode={{ $periodeAdministrasi->slug }}&fungsi={{ $fungsi }}">
                                            {{$periodeAdministrasi->nama}}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-xs text-gray-600 dark:text-neutral-400">
                                        {{\Carbon\Carbon::parse($periodeAdministrasi->tgl_awal)->format('d/m/Y')}} -  {{\Carbon\Carbon::parse($periodeAdministrasi->tgl_akhir)->format('d/m/Y')}}
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
                                        
                                        @if ($periodeAdministrasi->progres == 100)
                                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-green-500 bg-green-600 text-white hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                            href="#">
                                            {{$periodeAdministrasi->complete_file}}/{{$periodeAdministrasi->amount_file}}
                                        </a>
                                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-green-500  bg-green-600 text-white hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                        href="#">
                                        {{$periodeAdministrasi->progres}}%
                                    </a>
                                            
                                        @else
                                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-blue-200 bg-slate-50 text-blue-500 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                        href="#">
                                        {{$periodeAdministrasi->complete_file}}/{{$periodeAdministrasi->amount_file}}
                                    </a>
                                        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border  border-blue-200 bg-slate-50 text-blue-500 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                            href="#">
                                            {{$periodeAdministrasi->progres}}%
                                        </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <!-- End Header -->
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
        
        </script>
    </div>
</div>
<!-- End Content -->
  <!-- ========== END MAIN CONTENT ========== -->
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/setSession.js') }}"></script>

