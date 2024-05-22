 <!-- Tombol Notifikasi -->
 <div class="hs-dropdown relative inline-flex mr-4">
    <div class="rounded-lg bg-gray-100 p-1">
        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400" type="button">
            <svg class="w-6 h-6 text-amber-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20">
                <path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/>
            </svg>
            <div data-hs-bullet="" class="absolute block w-3 h-3 bg-red-600 border-2 border-white rounded-full -top-0.5 start-2.5 dark:border-gray-900"></div>
        </button>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="dropdownNotification" class="z-20 hidden w-full max-w-lg bg-white divide-y divide-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:divide-gray-700 mx-auto lg:mr-4 lg:pr-4" aria-labelledby="dropdownNotificationButton">
    <div class="px-6 py-3 font-medium text-center text-gray-700 rounded-t-lg bg-gray-100 dark:bg-gray-800 dark:text-white">
        Notifikasi
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        <div class="py-3 px-6 font-semibold text-red-700 dark:text-red-800">Melewati Tenggat Waktu</div>
        <div class="flex items-center justify-between px-6 py-4">
            <div class="w-full">
                <div class="flex justify-between">
                    <div>
                        <div data-hs-combo-box-output-notif=""></div>
                        <div data-hs-combo-no-notification="">Tidak ada notifikasi</div>
                    </div>
                    <div class="flex items-center">
                        <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-800"  data-hs-date=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="/download-notifinasi-excel" class="block py-3 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white">
        <div class="inline-flex items-center ">
            <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
            </svg>
            Export Excel
        </div>
    </a>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
        $.ajax({
            url: '/notifications', // Ganti URL dengan URL endpoint pencarian Anda
            type: 'GET',
            
            success: function(response) {
                // Kosongkan isi dropdown sebelum menambahkan hasil pencarian baru
                var dropdown = $('[data-hs-combo-box-output-notif]');
                var tgl = $('[data-hs-date]');
                
                dropdown.empty();
                tgl.empty();

                // Loop melalui hasil pencarian dan tambahkan setiap item ke dalam dropdown
                response.forEach(function(item) {
                    dropdown.append('<div data-hs-combo-box-output-notif="{&quot;group&quot;: {&quot;name&quot;: &quot;fungsi&quot;, &quot;title&quot;: &quot;Fungsi&quot;}}" tabindex="0"><a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="' + item.url + '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path><path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path></svg><span class="text-sm font-semibold text-gray-800 dark:text-neutral-200" data-hs-combo-box-search-text="Compose an email" data-hs-combo-box-value="">' + item.name + '</span></a></div><div class="text-xs text-blue-600 dark:text-blue-500">' + item.alamat + '</div>');
                    tgl.append('<div class="text-gray-500 text-sm mb-1.5 dark:text-gray-800"  data-hs-date="{&quot;group&quot;: {&quot;name&quot;: &quot;fungsi&quot;, &quot;title&quot;: &quot;Fungsi&quot;}}"> ' + item.tgl + ' </div>')
                });

                // Tampilkan dropdown jika ada hasil pencarian, dan sembunyikan jika tidak ada
                if (response.length > 0) {
                    $('[data-hs-combo-box-output-notif]').show();
                    $('[data-hs-bullet]').show();
                    $('[data-hs-combo-no-notification]').hide();
                } else {
                    $('[data-hs-combo-box-output-notif]').hide();
                    $('[data-hs-bullet]').hide();
                    $('[data-hs-combo-no-notification]').show();

                }
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tangani kesalahan di sini
                console.error(error);
            }
        });
    
});
</script>
