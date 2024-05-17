@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

<form action="/administrasi/transaksi/destroy_excel" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="fungsi" value="{{ $fungsi }}">
    <input type="hidden" name="kegiatan" value="{{ $kegiatan->id }}">
    <input type="hidden" name="akun_id" value="{{ $akun->id }}">
    <div id="hs-sign-out-alert" class="hs-overlay hidden size-full fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="hs-overlay-open:animate-scaleUp hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 transition transform-gpu scale-95 sm:max-w-lg sm:w-full mx-4">
                <div class="relative bg-white shadow-xl rounded-2xl dark:bg-blue-800">
                    <div class="flex justify-between items-center p-5 rounded-t-2xl border-b dark:border-gray-600">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                            Import Transaksi Excel
                        </h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700"
                            data-hs-overlay="#hs-sign-out-alert">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 text-center">
                        <label class="block w-full">
                            <span class="sr-only">Choose file</span>
                            <div class="flex flex-col items-center justify-center w-full">
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg text-sm leading-normal text-gray-500 bg-white py-10 px-4 w-full flex flex-col justify-center items-center">
                                    <svg class="w-8 h-8  mb-3 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="mb-2" id="file-name">Drag and drop your file here or click to upload</span>
                                    <input type="file" name="excel_file" class="hidden" onchange="updateFileName(this)" />
                                </div>
                            </div>
                        </label>

                        <div class="mt-6 flex justify-center gap-x-4">
                            <button type="button"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-hs-overlay="#hs-sign-out-alert">
                                Batal
                            </button>
                            <button type="submit"
                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        document.getElementById("file-name").innerText = fileName;
    }
</script>
