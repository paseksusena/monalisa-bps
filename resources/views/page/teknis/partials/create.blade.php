<div id="hs-focus-management-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Tambah Card
                </h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-focus-management-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form id="card-form" action="/teknis/kegiatan" method="POST">
                    @csrf
                    <input type="hidden" value="{{$fungsi}}" name="fungsi">
                    <input type="hidden" value="{{session('selected_year')}}" name="tahun">
                    <!-- Dropdown Button and Options -->
                    <label for="dropdown" class="block text-sm font-medium mt-4 mb-2 dark:text-white">Pilih Opsi</label>
                    <div class="relative inline-block w-full text-gray-700">
                        <select id="dropdown" name="kategori" class="block w-full py-3 px-4 pr-8 leading-tight border border-gray-200 rounded-lg appearance-none focus:outline-none focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                            <option value="" disabled selected>Pilih Opsi..</option>
                            <option value="direct_link">Direct Link</option>
                            <option value="rumah_tangga">Rumah Tangga</option>
                            <option value="perusahaan">Perusahaan</option>
                            <option value="petani">Petani</option>
                        </select>
                    </div>

                    <!-- Additional Inputs -->
                    <div class="mt-4">
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Judul</label>
                        <input type="text" id="judul" name="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required placeholder="Masukan judul...">
                    </div>

                    <div class="mt-4">
                        <label for="tgl_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="date" id="tgl_mulai" name="tgl_awal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <div class="mt-4">
                        <label for="tgl_akhir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Selesai</label>
                        <input type="date" id="tgl_akhir" name="tgl_akhir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <div class="mt-4">
                        <label for="link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Masukan Link</label>
                        <input type="url" id="link" name="link" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"  placeholder="Masukan link...">
                    </div>
                </form>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-focus-management-modal">
                    Close
                </button>
                <button type="submit" form="card-form" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
