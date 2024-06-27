<header
    class="flex flex-wrap sticky top-0 sm:justify-start sm:flex-nowrap z-[9999] w-full bg-cyan-950 text-sm py-3 sm:py-0">
    <nav class="relative max-w-[85rem] w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8"
        aria-label="Global">
        <div class="flex items-center justify-between">
            <a class="flex-none text-xl font-semibold text-white" href="/" aria-label="monalisa">
                <img class="h-10 w-57" src="{{ asset('storage/img/logo_monalisa.png') }}" alt="logo monalisa">
            </a>
            <!-- Tombol toggle hamburger -->
            <div class="sm:hidden">
                <button type="button"
                    class="hs-collapse-toggle size-9 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-white/20 text-white hover:border-white/40 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    data-hs-collapse="#navbar-collapse-with-animation" aria-controls="navbar-collapse-with-animation"
                    aria-label="Toggle navigation">
                    <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                    <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="navbar-collapse-with-animation"
            class="hs-collapse hidden transition-all duration-300 basis-full grow sm:block">
            <div
                class="flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7">

                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=Umum">Umum</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=Produksi">Produksi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=Distribusi">Distribusi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=Neraca">Neraca</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=Sosial">Sosial</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6"
                    href="/teknis/kegiatan?fungsi=IPDS">IPDS</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/administrasi">Administrasi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/mitra-pemutakhiran">Mitra</a>

                <div class="flex md:order-2">
                    <!-- SearchBox -->
                    <div class="relative flex-grow-0 w-full sm:w-[200px] me-auto"
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
                                class="py-2 ps-10 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
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


                </div>

                @guest
                    @include('layouts.partials.header.header_guest')
                @endguest
            </div>
        </div>

        @auth()
            @include('layouts.partials.header.header_auth')
        @endauth

    </nav>
</header>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            var searchValue = $(this).val();
            var selectedYear = '{{ session('selected_year') }}';
            $.ajax({
                url: '/teknis/kegiatan/search',
                type: 'GET',
                data: {
                    search: searchValue,
                    year: selectedYear
                },

                success: function(response) {
                    console.log(response); // Log the response to the console
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
                    });
                    if (response.length > 0) {
                        $('[data-hs-combo-box-output]').show();
                    } else {
                        $('[data-hs-combo-box-output]').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
