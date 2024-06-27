<!-- Form menambahkan pemutakhiran rumah tangga manual -->

<form id="hs-sign-out-alert1" method="POST" action="/teknis/kegiatan/rumah-tangga/pemutakhiran"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[9999] overflow-x-hidden overflow-y-auto pointer-events-none">
    @csrf
    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
        <div
            class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Import Data
                </h3>
                <!-- Tombol close -->
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                    data-hs-overlay="#hs-sign-out-alert1">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">

                <div class="space-y-4">
                    <!-- Inputan tanggal mulai -->

                    <div class="mb-5">
                        <label for="tgl_awal"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Mulai</label>
                        <input type="date" id="tgl_awal" name="tgl_awal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>
                    <!-- Inputan tanggal selesai -->

                    <div>
                        <label for="tgl_akhir"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Selesai</label>
                        <input type="date" id="tgl_akhir" name="tgl_akhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>
                    <!-- Inputan nbs -->

                    <div class="mb-5">
                        <label for="nbs" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NBS
                        </label>
                        <input type="text" id="nbs" name="nbs"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan SLS -->

                    <div class="mb-5">
                        <label for="nama_sls"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SLS</label>
                        <input type="text" id="nama_sls" name="nama_sls"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan PML -->

                    <div class="mb-5">
                        <label for="id_pml" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID
                            PML</label>
                        <input type="text" id="id_pml" name="id_pml"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    <!-- Inputan PML -->

                    <div class="mb-5">
                        <label for="pml"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PML</label>
                        <input type="text" id="pml" name="pml"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan ID PPL -->

                    <div class="mb-5">
                        <label for="id_ppl" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID
                            PPL</label>
                        <input type="text" id="id_ppl" name="id_ppl"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    <!-- Inputan Nnma PPL -->

                    <div class="mb-5">
                        <label for="ppl"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PPL</label>
                        <input type="text" id="ppl" name="ppl"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan Kode Kec -->

                    <div class="mb-5">
                        <label for="kode_kec" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                            Kecamatan</label>
                        <input type="text" id="kode_kec" name="kode_kec"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan nama kecamatan -->

                    <div class="mb-5">
                        <label for="kecamatan"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan kode desa -->

                    <div class="mb-5">
                        <label for="kode_desa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                            Desa/Kelurahan</label>
                        <input type="text" id="kode_desa" name="kode_desa"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan nama desa -->

                    <div class="mb-5">
                        <label for="desa"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desa/Kelurahan</label>
                        <input type="text" id="desa" name="desa"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan nks -->

                    <div class="mb-5">
                        <label for="nks"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NKS</label>
                        <input type="text" id="nks" name="nks"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <!-- Inputan beban kerja -->

                    <div class="mb-5">
                        <label for="beban_kerja"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Beban
                            Kerja</label>
                        <input type="number" id="beban_kerja" name="beban_kerja"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                <!-- Tombol batal -->

                <button type="button"
                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    data-hs-overlay="#hs-sign-out-alert1">
                    Batal
                </button>
                <!-- Tombol import -->

                <button type="submit"
                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Import
                </button>
            </div>
        </div>
    </div>
</form>


<!-- menampilkan erro jika ada -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<script>
    // membuka modal create rumha tangga
    function openCreateRumahTangga(button) {
        const id = button.getAttribute('data-id');

        fetch(`/teknis/rumah-tangga-pemutakhiran-create/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tglAwal = document.getElementById('tgl_awal');
                const tglAkhir = document.getElementById('tgl_akhir');
                if (data !== 0) {
                    tglAwal.value = data.tgl_awal
                    tglAkhir.value = data.tgl_akhir
                    tglAwal.setAttribute('readonly', true); // Set the readonly attribute
                    tglAkhir.setAttribute('readonly', true); // Set the readonly attribute
                } else {
                    tglAwal.removeAttribute('readonly'); // Remove the readonly attribute
                    tglAkhir.removeAttribute('readonly'); // Remove the readonly attribute
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }
</script>
