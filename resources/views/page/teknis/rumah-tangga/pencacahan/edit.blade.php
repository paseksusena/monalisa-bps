<div id="hs-sign-out-alert5"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
        <div class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Import Data
                </h3>
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                    data-hs-overlay="#hs-sign-out-alert5">
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
                    <form method="POST" action="/teknis/kegiatan/rumah-tangga/pencacahan">
                        @csrf
                        @method('put')

                        <input type="hidden" value="{{$kegiatan->id}}" name="kegiatan_id">
                        <input type="hidden" value="{{$pencacahan->id}}" name="id">
                        <div class="mb-5">
                            <label for="tgl_awal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Mulai</label>
                            <input type="date" id="tgl_awal-edit" name="tgl_awal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-5">
                            <label for="tgl_akhir"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Selesai</label>
                            <input type="date" id="tgl_akhir-edit" name="tgl_akhir"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>

                        <div class="mb-5">
                            <label for="nks"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NKS</label>
                            <input type="text" id="nks-edit" name="nks"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="id_pml"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PML</label>
                            <input type="text" id="id_pml-edit" name="id_pml"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="pml"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PML</label>
                            <input type="text" id="pml-edit" name="pml"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="id_ppl"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PPL</label>
                            <input type="text" id="id_ppl-edit" name="id_ppl"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="ppl"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PPL</label>
                            <input type="text" id="ppl-edit" name="ppl"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_kec"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Kecamatan</label>
                            <input type="text" id="kode_kec-edit" name="kode_kec"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-5">
                            <label for="kecamatan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                            <input type="text" id="kecamatan-edit" name="kecamatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_desa"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Desa/Kelurahan</label>
                            <input type="text" id="kode_desa-edit" name="kode_desa"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="desa"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desa/Kelurahan</label>
                            <input type="text" id="desa-edit" name="desa"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        @for ($i = 1; $i <= 10; $i++)
                        <div class="mb-5">
                            <label for="sampel_{{$i}}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">KRT {{$i}}</label>
                            <input type="text" id="edit-sampel_{{$i}}" name="sampel_{{$i}}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        @endfor

                        <!-- Button Container -->
                        <div class="sticky bottom-0 bg-white dark:bg-neutral-800 p-7 border-t dark:border-gray-700">
                            <div class="flex justify-end items-center gap-x-2">
                                <button type="button"
                                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    data-hs-overlay="#hs-sign-out-alert5">
                                    Batal
                                </button>
                                <button type="sumbit"
                                    class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

<script>
function openEditModal(button) {
    const id = button.getAttribute('data-id');

    const tglAwal = document.getElementById('tgl_awal-edit');
    const tglAkhir = document.getElementById('tgl_akhir-edit');
    const nks = document.getElementById('nks-edit');
    const id_pml = document.getElementById('id_pml-edit');
    const pml = document.getElementById('pml-edit');
    const id_ppl = document.getElementById('id_ppl-edit');
    const ppl = document.getElementById('ppl-edit');
    const kodeKec = document.getElementById('kode_kec-edit');
    const kecamatan = document.getElementById('kecamatan-edit');
    const kodeDesa = document.getElementById('kode_desa-edit');
    const desa = document.getElementById('desa-edit');

    fetch(`/teknis/rumah-tangga-pencacahan-edit/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data !== 0) {
                tglAwal.value = data.tgl_awal;
                tglAkhir.value = data.tgl_akhir;
                nks.value = data.nks;
                id_pml.value = data.id_pml;
                pml.value = data.pml;
                id_ppl.value = data.id_ppl;
                ppl.value = data.ppl;
                kodeKec.value = data.kode_kec;
                kecamatan.value = data.kecamatan;
                kodeDesa.value = data.kode_desa;
                desa.value = data.desa;
                for (let i = 1; i <= 10; i++) {
                    const sampelField = document.getElementById(`edit-sampel_${i}`);
                    sampelField.value = data[`sampel_${i}`];
                }

                tglAwal.setAttribute('readonly', true);
                tglAkhir.setAttribute('readonly', true);
            } else {
                tglAwal.removeAttribute('readonly', false);
                tglAkhir.removeAttribute('readonly', false);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}
</script>
