<!-- ========== HEADER ========== -->
<header
    class="flex flex-wrap sticky top-0 sm:justify-start sm:flex-nowrap z-50 w-full bg-cyan-950 text-sm py-3 sm:py-0">
    <nav class="relative max-w-[85rem] w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8"
        aria-label="Global">
        <div class="flex items-center justify-between">
            <a class="flex-none text-xl font-semibold text-white" href="/" aria-label="monalisa">
                <img class="h-10 w-57" src="{{asset('storage/img/logo_monalisa.png')}}" alt="logo monalisa">
            </a>
            <!-- Tombol toggle hamburger -->
            <div class="sm:hidden">
                <button type="button"
                    class="hs-collapse-toggle size-9 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-white/20 text-white hover:border-white/40 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    data-hs-collapse="#navbar-collapse-with-animation" aria-controls="navbar-collapse-with-animation"
                    aria-label="Toggle navigation">
                    <svg class="hs-collapse-open:hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                    <svg class="hs-collapse-open:block hidden flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="navbar-collapse-with-animation"
            class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:block">
            <div
                class="flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7">

                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=Umum">Umum</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=Produksi">Produksi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=Distribusi">Distribusi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=Neraca">Neraca</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=Sosial">Sosial</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/teknis/kegiatan?fungsi=IPDS">IPDS</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/administrasi">Administrasi</a>
                <a class="font-medium text-white/[.8] hover:text-white sm:py-6" href="/mitra">Mitra</a>

                <div class="flex md:order-2">
                    <div class="relative hidden md:block">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <input type="text" id="search-navbar"
                            class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search...">
                    </div>

                    <div class="relative md:hidden w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search icon</span>
                        </div>
                        <input type="text" id="search-navbar"
                            class="block w-full p-2 pl-10 pr-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search...">
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
<!-- ========== END HEADER ========== -->