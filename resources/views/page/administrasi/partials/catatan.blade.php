<!-- Tombol Notifikasi -->
<div class="hs-dropdown relative inline-flex ml-2 mr-4">
    <div class="rounded-lg bg-gray-100 p-1">
        <button id="dropdownNotification2Button" data-dropdown-toggle="dropdownNotification2"
            class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400"
            type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                style="fill: rgba(58, 130, 255, 1);transform: ;msFilter:;">
                <path
                    d="M19 4h-3V2h-2v2h-4V2H8v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm-7 10H7v-2h5v2zm5-4H7V8h10v2z">
                </path>
            </svg>
        </button>
    </div>
</div>

<!-- Modal catatan -->
<div id="dropdownNotification2"
    class="z-20 hidden w-full max-w-lg bg-white divide-y divide-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:divide-gray-700 mx-auto lg:mr-4 lg:pr-4"
    aria-labelledby="dropdownNotification2Button">
    <div
        class="flex justify-between items-center px-6 py-3 font-medium text-base text-center text-gray-700 rounded-t-lg bg-gray-100 dark:bg-gray-800 dark:text-white">
        <span>Catatan</span>
    </div>
    <div id="notification-container" class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
        <!-- Catatan akan dimuat di sini secara dinamis -->
    </div>
</div>

<!-- script untuk menampilkan notifikasi secara asinkronus menggunakan Jquery Ajax -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dropdownNotification2Button').on('click', function() {
            $.ajax({
                url: "{{ route('get.catatan') }}",
                type: 'GET',
                success: function(data) {
                    $('#notification-container')
                        .empty(); // Bersihkan container sebelum menambahkan data baru

                    if (data.length > 0) {
                        let hasCatatan = false;

                        data.forEach(function(item) {
                            if (item
                                .catatan
                            ) { // Hanya tampilkan jika catatan tidak null atau kosong
                                $('#notification-container').append(
                                    `<div class="flex items-center justify-between px-6 py-4">
                                    <div class="flex flex-col w-2/3">
                                        <a href="${item.url}" class="text-base font-bold text-gray-800 dark:text-neutral-200 hover:text-blue-500 dark:hover:text-blue-400">${item.name}</a>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">${item.alamat}</div>
                                    </div>
                                    <div class="flex flex-col items-end w-1/3">
                                        <div class="font-medium text-gray-800 dark:text-neutral-200">${item.catatan}</div>
                                    </div>
                                </div>`
                                );
                                hasCatatan = true;
                            }
                        });

                        if (!
                            hasCatatan
                        ) { // Jika tidak ada catatan sama sekali, tambahkan pesan ini
                            $('#notification-container').append(
                                `<div class="flex items-center justify-center px-6 py-4">
                                <span class="text-gray-600 dark:text-gray-400">Tidak ada catatan hari ini</span>
                            </div>`
                            );
                        }
                    } else {
                        // Jika data kosong, tambahkan pesan "Tidak ada catatan hari ini"
                        $('#notification-container').append(
                            `<div class="flex items-center justify-center px-6 py-4">
                            <span class="text-gray-600 dark:text-gray-400">Tidak ada catatan hari ini</span>
                        </div>`
                        );
                    }

                    $('#dropdownNotification2').show(); // Selalu tampilkan modal
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
