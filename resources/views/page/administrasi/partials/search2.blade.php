<header
    class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-neutral-800 dark:border-neutral-700">
    <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        <div class="me-5 lg:me-0 lg:hidden">
            <!-- humberger button -->
            <button id="hamburger-icon" class="lg:hidden block text-blue-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <div class="w-full flex items-center justify-between ms-auto sm:gap-x-3 sm:order-3">
            <!-- SearchBox -->
            <div class="relative flex-grow-0 w-full sm:w-[400px] me-auto"
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

            <!-- Tombol Download Template-->
            <div class="hs-dropdown relative inline-flex ml-4">
                <div class="rounded-lg bg-gray-100 p-1">
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400"
                        type="button">
                        <svg class="w-6 h-6 text-lime-500 dark:text-green-300" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5h7.586l-.293.293a1 1 0 0 0 1.414 1.414l2-2a1 1 0 0 0 0-1.414l-2-2a1 1 0 0 0-1.414 1.414l.293.293H4V9h5a2 2 0 0 0 2-2Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Dropdown menu template excel -->
            <div id="dropdown"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="/download-excel-template?template=Template_kegiatan"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: rgba(12, 226, 105, 1);transform: ;msFilter:;">
                                <path
                                    d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z">
                                </path>
                            </svg>
                            <span class="ml-2">Format Kegiatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/download-excel-template?template=Template_akun"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: rgba(12, 226, 105, 1);transform: ;msFilter:;">
                                <path
                                    d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z">
                                </path>
                            </svg>
                            <span class="ml-2">Format Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="/download-excel-template?template=Template_transaksi"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: rgba(12, 226, 105, 1);transform: ;msFilter:;">
                                <path
                                    d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z">
                                </path>
                            </svg>
                            <span class="ml-2">Format Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="/download-excel-template?template=Template_file"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: rgba(12, 226, 105, 1);transform: ;msFilter:;">
                                <path
                                    d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z">
                                </path>
                            </svg>
                            <span class="ml-2">Format File</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End Tombol Download -->
            <!-- End SearchBox -->

            <!-- mengambil blade dari notifikasi -->
            @include('page.administrasi.partials.notification')
            @include('page.administrasi.partials.catatan')
            <!-- style css terpisah pada tombol home -->
            <style>
                @media (max-width: 640px) {
                    .hide-on-mobile {
                        display: none;
                    }
                }
            </style>

            <!-- Tombol logout-->
            <div class="relative inline-flex mr-4 hide-on-mobile">
                <button id="logout-button" type="button"
                    class="py-1 px-2 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                        <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                        <path
                            d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- form logout mengarah ke route logout-->
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                @csrf
            </form>

            <!-- SweetAlert JavaScript -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('logout-button').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Apakah Anda ingin logout?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, logout',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                });
            </script>

        </div>
    </nav>
</header>

<!-- script jquery Ajax dan pencarian data-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() { //input function search
            var searchValue = $(this).val();
            var selectedYear = '{{ session('selected_year') }}'; //mengambil sesi tahun
            $.ajax({
                url: '/administrasi/kegiatan/search', //url Ajax untuk search
                type: 'GET', //method
                data: {
                    search: searchValue,
                    year: selectedYear
                },
                success: function(
                    response
                ) { //function data tampil saat search yang mencakup url item, nama item dan alamat item.
                    var dropdown = $('[data-hs-combo-box-output-items-wrapper]');
                    dropdown.empty();
                    response.forEach(function(item) {
                        dropdown.append(
                            '<div data-hs-combo-box-output-item="{&quot;group&quot;: {&quot;name&quot;: &quot;fungsi&quot;, &quot;title&quot;: &quot;Fungsi&quot;}}" tabindex="0"><a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="' +
                            item.url +
                            '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path><path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path></svg><span class="text-sm font-semibold text-gray-800 dark:text-neutral-200" data-hs-combo-box-search-text="" data-hs-combo-box-value="">' +
                            item.name +
                            '</span></a></div><div class="ml-3 text-sm text-gray-500">' +
                            item.alamat + '</div>');
                    }); //data tampil ketika kondisi data > 0
                    if (response.length > 0) {
                        $('[data-hs-combo-box-output]').show();
                    } else {
                        $('[data-hs-combo-box-output]').hide();
                    }
                },
                //handling error
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
