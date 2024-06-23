<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<x-app-layout>

    <body>

        @include('page.teknis.partials.jumbroton')
        @include('page.teknis.partials.edit')
        <!-- ========== START CARD ========== -->

        <div class="max-w-[80rem] px-4=2 py-10 sm:px-6 lg:px-8 lg:py-12 m-auto">
            <div class="flex justify-between items-center border-b border-gray-200 px-4 dark:border-gray-700">
                <!-- Tabs -->
                <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
                    <!-- Existing tabs here -->
                    <button type="button"
                        class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-400 dark:hover:text-blue-500 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 active"
                        id="basic-tabs-item-1" data-hs-tab="#basic-tabs-1" aria-controls="basic-tabs-1" role="tab">
                        Kegiatan
                    </button>
                </nav>

                <div class="flex items-center gap-x-2">
                    <div class="hs-dropdown relative inline-flex [--trigger:hover]">
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modal">
                            Tambah Card
                          </button>                          
                    </div>
                    <!-- ========== TOMBOL TAMBAH CARD ========== -->
                    @auth()
                    @include('page.teknis.partials.create')
                    @endauth
                    <!-- ========== END TAMBAH CARD ========== -->

                </div>


            </div>
            <div class="mt-4 p-5">
                <!-- ========== KEGIATAN ========== -->
                <div id="basic-tabs-1" role="tabpanel" aria-labelledby="basic-tabs-item-1">
                    <div class="grid lg:grid-cols-3 gap-6">
                    @foreach ($kegiatans as $kegiatan)
                        @if ($kegiatan->kategori == 'direct_link')
                        <div class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                            <!-- Colored Label -->
                            <div class="absolute top-0 right-0 bg-blue-500 text-white px-2 py-1 rounded-bl-lg">
                                DL
                            </div>
                        
                            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                {{$kegiatan->nama}}
                            </h5>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                Tgl Pelaksanaan :
                                <br />
                                {{$kegiatan->tgl_awal}} - {{$kegiatan->tgl_akhir}}
                            </p>
                        
                            <div class="flex justify-between items-center mt-4">
                                @if ($kegiatan->link !=  null)
                                <a href="{{$kegiatan->link}}" target="_blank"
                                    class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                    Direct to web monitoring
                                    <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                    </svg>
                                </a>
                                @endif
                        
                                <!-- Checklist -->
                                <div class="flex items-center ml-4">
                                    <input type="checkbox" id="check1" class="mr-2">
                                    <label for="check1" class="text-gray-500 dark:text-gray-400">Selesai</label>
                                </div>
                            </div>
                        
                            <!-- Button Container -->
                            <div class="flex justify-end space-x-2 absolute bottom-0 right-0 mb-2 mr-2">
                                 
                                    {{-- <button data-id="{{$kegiatan->id}}"
                                        onclick="openEditModal(this)" type="button" class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modal2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                            <path d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z"></path>
                                        </svg>
                                    </button> --}}
                                
                                <form id="delete-form" action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-400"
                                        onclick="confirmDelete()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            style="fill: rgba(232, 27, 27, 1);transform: ;msFilter:;">
                                            <path
                                                d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @elseif ($kegiatan->kategori == 'rumah_tangga')
                        <div class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                            <!-- Colored Label -->
                            <div class="absolute top-0 right-0 bg-orange-500 text-white px-2 py-1 rounded-bl-lg">
                                RT
                            </div>
                        
                            <a href="/teknis/kegiatan/rumah-tangga/pemutakhiran?kegiatan={{$kegiatan->id}}">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{$kegiatan->nama}}
                                </h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                Tgl Pelaksanaan :
                                <br />
                                {{$kegiatan->tgl_awal}} - {{$kegiatan->tgl_akhir}}
                            </p>
                            <!-- Progress Indicator -->
                            <div class="flex justify-end mt-4 sm:mt-0">
                                <div class="flex items-center bg-blue-100 rounded-full p-1">
                                    <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                        {{$kegiatan->progres}}%
                                    </div>
                                    <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                @if ($kegiatan->link !=  null)
                                <a href="{{$kegiatan->link}}" target="_blank" class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                    Direct to web monitoring
                                    <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        
                            <!-- Button Container for Delete -->
                            <div class="absolute bottom-0 right-0 mb-2 mr-2">
                                <form id="delete-form" action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-400"
                                        onclick="confirmDelete()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(232, 27, 27, 1);transform: ;msFilter:;">
                                            <path
                                                d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @elseif ($kegiatan->kategori == 'perusahaan')
                        <div class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                            <!-- Colored Label -->
                            <div class="absolute top-0 right-0 bg-yellow-300 text-white px-2 py-1 rounded-bl-lg">
                                PA
                            </div>
                        
                            <a href="/teknis/kegiatan/perusahaan/pemutakhiran?kegiatan={{$kegiatan->id}}">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{$kegiatan->nama}}
                                </h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                Tgl Pelaksanaan :
                                <br />
                                {{$kegiatan->tgl_awal}} - {{$kegiatan->tgl_akhir}}
                            </p>
                            <!-- Progress Indicator -->
                            <div class="flex justify-end mt-4 sm:mt-0">
                                <div class="flex items-center bg-blue-100 rounded-full p-1">
                                    <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                        {{$kegiatan->progres}}%
                                    </div>
                                    <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                @if ($kegiatan->link !=  null)
                                <a href="{{$kegiatan->link}}" target="_blank" class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                    Direct to web monitoring
                                    <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        
                            <!-- Button Container for Delete -->
                            <div class="absolute bottom-0 right-0 mb-2 mr-2">
                                <form id="delete-form" action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-400"
                                        onclick="confirmDelete()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(232, 27, 27, 1);transform: ;msFilter:;">
                                            <path
                                                d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @elseif ($kegiatan->kategori == 'petani')
                            <div
                                class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                <!-- Colored Label -->
                                <div class="absolute top-0 right-0 bg-green-500 text-white px-2 py-1 rounded-bl-lg">
                                    PN
                                </div>

                                <a href="/teknis/kegiatan/petani/pemutakhiran?kegiatan={{$kegiatan->id}}">
                                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                        {{$kegiatan->nama}}
                                    </h5>
                                </a>
                                <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                    Tgl Pelaksanaan :
                                    <br />
                                    {{$kegiatan->tgl_awal}} -  {{$kegiatan->tgl_akhir}}
                                </p>
                                <!-- Progress Indicator -->
                                <div class="flex justify-end mt-4 sm:mt-0">
                                    <div class="flex items-center bg-blue-100 rounded-full p-1">
                                        <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                            {{$kegiatan->progres}}%
                                        </div>
                                        <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    @if ($kegiatan->link !=  null)
                                        <a href="{{$kegiatan->link}}" target="_blank"
                                            class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                            Direct to web monitoring
                                            <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                            </svg>
                                        </a>
                                    @endif 
                                    <div class="absolute bottom-0 right-0 mb-2 mr-2">
                                        <form id="delete-form" action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-400"
                                                onclick="confirmDelete()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(232, 27, 27, 1);transform: ;msFilter:;">
                                                    <path
                                                        d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    </div>
                </div>
            </div>
        </div>
    </body>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin menghapus ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
