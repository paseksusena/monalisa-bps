<div id="hs-toggle-between-modals-input-petani"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
        <div class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto
            dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Import Data
                </h3>
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                    data-hs-overlay="#hs-toggle-between-modals-input-petani">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="space-y-4 ">
                <form method="POST" action="/teknis/kegiatan/petani/pemutakhiran">
                    @csrf
                    @method('put')

                    <input type="hidden" id="kegiatan-id-petani" name="kegiatan_id">
                    <input type="hidden"  name="id" id="id-edit-petani">
                    <div class="mb-5">
                        <label for="tgl_awal"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Mulai</label>
                        <input type="date" id="tgl_awal-edit-petani" name="tgl_awal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required readonly >
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-5">
                        <label for="tgl_akhir"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Selesai</label>
                        <input type="date" id="tgl_akhir-edit-petani" name="tgl_akhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required >
                    </div>


                        <div class="mb-5">
                            <label for="nbs"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NBS
                                </label>
                            <input type="text" id="nbs-edit-petani" name="nbs"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="nama_sls"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SLS</label>
                            <input type="text" id="nama_sls-edit-petani" name="nama_sls"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>




                        <div class="mb-5">
                            <label for="id_pml"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PML</label>
                            <input type="text" id="id_pml-edit-petani" name="id_pml"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="pml"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PML</label>
                            <input type="text" id="pml-edit-petani" name="pml"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="id_ppl"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PPL</label>
                            <input type="text" id="id_ppl-edit-petani" name="id_ppl"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="ppl"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PPL</label>
                            <input type="text" id="ppl-edit-petani" name="ppl"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_kec"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Kecamatan</label>
                            <input type="text" id="kode_kec-edit-petani" name="kode_kec"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-5">
                            <label for="kecamatan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                            <input type="text" id="kecamatan-edit-petani" name="kecamatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="kode_desa"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Desa/Kelurahan</label>
                            <input type="text" id="kode_desa-edit-petani" name="kode_desa"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="desa"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desa/Kelurahan</label>
                            <input type="text" id="desa-edit-petani" name="desa"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="nks"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NKS</label>
                            <input type="text" id="nks-edit-petani" name="nks"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div class="mb-5">
                            <label for="beban_kerja"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Beban
                                Kerja</label>
                            <input type="number" id="beban_kerja-edit-petani" name="beban_kerja"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>


                         <div id="ruta-container"></div>



                        <div class="mb-5">
                            <label for="keluarga_awal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keluarga Awal</label>
                            <input type="number" id="keluarga_awal-edit-petani" name="keluarga_awal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" >
                        </div>

                        <div class="mb-5">
                            <label for="keluarga_akhir"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keluarga Akhir</label>
                            <input type="number" id="keluarga_akhir-edit-petani" name="keluarga_akhir"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" >
                        </div>



                    <!-- Button Container -->
                    <div class="sticky bottom-0 bg-white dark:bg-neutral-800 p-7 border-t dark:border-gray-700">
                        <div class="flex justify-end items-center gap-x-2">
                            <button type="button"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-hs-overlay="#hs-toggle-between-modals-input-petani">
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
function openInputPetani(button) {
    const id = button.getAttribute('data-id');

    const kegiatanId = document.getElementById('kegiatan-id-petani');
    const tglAwal = document.getElementById('tgl_awal-edit-petani');
    const tglAkhir = document.getElementById('tgl_akhir-edit-petani');
    const nbs = document.getElementById('nbs-edit-petani');
    const namaSls = document.getElementById('nama_sls-edit-petani');
    const idPml = document.getElementById('id_pml-edit-petani');
    const pml = document.getElementById('pml-edit-petani');
    const idPpl = document.getElementById('id_ppl-edit-petani');
    const ppl = document.getElementById('ppl-edit-petani');
    const kodeKec = document.getElementById('kode_kec-edit-petani');
    const kecamatan = document.getElementById('kecamatan-edit-petani');
    const kodeDesa = document.getElementById('kode_desa-edit-petani');
    const desa = document.getElementById('desa-edit-petani');
    const nks = document.getElementById('nks-edit-petani');
    const bebanKerja = document.getElementById('beban_kerja-edit-petani');
    const keluargaAwal = document.getElementById('keluarga_awal-edit-petani');
    const keluargaAkhir = document.getElementById('keluarga_akhir-edit-petani');
    const editID = document.getElementById('id-edit-petani');



    const rutaContainer = document.getElementById('ruta-container');

    fetch(`/teknis/kegiatan/petani/pemutakhiran-edit/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {


            const pemutakhiran = data.pemutakhiran;
            const rutas = data.ruta;
            console.log(pemutakhiran);

            if (pemutakhiran !== null) {

                kegiatanId.value = pemutakhiran.kegiatan_id
                editID.value = pemutakhiran.id;
                tglAwal.value = pemutakhiran.tgl_awal;
                tglAkhir.value = pemutakhiran.tgl_akhir;
                nbs.value = pemutakhiran.nbs;
                namaSls.value = pemutakhiran.nama_sls;
                idPml.value = pemutakhiran.id_pml;
                pml.value = pemutakhiran.pml;
                idPpl.value = pemutakhiran.id_ppl;
                ppl.value = pemutakhiran.ppl;
                kodeKec.value = pemutakhiran.kode_kec;
                kecamatan.value = pemutakhiran.kecamatan;
                kodeDesa.value = pemutakhiran.kode_desa;
                desa.value = pemutakhiran.desa;
                nks.value = pemutakhiran.nks;
                bebanKerja.value = pemutakhiran.beban_kerja;
                keluargaAwal.value = pemutakhiran.keluarga_awal;
                keluargaAkhir.value = pemutakhiran.keluarga_akhir;


                // Clear previous ruta fields
                rutaContainer.innerHTML = '';

                // Populate ruta fields dynamically
                rutas.forEach((ruta, index) => {
                    const rutaElement = document.createElement('div');
                    rutaElement.classList.add('mb-3');

                    const label = document.createElement('label');
                    label.setAttribute('for', `ruta-input-${index}`);
                    label.classList.add('form-label');
                    label.textContent = new Date(ruta.tanggal).toLocaleDateString('id-ID');

                    const inputHidden = document.createElement('input');
                    inputHidden.type = 'hidden';
                    inputHidden.name = 'id_ruta[]';
                    inputHidden.value = ruta.id;

                    const inputNumber = document.createElement('input');
                    inputNumber.type = 'number';
                    inputNumber.classList.add('form-control', 'bg-gray-50', 'border', 'border-gray-300', 'text-gray-900', 'text-sm', 'rounded-lg', 'focus:ring-blue-500', 'focus:border-blue-500', 'block', 'w-full', 'p-2.5', 'dark:bg-gray-700', 'dark:border-gray-600', 'dark:placeholder-gray-400', 'dark:text-white');

                    inputNumber.id = `ruta-input-${index}`;
                    inputNumber.name = 'ruta[]';
                    inputNumber.value = ruta.progres;

                    rutaElement.appendChild(label);
                    rutaElement.appendChild(inputHidden);
                    rutaElement.appendChild(inputNumber);
                    rutaContainer.appendChild(rutaElement);
                });

                }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

</script>


