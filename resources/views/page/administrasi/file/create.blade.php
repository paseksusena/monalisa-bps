<!-- Error handling -->
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div id="hs-slide-down-animation-modal-folder"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <!-- Form create file -->
        <form action="/administrasi/file" method="POST"
            class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
            @csrf
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Tambahkan Laci</h3>
                <!-- Tombol tambah laci -->
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700"
                    data-hs-overlay="#hs-slide-down-animation-modal-folder">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col space-y-4 p-4 overflow-y-auto">
                <!-- Inputan hidden dari Id sebelumnya -->
                <input type="hidden" name="transaksi_id" value={{ $transaksi->id }}>
                <input type="hidden" name="akun" value={{ $akun->id }}>
                <input type="hidden" name="kegiatan" value={{ $kegiatan->id }}>
                <input type="hidden" name="fungsi" value={{ $fungsi }}>
                <!-- Input untuk Nama Kegiatan -->
                <div>
                    <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama
                        Laci</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        required>
                    @error('judul')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <!-- Input untuk Penanggung jawab -->
                <div>
                    <label for="penanggung_jwb"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Penanggung Jawab</label>
                    <input type="text" id="penanggung_jwb" name="penanggung_jwb" value="{{ old('penanggung_jwb') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        required>
                    @error('penanggung_jwb')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <!-- Tombol batal dan simpan-->
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                <button type="button"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-red-600 text-white shadow-sm hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800"
                    data-hs-overlay="#hs-slide-down-animation-modal-folder">Batal</button>
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Selesai</button>
            </div>
        </form>
        <!-- End Form create file -->
    </div>
</div>
