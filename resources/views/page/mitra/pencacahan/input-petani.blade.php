<!-- Modal import file excel -->
<div id="hs-toggle-between-modals-input-petani"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
        <div
            class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto
            dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b shadow-sm dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Import Data
                </h3>
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                    data-hs-overlay="#hs-toggle-between-modals-input-petani">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Form requerment -->
            <div class="px-4 overflow-y-auto">
                <div class="space-y-4">
                    <!-- mengirim data inputan modal -->
                    <form method="POST" action="/mitra-pencacahan-petani">
                        @csrf
                        @method('put')
                        <input type="hidden" id="kegiatan-edit-petani" name="kegiatan_id">
                        <input type="hidden" name="id" id="id-edit-petani">
                        <!-- Tanggal Mulai -->
                        <div class="mb-5">
                            <label for="tgl_awal-edit"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                                Mulai</label>
                            <input type="date" id="tgl_awal-edit-petani" name="tgl_awal" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="tgl_akhir-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                                Selesai</label>
                            <input type="date" id="tgl_akhir-edit-petani" name="tgl_akhir" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_kec-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Kode
                                Kecamatan</label>
                            <input type="text" id="kode_kec-edit-petani" name="kode_kec" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kecamatan-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Kecamatan</label>
                            <input type="text" id="kecamatan-edit-petani" name="kecamatan" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_desa-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Kode
                                Desa</label>
                            <input type="text" id="kode_desa-edit-petani" name="kode_desa" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="desa-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Desa</label>
                            <input type="text" id="desa-edit-petani" name="desa" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="id_pml-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">ID PML</label>
                            <input type="text" id="id_pml-edit-petani" name="id_pml" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="pml-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama PML</label>
                            <input type="text" id="pml-edit-petani" name="pml" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="id_ppl-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">ID PPL</label>
                            <input type="text" id="id_ppl-edit-petani" name="id_ppl" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="ppl-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama PPL</label>
                            <input type="text" id="ppl-edit-petani" name="ppl" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="nks-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">NKS</label>
                            <input type="text" id="nks-edit-petani" name="nks" readonly
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="jenis_komoditas-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Jenis
                                Komoditas</label>
                            <input type="text" id="jenis_komoditas-edit-petani" name="jenis_komoditas"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="nama_krt-edit-petani"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama
                                KRT</label>
                            <input type="text" id="nama_krt-edit-petani" name="nama_krt"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <!-- Button Container -->
                        <div class="sticky bottom-0 bg-white dark:bg-neutral-800 p-4 border-t dark:border-gray-700">
                            <div class="flex justify-end items-center gap-x-2">
                                <button type="button"
                                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-semibold rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    data-hs-overlay="#hs-toggle-between-modals-input-petani">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-semibold rounded-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    //mengambil data dari inputan teknis
    function openInputModalPetani(button) {
        const id = button.getAttribute('data-id');

        const editID = document.getElementById('id-edit-petani');
        const kegiatan = document.getElementById('kegiatan-edit-petani');

        const tglAwal = document.getElementById('tgl_awal-edit-petani');
        const tglAkhir = document.getElementById('tgl_akhir-edit-petani');
        const kodeKec = document.getElementById('kode_kec-edit-petani');
        const kecamatan = document.getElementById('kecamatan-edit-petani');
        const kodeDesa = document.getElementById('kode_desa-edit-petani');
        const desa = document.getElementById('desa-edit-petani');
        const idPml = document.getElementById('id_pml-edit-petani');
        const pml = document.getElementById('pml-edit-petani');
        const idPpl = document.getElementById('id_ppl-edit-petani');
        const ppl = document.getElementById('ppl-edit-petani');
        const nks = document.getElementById('nks-edit-petani');
        const jenisKomoditas = document.getElementById('jenis_komoditas-edit-petani');
        const namaKrt = document.getElementById('nama_krt-edit-petani');

        fetch(`/mitra/petani/pencacahan-input/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {

                if (data !== null) {
                    editID.value = data.id;
                    kegiatan.value = data.kegiatan_id;
                    tglAwal.value = data.tgl_awal;
                    tglAkhir.value = data.tgl_akhir;
                    kodeKec.value = data.kode_kec;
                    kecamatan.value = data.kecamatan;
                    kodeDesa.value = data.kode_desa;
                    desa.value = data.desa;
                    idPml.value = data.id_pml;
                    pml.value = data.pml;
                    idPpl.value = data.id_ppl;
                    ppl.value = data.ppl;
                    nks.value = data.nks;
                    jenisKomoditas.value = data.jenis_komoditas;
                    namaKrt.value = data.nama_krt;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }
</script>
