<header
    class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-neutral-800 dark:border-neutral-700">
    <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        <div class="me-5 lg:me-0 lg:hidden">
            <!-- Logo -->
            <a class="text-start flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-blue-600 hs-accordion-active:hover:bg-transparent text-sm text-neutral-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white"
                href="home">
                MONALISA
            </a>
            <!-- End Logo -->
        </div>

        <div class="w-full flex items-center justify-between ms-auto sm:gap-x-3 sm:order-3">
            <!-- SearchBox -->
            <div class="relative flex-grow-0 w-[400px] me-auto"
                data-hs-combo-box='{
                "groupingType": "default",
                "preventSelection": true,
                "isOpenOnFocus": true,
                "groupingTitleTemplate": "<div class=\"block text-xs text-gray-500 m-3 mb-1 dark:text-neutral-500 dark:border-neutral-700\"></div>"
            }'>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <svg class="flex-shrink-0 size-4 text-gray-400 dark:text-white/60"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </div>
                    <input id="search"
                        class="py-3 ps-10 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        type="text" placeholder="Search..." value="" data-hs-combo-box-input=""
                        autocomplete="off">
                </div>

                <!-- SearchBox Dropdown -->
                <div class="absolute z-50 w-full bg-white rounded-xl shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-800"
                    style="display: none;" data-hs-combo-box-output="">
                    <div class="max-h-[500px] p-2 overflow-y-auto overflow-hidden [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"
                        data-hs-combo-box-output-items-wrapper="">
                        <!-- List of search results will be dynamically added here -->
                    </div>
                </div>
                <!-- End SearchBox Dropdown -->
            </div>
            <!-- End SearchBox -->

            <!-- Mengambil halaman blade notifikasi  -->
            @include('page.administrasi.partials.notification')

            <!-- Tombol Home -->
            <div class="hs-dropdown relative inline-flex mr-4">
                <a href="/">
                    <button id="hs-dropdown-default" type="button"
                        class="hs-dropdown-toggle py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-blue-500 text-white shadow-sm hover:bg-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:bg-blue-900 dark:text-white dark:hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path
                                d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z">
                            </path>
                        </svg>
                    </button>
                </a>
            </div>

            <!-- Tombol Tahun -->
            <div class="hs-dropdown relative inline-flex">
                <button id="hs-dropdown-default" type="button"
                    class="hs-dropdown-toggle py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-800 shadow-sm hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    {{ session('selected_year') }}
                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-20 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    aria-labelledby="hs-dropdown-default">
                    @foreach ($years as $tahun)
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            href="" data-year="{{ $tahun }}">{{ $tahun }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- script untuk melakukan pencarian data -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            // Ambil nilai input search
            var searchValue = $(this).val();
            var selectedYear = '{{ session('selected_year') }}'; // Ambil tahun dari sesi PHP
            // Lakukan AJAX request
            $.ajax({
                url: '/administrasi/kegiatan/search', // Ganti URL dengan URL endpoint pencarian Anda
                type: 'GET',
                data: {
                    search: searchValue,
                    year: selectedYear // Sertakan tahun yang dipilih dalam permintaan
                },
                success: function(response) {
                    // Kosongkan isi dropdown sebelum menambahkan hasil pencarian baru
                    var dropdown = $('[data-hs-combo-box-output-items-wrapper]');
                    dropdown.empty();

                    // Loop melalui hasil pencarian dan tambahkan setiap item ke dalam dropdown
                    response.forEach(function(item) {
                        dropdown.append(
                            '<div data-hs-combo-box-output-item="{&quot;group&quot;: {&quot;name&quot;: &quot;fungsi&quot;, &quot;title&quot;: &quot;Fungsi&quot;}}" tabindex="0"><a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="' +
                            item.url +
                            '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path><path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path></svg><span class="text-sm font-semibold text-gray-800 dark:text-neutral-200" data-hs-combo-box-search-text="" data-hs-combo-box-value="">' +
                            item.name +
                            '</span></a></div><div class="ml-3 text-sm text-gray-500">' +
                            item.alamat + '</div>');
                    });

                    // Tampilkan dropdown jika ada hasil pencarian, dan sembunyikan jika tidak ada
                    if (response.length > 0) {
                        $('[data-hs-combo-box-output]').show();
                    } else {
                        $('[data-hs-combo-box-output]').hide();
                    }
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan di sini
                    console.error(error);
                }
            });
        });
    });
</script>
