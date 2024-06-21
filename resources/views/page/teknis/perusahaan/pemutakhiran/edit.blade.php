<div id="hs-sign-out-alert4" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
        <div class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Edit Data
                </h3>
                <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                        data-hs-overlay="#hs-sign-out-alert4">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="px-4 pb-4 overflow-y-auto">
                <form action="/teknis/kegiatan/perusahaan/pemutakhiran/update" method="POST" class="space-y-4">
                    @method('PUT')
                    @csrf
                    <input type="hidden" value="{{$kegiatan->id}}" name="kegiatan_id">
                    <input type="hidden" id="id-edit"  name="id">
                    
                    <div class="mb-5">
                        <label for="tgl_awal-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="date" id="tgl_awal-edit" name="tgl_awal" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <div class="mb-5">
                        <label for="tgl_akhir-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal Selesai</label>
                        <input type="date" id="tgl_akhir-edit" name="tgl_akhir" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>

                    <div class="mb-5">
                        <label for="perusahaan-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Perusahaan</label>
                        <input type="text" id="perusahaan-edit" name="perusahaan"
                               class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label for="id_pml-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PML</label>
                        <input type="text" id="id_pml-edit" name="id_pml"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                    <div class="mb-5">
                        <label for="pml-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PML</label>
                        <input type="text" id="pml-edit" name="pml"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                    <div class="mb-5">
                        <label for="id_ppl-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID PPL</label>
                        <input type="text" id="id_ppl-edit" name="id_ppl"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                    <div class="mb-5">
                        <label for="ppl-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PPL</label>
                        <input type="text" id="ppl-edit" name="ppl"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label for="kode_kec-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Kecamatan</label>
                        <input type="text" id="kode_kec-edit" name="kode_kec"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                    <div class="mb-5">
                        <label for="kecamatan-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                        <input type="text" id="kecamatan-edit" name="kecamatan"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label for="kode_desa-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Desa</label>
                        <input type="text" id="kode_desa-edit" name="kode_desa"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label for="desa-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desa</label>
                        <input type="text" id="desa-edit" name="desa"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark :focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label for="kode_sbr-edit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode SBR</label>
                        <input type="text" id="kode_sbr-edit" name="kode_sbr"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-hs-overlay="#hs-sign-out-alert4">
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
            </form>
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
    function openEditModal(button) {
        const id = button.getAttribute('data-id');
        fetch(`/teknis/perusahaan-pemutakhiran-edit/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {

                if(data != 0){
                document.getElementById('id-edit').value = data.id;

                 document.getElementById('perusahaan-edit').value = data.perusahaan;
                document.getElementById('kode_kec-edit').value = data.kode_kec;
                document.getElementById('kode_desa-edit').value = data.kode_desa;
                document.getElementById('desa-edit').value = data.desa;
                document.getElementById('kecamatan-edit').value = data.kecamatan;
                document.getElementById('id_pml-edit').value = data.id_pml;
                document.getElementById('pml-edit').value = data.pml;
                document.getElementById('id_ppl-edit').value = data.id_ppl;
                document.getElementById('ppl-edit').value = data.ppl;

                document.getElementById('kode_sbr-edit').value = data.kode_sbr;
               document.getElementById('tgl_awal-edit').value = data.tgl_awal;
                document.getElementById('tgl_akhir-edit').value = data.tgl_akhir;


                document.getElementById('tgl_awal-edit').setAttribute('readonly', true); // Set the readonly attribute
                document.getElementById('tgl_akhir-edit').setAttribute('readonly', true); // Set the readonly attribute
                } else {
                    document.getElementById('tgl_awal-edit').removeAttribute('readonly'); // Remove the readonly attribute
                    document.getElementById('akhir-edit').tglAkhir.removeAttribute('readonly'); // Remove the readonly attribute
                }
                // Populate form fields with the fetched data
              
                // Open the edit form overlay
                const editForm = document.getElementById('hs-sign-out-alert4');
                editForm.classList.remove('hidden');
                editForm.classList.add('hs-overlay-open');
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }
</script>
