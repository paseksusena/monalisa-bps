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
                    <!-- Tombol Tahun -->
                    <div class="hs-dropdown relative flex pl-2 pt-5 mb-3">
                        <button id="hs-dropdown-default" type="button"
                            class="hs-dropdown-toggle py-2 px-4 flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-800 shadow-sm hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                            style="min-width: 80px;">
                            {{ session('selected_year') }}
                            <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-20 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-50"
                            aria-labelledby="hs-dropdown-default">
                            @foreach ($years as $tahun)
                                <div class="flex items-center cursor-pointer gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 year-item"
                                    data-year="{{ $tahun }}">{{ $tahun }}</div>
                            @endforeach
                        </div>
                    </div>
                </nav>


                <div class="flex items-center gap-x-2">
                    <div class="hs-dropdown relative inline-flex [--trigger:hover]">
                        <button type="button"
                            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                            data-hs-overlay="#hs-focus-management-modal">
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
                <div id="basic-tabs-1" role="tabpanel" aria-labelledby="basic-tabs-item-1">
                    <div class="grid lg:grid-cols-3 gap-6">
                        @foreach ($kegiatans as $kegiatan)
                            @if ($kegiatan->kategori == 'direct_link')
                                <div
                                    class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                    <!-- Label and Dropdown button container -->
                                    <div class="absolute top-0 right-0 flex items-center space-x-2">
                                        <!-- Colored Label -->
                                        <div class="bg-blue-500 text-white px-2 py-1 rounded-bl-lg">
                                            <!-- Dropdown label button -->
                                            <div class="hs-dropdown relative inline-flex z-50">
                                                <button id="hs-dropdown-default" type="button"
                                                    class="hs-dropdown-toggle py-1 px-1 inline-flex items-center gap-x-2 text-base font-medium text-white">
                                                    DL
                                                    <svg class="hs-dropdown-open:rotate-180 size-5"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m6 9 6 6 6-6" />
                                                    </svg>
                                                </button>

                                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-30 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                                    aria-labelledby="hs-dropdown-default">
                                                    <button data-id="{{ $kegiatan->id }}"
                                                        data-hs-overlay="#hs-focus-management-modal2"
                                                        onclick="openEditModal(this)"
                                                        class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base text-blue-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-blue-400 dark:hover:bg-blue-700 dark:hover:text-blue-300 dark:focus:bg-blue-700">
                                                        Edit
                                                    </button>
                                                    <form id="delete-kegiatan-{{ $kegiatan->id }}"
                                                        action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <input type="hidden" value="{{ $kegiatan->id }}"
                                                            name="kegiatan">
                                                        <!-- Tambahkan input hidden untuk mengirimkan data yang diperlukan -->
                                                        <button onclick="confirmDelete({{ $kegiatan->id }})"
                                                            type="button"
                                                            class="flex items-center gap-x-3.5 py-1 px-3 rounded-lg text-base text-red-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5
                                        class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                        {{ $kegiatan->nama }}
                                    </h5>
                                    <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                        Tgl Pelaksanaan :
                                        <br />
                                        {{ $kegiatan->tgl_awal }} - {{ $kegiatan->tgl_akhir }}
                                    </p>

                                    <div class="flex justify-between items-center mt-4">
                                        @if ($kegiatan->link != null)
                                            <a href="{{ $kegiatan->link }}" target="_blank"
                                                class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                                Direct to web
                                                <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Checklist -->
                                        @if ($kegiatan->status == 0)
                                            <div class="flex items-center">
                                                <input type="hidden" value="{{ $kegiatan->id }}" name="id"
                                                    class="ceklist_id">
                                                <input
                                                    class="link-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                    type="checkbox" value="">
                                                <h1 class="ml-2">Selesai</h1>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <input type="hidden" value="{{ $kegiatan->id }}" name="id"
                                                    class="ceklist_id">
                                                <input
                                                    class="link-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                    type="checkbox" value="" checked>
                                                <h1 class="ml-2">Selesai</h1>

                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($kegiatan->kategori == 'rumah_tangga')
                                <div
                                    class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                    <!-- Colored Label -->
                                    <div
                                        class="absolute top-0 right-0 bg-orange-500 text-white px-2 py-1 rounded-bl-lg">
                                        <!-- Dropdown label button -->
                                        <div class="hs-dropdown relative inline-flex z-50">
                                            <button id="hs-dropdown-default" type="button"
                                                class="hs-dropdown-toggle py-1 px-1 inline-flex items-center gap-x-2 text-base font-medium text-white">
                                                RT
                                                <svg class="hs-dropdown-open:rotate-180 size-5"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            </button>

                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-30 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                                aria-labelledby="hs-dropdown-default">
                                                <button data-id="{{ $kegiatan->id }}"
                                                    data-hs-overlay="#hs-focus-management-modal2"
                                                    onclick="openEditModal(this)"
                                                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base text-blue-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-blue-400 dark:hover:bg-blue-700 dark:hover:text-blue-300 dark:focus:bg-blue-700">
                                                    Edit
                                                </button>
                                                <form id="delete-kegiatan-{{ $kegiatan->id }}"
                                                    action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="hidden" value="{{ $kegiatan->id }}"
                                                        name="kegiatan">
                                                    <!-- Tambahkan input hidden untuk mengirimkan data yang diperlukan -->
                                                    <button onclick="confirmDelete({{ $kegiatan->id }})"
                                                        type="button"
                                                        class="flex items-center gap-x-3.5 py-1 px-3 rounded-lg text-base text-red-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="/teknis/kegiatan/rumah-tangga/pemutakhiran?kegiatan={{ $kegiatan->id }}">
                                        <h5
                                            class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                            {{ $kegiatan->nama }}
                                        </h5>
                                    </a>
                                    <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                        Tgl Pelaksanaan :
                                        <br />
                                        {{ $kegiatan->tgl_awal }} - {{ $kegiatan->tgl_akhir }}
                                    </p>
                                    <!-- Progress Indicator -->
                                    <div class="flex justify-between items-center mt-4 sm:mt-0">
                                        @if ($kegiatan->link != null)
                                            <a href="{{ $kegiatan->link }}" target="_blank"
                                                class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                                Direct to web
                                                <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                                </svg>
                                            </a>
                                        @endif
                                        <div class="flex items-center bg-blue-100 rounded-full p-1 ml-auto">
                                            <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                                {{ $kegiatan->progres }}%
                                            </div>
                                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                                        </div>
                                    </div>

                                </div>
                            @elseif ($kegiatan->kategori == 'perusahaan')
                                <div
                                    class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                    <!-- Colored Label -->
                                    <div
                                        class="absolute top-0 right-0 bg-yellow-300 text-white px-2 py-1 rounded-bl-lg">
                                        <!-- Dropdown label button -->
                                        <div class="hs-dropdown relative inline-flex z-50">
                                            <button id="hs-dropdown-default" type="button"
                                                class="hs-dropdown-toggle py-1 px-1 inline-flex items-center gap-x-2 text-base font-medium text-white">
                                                PA
                                                <svg class="hs-dropdown-open:rotate-180 size-5"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-30 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                                aria-labelledby="hs-dropdown-default">
                                                <button data-id="{{ $kegiatan->id }}"
                                                    data-hs-overlay="#hs-focus-management-modal2"
                                                    onclick="openEditModal(this)"
                                                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base text-blue-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-blue-400 dark:hover:bg-blue-700 dark:hover:text-blue-300 dark:focus:bg-blue-700">
                                                    Edit
                                                </button>
                                                <form id="delete-kegiatan-{{ $kegiatan->id }}"
                                                    action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="hidden" value="{{ $kegiatan->id }}"
                                                        name="kegiatan">
                                                    <!-- Tambahkan input hidden untuk mengirimkan data yang diperlukan -->
                                                    <button onclick="confirmDelete({{ $kegiatan->id }})"
                                                        type="button"
                                                        class="flex items-center gap-x-3.5 py-1 px-3 rounded-lg text-base text-red-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="/teknis/kegiatan/perusahaan/pemutakhiran?kegiatan={{ $kegiatan->id }}">
                                        <h5
                                            class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                            {{ $kegiatan->nama }}
                                        </h5>
                                    </a>
                                    <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                        Tgl Pelaksanaan :
                                        <br />
                                        {{ $kegiatan->tgl_awal }} - {{ $kegiatan->tgl_akhir }}
                                    </p>
                                    <!-- Progress Indicator -->
                                    <div class="flex justify-between items-center mt-4 sm:mt-0">
                                        @if ($kegiatan->link != null)
                                            <a href="{{ $kegiatan->link }}" target="_blank"
                                                class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                                Direct to web
                                                <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                                </svg>
                                            </a>
                                        @endif
                                        <div class="flex items-center bg-blue-100 rounded-full p-1 ml-auto">
                                            <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                                {{ $kegiatan->progres }}%
                                            </div>
                                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($kegiatan->kategori == 'petani')
                                <div
                                    class="relative max-w-sm p-6 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                    <!-- Colored Label -->
                                    <div
                                        class="absolute top-0 right-0 bg-green-500 text-white px-2 py-1 rounded-bl-lg">
                                        <!-- Dropdown label button -->
                                        <div class="hs-dropdown relative inline-flex z-50">
                                            <button id="hs-dropdown-default" type="button"
                                                class="hs-dropdown-toggle py-1 px-1 inline-flex items-center gap-x-2 text-base font-medium text-white">
                                                PN
                                                <svg class="hs-dropdown-open:rotate-180 size-5"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            </button>

                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-30 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                                aria-labelledby="hs-dropdown-default">
                                                <button data-id="{{ $kegiatan->id }}"
                                                    data-hs-overlay="#hs-focus-management-modal2"
                                                    onclick="openEditModal(this)"
                                                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-base text-blue-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-blue-400 dark:hover:bg-blue-700 dark:hover:text-blue-300 dark:focus:bg-blue-700">
                                                    Edit
                                                </button>
                                                <form id="delete-kegiatan-{{ $kegiatan->id }}"
                                                    action="/teknis/kegiatan/{{ $kegiatan->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="hidden" value="{{ $kegiatan->id }}"
                                                        name="kegiatan">
                                                    <!-- Tambahkan input hidden untuk mengirimkan data yang diperlukan -->
                                                    <button onclick="confirmDelete({{ $kegiatan->id }})"
                                                        type="button"
                                                        class="flex items-center gap-x-3.5 py-1 px-3 rounded-lg text-base text-red-600 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="/teknis/kegiatan/petani/pemutakhiran?kegiatan={{ $kegiatan->id }}">
                                        <h5
                                            class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                            {{ $kegiatan->nama }}
                                        </h5>
                                    </a>

                                    <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                        Tgl Pelaksanaan :
                                        <br />
                                        {{ $kegiatan->tgl_awal }} - {{ $kegiatan->tgl_akhir }}
                                    </p>
                                    <!-- Progress Indicator -->
                                    <div class="flex justify-between items-center mt-4 sm:mt-0">
                                        @if ($kegiatan->link != null)
                                            <a href="{{ $kegiatan->link }}" target="_blank"
                                                class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                                Direct to web
                                                <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                                </svg>
                                            </a>
                                        @endif
                                        <div class="flex items-center bg-blue-100 rounded-full p-1 ml-auto">
                                            <div class="py-1 px-1 bg-blue-500 text-white rounded-full text-sm mr-1">
                                                {{ $kegiatan->progres }}%
                                            </div>
                                            <span class="text-gray-800 dark:text-gray-400 text-sm mr-2">Progress</span>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus kegiatan ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya, Hapus saja!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-kegiatan-' + id).submit(); // Submit the form
            }
        });
    }
