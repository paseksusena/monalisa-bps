<!-- Sidebar Dashboard Mitra versi mobile -->
<nav class="fixed top-0 z-50 w-full bg-blue-950 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <!-- tombol toggle humburger -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <!-- end tombol toggle humburger -->

                <!-- logo Monalisa Putih -->
                <a class="flex ms-2 md:me-24">
                    <img class="h-10 w-58" src="{{ asset('storage/img/logo_monalisa.png') }}">
                </a>
                <!-- logo Monalisa Putih -->
            </div>

            <!-- Dropdown Pemilihan Tahun -->
            <div class="hs-dropdown flex px-2 py-2">
                <button id="hs-dropdown-default" type="button"
                    class="hs-dropdown-toggle py-2 px-2 flex items-centergap-x-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-800 shadow-sm hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                    style="min-width: 80px;">
                    {{ session('selected_year') }}
                    <svg class="hs-dropdown-open:rotate-180 size-5 ml-2" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <!-- Swicth ke tahun yang dipilih -->
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-20 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    aria-labelledby="hs-dropdown-default">
                    @foreach ($years as $tahun)
                        <div class="flex items-center  cursor-pointer gap-x-3.5 py-3 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 year-item"
                            data-year="{{ $tahun }}">{{ $tahun }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('.year-item').click(function(e) { //function pemanggilan tahun
            e.preventDefault();
            console.log('ss');
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
    });
</script>
