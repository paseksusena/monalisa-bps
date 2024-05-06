<form action="/administrasi/file/addFile?transaksi={{ $transaksi->id }}&akun={{ $akun->id }}&kegiatan={{ $kegiatan->id }}&periode={{ $periode->slug }}&fungsi={{ $fungsi }}"
    method="post" enctype="multipart/form-data" class="flex items-center justify-between p-4 bg-white border rounded-lg border-gray-300" onsubmit="return validatePDFFile()">
    @csrf
    <!-- Input File Multiple -->
    <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
    <input
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 mr-4"
        id="file-input" type="file" name="files[]" multiple>
    <!-- Tombol Submit -->
    <button type="submit"
        class="px-4 py-2 mr-2 text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none">
        Upload
    </button>
</form>