</script>

<!-- AlpineJS for interactive components -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
<script>
    // Toggle tab content
    document.querySelectorAll('[data-hs-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-hs-tab');
            document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
                panel.classList.add('hidden');
            });
            document.querySelector(target).classList.remove('hidden');
        });
    });

    // Handle dropdown year selection
    document.querySelectorAll('.year-item').forEach(item => {
        item.addEventListener('click', () => {
            const selectedYear = item.getAttribute('data-year');
            fetch(`/teknis/set-year/${selectedYear}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            }).then(() => {
                location.reload();
            });
        });
    });

    $(document).ready(function() {
        $('.link-checkbox').on('change', function() {
            const id = $(this).siblings('.ceklist_id').val();
            const isChecked = $(this).prop('checked');

            $.ajax({
                url: `/teknis/kegiatan-ceklist/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    isChecked: isChecked ? 1 :
                        0 // Mengirimkan nilai 1 atau 0 tergantung status ceklis
                },
                success: function(response) {
                    console.log(response.message);
                    // Lakukan tindakan lebih lanjut jika sukses, seperti memberikan feedback kepada pengguna
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // Tangani kesalahan jika terjadi
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js">
    < />

    <
    script >
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('.year-item').click(function(e) {
                e.preventDefault();
                var year = $(this).data('year');
                $.ajax({
                    type: 'POST',
                    url: '/set-year-session', // ganti dengan URL yang sesuai di sisi server
                    data: {
                        year: year,
                        _token: csrfToken
                    },
                    success: function(response) {
                        // refresh halaman setelah berhasil
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // tangani kesalahan jika terjadi
                        console.error('Terjadi kesalahan:', error);
                    }
                });
            });
        }); <
    />
