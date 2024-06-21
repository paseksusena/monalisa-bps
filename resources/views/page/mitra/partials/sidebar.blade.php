<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-24 h-screen pt-20 shadow-md transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <div class="flex flex-col justify-center items-center gap-y-2 py-4">
            <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button"
                    class=" hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex flex-col items-center
                justify-center px-5 rounded-full border border-transparent hover:bg-gray-50
                dark:hover:bg-gray-800 group">
                    <svg class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z">
                        </path>
                    </svg>
                    <span
                        class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700"
                        role="tooltip">
                        Beranda
                    </span>
                </button>
            </div>

            <form action="{{ route('mitra.logout') }}" method="POST">
                @csrf

                    <div class="hs-tooltip inline-block [--placement:right]">
                        <button  type="submit"
                            class=" hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex flex-col items-center
                    justify-center px-5 rounded-full border border-transparent hover:bg-gray-50
                    dark:hover:bg-gray-800 group"
                            data-hs-overlay="#hs-sign-out-alert">
                            <svg class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21c4.411 0 8-3.589 8-8 0-3.35-2.072-6.221-5-7.411v2.223A6 6 0 0 1 18 13c0 3.309-2.691 6-6 6s-6-2.691-6-6a5.999 5.999 0 0 1 3-5.188V5.589C6.072 6.779 4 9.65 4 13c0 4.411 3.589 8 8 8z">
                                </path>
                                <path d="M11 2h2v10h-2z"></path>
                            </svg>
                            <span
                                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700"
                                role="tooltip">
                                Keluar
                            </span>
                        </button>
                    </div>
            </form>

        </div>
</aside>
