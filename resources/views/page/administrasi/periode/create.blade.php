<div id="hs-slide-down-animation-modal-periode"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <form action="/administrasi/periode" method="POST">
            @csrf
            <div

            
                class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Membuat Periode Kegiatan
                    </h3>
                    <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700"
                        data-hs-overlay="#hs-slide-down-animation-modal-periode">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6.293 6.293a1 1 0 0 1 1.414 0L10 8.586l2.293-2.293a1 1 0 1 1 1.414 1.414L11.414 10l2.293 2.293a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="nama_fungsi" value={{ $fungsi }}>
                <div class="flex flex-col space-y-4 p-4 overflow-y-auto">
                    <!-- Nama Periode -->
                    <div>
                        <label for="nama"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama Periode</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tgl_awal"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Mulai</label>
                        <input type="date" id="tgl_awal" name="tgl_awal" value="{{ old('tgl_awal') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="end-date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Selesai</label>
                        <input type="date" id="end-date" name="tgl_akhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>

                    <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        <label for="periode" class="block text-sm font-medium mb-2 dark:text-white">Periode</label>
                        <select name="periode" id="periode"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:placeholder-neutral-500 dark:text-neutral-400">
                            <option value="Tahunan">Tahunan</option>
                            <option value="Semester">Semester</option>
                            <option value="Triwulan">Triwulan</option>
                            <option value="Bulan">Bulan</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-center h-full p-4 overflow-y-auto">
                    <p class="text-gray-800 dark:text-gray-400 text-center">
                        Apakah anda sudah yakin dengan periode kegiatan yang akan dibuat?
                    </p>
                </div>

                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-red-600 text-white shadow-sm hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800"
                        data-hs-overlay="#hs-slide-down-animation-modal">
                        Batal
                    </button>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Selesai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
