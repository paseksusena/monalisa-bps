<!-- Tab header workspace -->
<div class="pt-20 mr-7 lg:ml-32 ml-3 sm:ml-32 border-b border-gray-300 dark:border-gray-800">
    <nav class="flex space-x-1 overflow-x-auto" aria-label="Tabs" role="tablist">
        <!-- Tab 1 dengan kondisi perubahan warna -->
        <button type="button"
            class="tab-button py-4 px-1 inline-flex items-center gap-x-2 border-b-2 @if ('pemutakhiran' == $active) border-blue-600 text-blue-600 @else border-transparent text-gray-500 @endif font-semibold hover:text-blue-600 focus:outline-none dark:text-neutral-400 dark:hover:text-blue-500"
            id="tabs-with-underline-item-1" aria-controls="tabs-with-underline-1" role="tab">
            <a href="/mitra-pemutakhiran">
                Pemutakhiran
            </a>
        </button>

        <!-- Tab 1 dengan kondisi perubahan warna -->
        <button type="button"
            class="tab-button py-4 px-1 inline-flex items-center gap-x-2 border-b-2 @if ('pencacahan' == $active) border-blue-600 text-blue-600 @else border-transparent text-gray-500 @endif font-semibold hover:text-blue-600 focus:outline-none dark:text-gray-400 dark:hover:text-blue-500"
            id="tabs-with-underline-item-2" aria-controls="tabs-with-underline-2" role="tab">
            <a href="/mitra-pencacahan">
                Pencacahan
            </a>
        </button>
    </nav>
</div>
