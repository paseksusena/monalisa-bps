<!-- ========== START ========== -->
<div class="max-w-[90rem] px-2 mt-20 py-4 sm:px-6 lg:px-8 lg:py-1 mx-auto sm:max-w-[36rem] md:max-w-[48rem] lg:max-w-[72rem] xl:max-w-[90rem]">
    <!-- SearchBox -->
    <div>
        <!-- Tabs -->
    </div>
    <!-- End SearchBox -->

    <div class="flex justify-between items-center border-b border-gray-300 px-2 dark:border-gray-700">
        <!-- Tabs -->
        <nav class="flex" aria-label="Tabs" role="tablist">
            <!-- Existing tabs here -->
            <a href="/teknis/kegiatan/perusahaan/pemutakhiran?kegiatan={{$kegiatan->id}}"
                class="tab-link py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-blue-500"
                id="basic-tabs-item-1" aria-controls="basic-tabs-1" role="tab">
                Pemutakhiran
            </a>
            <a href="/teknis/kegiatan/perusahaan/pencacahan?kegiatan={{$kegiatan->id}}"
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