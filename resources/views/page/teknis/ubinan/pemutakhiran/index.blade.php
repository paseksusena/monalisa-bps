<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="./node_modules/apexcharts/dist/apexcharts.css">
    <link rel="shortcut icon" href="http://192.168.2.115:8080/storage/img/icontab.png" type="image/x-icon">
</head>

<body class="bg-slate-50">

    @include('layouts.partials.header')

    @include('page.teknis.partials.tab-header')

    <div
        class="max-w-[90rem] px-2 pt-4 py-4 sm:px-6 lg:px-8 lg:py-4 mx-auto sm:max-w-[36rem] md:max-w-[48rem] lg:max-w-[72rem] xl:max-w-[90rem] ">
        <!-- ========== Pemutakhiran ========== -->
        <div id="basic-tabs-1" role="tabpanel" aria-labelledby="basic-tabs-item-1">
            <!-- TABEL -->
            <div class="grid lg:grid-cols-1 gap-1 bg-gray-100 shadow-lg p-4 rounded-lg overflow-x-auto mb-10">

                <div class="pt-2 flex flex-col">
                    <div class="flex items-center justify-between">
                        <h1 class="font-semibold text-black sm:text-xl md:text-2xl lg:text-xl">
                            Perusahaan Test
                        </h1>
                        <!-- Tombol Import Data excel -->
                        <div class="text-center">
                            <button type="button"
                                class="py-2 px-4 inline-flex items-center gap-x-1 text-sm font-medium rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                data-hs-overlay="#hs-sign-out-alert1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                                    <path
                                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z">
                                    </path>
                                </svg>
                                Data
                            </button>
                            <!-- End Tombol Data -->

                            <button type="button"
                                class="py-2 px-4 inline-flex items-center gap-x-1 text-sm font-medium rounded-xl border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                                data-hs-overlay="#hs-sign-out-alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                                    <path
                                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z">
                                    </path>
                                </svg>
                                Data Excel
                            </button>
                            
                        </div>
                    </div>

                    <div class="pb-2">
                        <p class="text-black text-xs font-medium">22/07/2024 - 01/07/2024</p>
                    </div>

                    <div class="flex flex-col pt-1">
                        <div class="-m-1.5 overflow-x-auto">
                            <div
                                class="p-1.5 min-w-full inline-block align-middle rounded-lg overflow-hidden bg-white ">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 rounded-lg dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr class="border-b-2 border-gray-300">
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    ID SPR</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Nama Perusahaan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Nama Kecamatan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Kode Kecamatan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Desa</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Kode Desa</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Kode SBR</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                                    Ceklist</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                    Action
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-700 dark:hover:bg-gray-700">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    004B</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    PT. Perusahan </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    Kalibuk</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    123242</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    123242</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    91891281</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    121928912</td>
                                                <td>
                                                    <div class="flex ml-9">
                                                        <input type="checkbox"
                                                            class="shrink-0 mt-0.5 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                            id="hs-default-checkbox">
                                                        <label for="hs-default-checkbox"
                                                            class="text-sm text-gray-500 ms-3 dark:text-neutral-400"></label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3 flex justify-center items-center space-x-2">
                                                    <a href="#" class="text-blue-600 hover:underline">
                                                        <button type="button" data-hs-overlay="#hs-sign-out-alert4" onclick="openEditModal(this)"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                                                                <path d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z"></path>
                                                                <path d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z"></path>
                                                            </svg>
                                                        </button>
                                                    </a>
                                                
                                                    <form id="#" action="#" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" onclick="confirmDelete()"
                                                            class="bg-red-600 hover:bg-red-700 text-white p-1 rounded focus:outline-none focus:shadow-outline">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 252, 252, 1);">
                                                                <path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path>
                                                                <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>                                                                                          
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data excel-->
                @include('page.teknis.perusahaan.pemutakhiran.create-excel')

                <!-- Data Manual-->
                @include('page.teknis.perusahaan.pemutakhiran.create')

                 <!-- Modal Edit-->
                @include('page.teknis.perusahaan.pemutakhiran.edit')
            </div>
        </div>
    </div>
</body>

</html>