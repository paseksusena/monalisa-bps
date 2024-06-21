<!-- ========== START ========== -->
<div class="max-w-[90rem] px-2 mt-20 py-4 sm:px-6 lg:px-8 lg:py-1 mx-auto sm:max-w-[36rem] md:max-w-[48rem] lg:max-w-[72rem] xl:max-w-[90rem]">
    <!-- SearchBox -->
    <div>
        <!-- Tabs -->
        <!-- SearchBox -->
        <div class="static w-full sm:w-64 mb-4 sm:mb-0">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                    <svg class="flex-shrink-0 size-4 text-gray-400 dark:text-white/60"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                <input
                    class="py-2 ps-10 pe-4 block w-full shadow-sm border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none bg-gray-50 dark:text-neutral-400 dark:placeholder-neutral-500"
                    type="text" placeholder="Search" value="" data-hs-combo-box-input="">
            </div>
        </div>
        <!-- End SearchBox -->
    </div>
    <!-- End SearchBox -->

    <div class="flex justify-between items-center border-b border-gray-300 px-2 dark:border-gray-700">
        <!-- Tabs -->
        <nav class="flex" aria-label="Tabs" role="tablist">
            <!-- Existing tabs here -->
            <a href="/teknis/kegiatan/petani/pemutakhiran?kegiatan={{$kegiatan->id}}"
                class="tab-link py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-blue-500"
                id="basic-tabs-item-1" aria-controls="basic-tabs-1" role="tab">
                Pemutakhiran
            </a>
            <a href="/teknis/kegiatan/petani/pencacahan?kegiatan={{$kegiatan->id}}"
                class="tab-link py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-blue-500"
                id="basic-tabs-item-2" aria-controls="basic-tabs-2" role="tab">
                Pencacahan
            </a>
        </nav>
    </div>
</div>
<!-- End Container -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the current URL
        const currentUrl = window.location.href;

        // Get all the tab links
        const tabLinks = document.querySelectorAll('.tab-link');

        // Loop through each tab link
        tabLinks.forEach(function(link) {
            // If the current URL includes the href of the link, add the active class
            if (currentUrl.includes(link.getAttribute('href'))) {
                link.classList.add('font-semibold', 'border-blue-600', 'text-blue-600');
                link.classList.remove('text-gray-500');
            }
        });
    });
    </script>

    <style>
    .tab-link {
        transition: color 0.3s ease;
    }

    .tab-link.active {
        font-weight: 800;
        border-color: #2563eb; /* Blue color */
        color: #2563eb; /* Blue color */
    }
    </style>
