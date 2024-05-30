<!-- Tombol Notifikasi -->
<div class="hs-dropdown relative inline-flex mr-4">
    <div class="rounded-lg bg-gray-100 p-1">
        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400" type="button">
            <svg class="w-6 h-6 text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20">
                <path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/>
            </svg>
            <div data-hs-bullet="" class="hidden absolute  w-3 h-3 bg-red-600 border-2 border-white rounded-full -top-0.5 start-2.5 dark:border-gray-900"></div>
        </button>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="dropdownNotification" class="z-20 hidden w-full max-w-lg bg-white divide-y divide-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:divide-gray-700 mx-auto lg:mr-4 lg:pr-4" aria-labelledby="dropdownNotificationButton">
    <div class="flex justify-between items-center px-6 py-3 font-medium text-base text-center text-gray-700 rounded-t-lg bg-gray-100 dark:bg-gray-800 dark:text-white">
        <span>Notifikasi</span>
        <a href="/download-notifinasi-excel" class="block p-3 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
            <span title="Download Excel">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(61, 61, 61, 1);transform: ;msFilter:;">
                    <path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path>
                </svg>
            </span>
        </a>
    </div>
    <div class="py-3 px-6 font-semibold text-red-700 dark:text-red-800">Melewati Tenggat Waktu</div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
        <!-- Notification Item Container -->
        <div id="notification-items">
            <!-- Notification Items will be appended here by JavaScript -->
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '/notifications', // Ganti URL dengan URL endpoint pencarian Anda
            type: 'GET',
            success: function(response) {
                var notificationContainer = $('#notification-items');
                
                notificationContainer.empty();

                response.forEach(function(item) {
                    var notificationItem = `
                        <div class="flex items-center justify-between px-6 py-4">
                            <div class="flex flex-col w-2/3">
                                <a href="${item.url}" class="text-base font-bold text-gray-800 dark:text-neutral-200 hover:text-blue-500 dark:hover:text-blue-400">${item.name}</a>
                                <div class="text-sm text-gray-600 dark:text-gray-400">${item.alamat}</div>
                            </div>
                            <div class="flex flex-col items-end w-1/3">
                                <div class="font-medium text-gray-800 dark:text-neutral-200">${item.tgl}</div>
                            </div>
                        </div>
                    `;
                    notificationContainer.append(notificationItem);
                });

                if (response.length > 0) {
                    $('#notification-items').show();
                    $('[data-hs-combo-no-notification]').hide();
                    $('[data-hs-bullet]').show();

                } else {
                    $('#notification-items').hide();
                    $('[data-hs-combo-no-notification]').show();
                }
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>



