<!-- Modal create excel pencacahan -->
<div id="hs-sign-out-alert2"
    class="hs-overlay hidden size-full fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen">
        <div
            class="hs-overlay-open:animate-scaleUp hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 transition transform-gpu scale-95 sm:max-w-lg sm:w-full mx-4">
            <div class="relative bg-white shadow-xl rounded-2xl dark:bg-blue-800">
                <div class="flex justify-between items-center p-5 rounded-t-2xl border-b dark:border-gray-600">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                        Import Data
                    </h3>
                    <!-- tombol close -->
                    <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                        data-hs-overlay="#hs-sign-out-alert2">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <form class="flex flex-col space-y-4 p-4 overflow-y-auto"
                    action="/teknis/rumah-tangga/pencacahan/create-excel" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                    <div>
                        <!-- Input tanggal mulai -->
                        <label for="tgl_awal-excel"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Mulai</label>
                        <input type="date" id="tgl_awal-excel" name="tgl_awal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>

                    <!-- Input tanggal selesai -->
                    <div>
                        <label for="tgl_akhir-excel"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanggal
                            Selesai</label>
                        <input type="date" id="tgl_akhir-excel" name="tgl_akhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-500 mb-5">
                            Silakan pilih file Excel yang ingin Anda import.
                        </p>
                        <div class="flex justify-center items-center mt-4">
                            <label class="block w-full">
                                <span class="sr-only">Choose file</span>
                                <div class="flex flex-col items-center justify-center w-full">
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg text-sm leading-normal text-gray-500 bg-white py-10 px-4 w-full flex flex-col justify-center items-center">
                                        <svg class="w-8 h-8 mb-3 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span id="file-name" class="mb-2">Drag and drop your file here or click to
                                            upload</span>
                                        <!-- input create excel -->
                                        <input type="file" name="excel_file" class="hidden"
                                            onchange="updateFileName(this)" />
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="mt-6 flex justify-center gap-x-4">
                            <!--Tombol batal  -->
                            <button type="button"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-hs-overlay="#hs-sign-out-alert2">
                                Batal
                            </button>
                            <!--Tombol Import  -->

                            <button type="submit"
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


<script>
    // function open modal create 
    function openCreateRumahTanggaExcel(button) {
        console.log(button);
        const id = button.getAttribute('data-id');

        // mecari tgl awal dan akhir dari pencacahan yang sudah dibuat sebelumnya
        fetch(`/teknis/rumah-tangga-pencacahan-create/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tglAwal = document.getElementById('tgl_awal-excel');
                const tglAkhir = document.getElementById('tgl_akhir-excel');
                // jika ditemukan tambahkan value pada tgl awal dan akhir. 
                if (data !== 0) {
                    tglAwal.value = data.tgl_awal;
                    tglAkhir.value = data.tgl_akhir;
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

    // menampilkan nama excel
    function updateFileName(input) {
        var fileName = input.files[0].name;
        document.getElementById("file-name").innerText = fileName;
    }
</script>
