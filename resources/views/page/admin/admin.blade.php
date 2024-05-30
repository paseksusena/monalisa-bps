<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<html>

<body class="bg-gray-50 dark:bg-gray-700 w-full">
    
    @include('page.admin.partials.sidebar')
    @include('page.admin.partials.navbar')

    
    <div class="p-4 mr-4 sm:ml-28">
        <!-- ========== Start ========== -->
        <div class="flex flex-col">
            <div class="mt-20 mb-3 flex justify-between">
                <span class="font-bold text-lg">
                    SEMUA USER
                </span>
                <div>
                    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modaladmin">
                        Tambah User
                    </button>
                </div>
                @include('page.admin.users.create')
            </div>
            <div class="flex flex-col px-2 mb-8">
                <div class="-m-1.5 overflow-x-auto shadow-md ">
                    <div class="min-w-full inline-block align-middle">
                        <div class="border rounded-sm overflow-hidden bg-white">
                            <table class="min-w-full divide-y ">
                                <thead class="bg-gray-200 dark:bg-gray-900">
                                    <tr class="border-y border-x border-gray-200">
                                        <th scope="col" class="px-6 py-3 text-start text-sm font-sans text-gray-500 uppercase dark:text-gray-400">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-sm font-sans text-gray-500 uppercase dark:text-gray-400">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-sm font-sans text-gray-500 uppercase dark:text-gray-400">
                                            Nama
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-sm font-sans text-gray-500 uppercase dark:text-gray-400">
                                            Otoritas
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-end text-sm font-sans text-gray-500 uppercase dark:text-gray-400">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                            
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($users as $key => $user)
                                    <tr>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            @if($user->role === 'admin')
                                            Admin
                                            @elseif($user->role === 'organik')
                                            Organik
                                            @elseif($user->role === 'anorganik')
                                            Anorganik
                                            @endif
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-end text-sm font-medium">
                                            <button type="button"
                                                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modaleditadmin">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                                    style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                    <path
                                                        d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z">
                                                    </path>
                                                    <path
                                                        d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                            @include('page.admin.users.edit')

                                             <!-- Tombol Hapus -->
                                             <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="deleteForm{{ $user->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete('{{ $user->id }}')"
                                                    class="py-1 px-1 inline-flex items-center gap-x-2 text-sm font-semibold rounded border border-transparent bg-red-600 text-white hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                                        style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                        <path
                                                            d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
                                                        </path>
                                                        <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                    </svg>
                                                </button>
                                            </form>                                            
                                            <!-- End Tombol Hapus -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                            <script>
                                function confirmDelete(userId) {
                                    if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
                                        document.getElementById('deleteForm' + userId).submit();
                                    }
                                }
                            
                                @if(session('success'))
                                    Swal.fire({
                                        icon: 'success',
                                        title: '{{ session('success') }}',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                @endif
                            </script>
                                                       
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========== end ========== -->
        </div>
    </div>

    <!-- ========== Start Modal ========== -->
    <div id="hs-vertically-centered-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div
                class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                <div class="flex justify-between items-center py-3 px-4 shadow-sm border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        TAMBAH USER
                    </h3>
                    <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                        data-hs-overlay="#hs-vertically-centered-modal">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end gap-x-2 pt-5 mx-4 mb-4">
                    <button type="button"
                        class="py-2 px-2 inline-flex items-center gap-x-1 text-sm font-semibold rounded-xl border border-transparent  bg-green-500 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-overlay="#hs-vertically-centered-modal" data-hs-overlay-close="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path
                                d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z">
                            </path>
                        </svg>
                        <p class="text-sm font-semibold">Simpan</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== end Modal========== -->

    <!-- ========== Start Modal ========== -->

    <div id="hs-toggle-between-modals-first-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
            <div
                class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div class="flex justify-between py-3 px-4 border-b shadow-sm dark:border-gray-700">
                    <h3 class="font-bold ml-5 text-gray-800 dark:text-white">
                        USER
                    </h3>

                    <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700"
                        data-hs-overlay="#hs-toggle-between-modals-first-modal" data-hs-overlay-close="">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Form -->
                <div class="p-4 overflow-y-auto">
                    <div class="space-y-4">
                        <form class="mx-5 pt-4">
                            <div class="mb-4">
                                <label for="text"
                                    class=" block text-xs mb-2 font-semibold text-gray-900 dark:text-white">
                                    NO
                                </label>
                                <input type="text" id="text" disabled
                                    class="bg-transparent py-2 px-4 block w-full rounded-lg border-gray-200 appearance-none dark:text-white dark:border-gray-700"
                                    placeholder="1" required />
                            </div>

                            <div class="mb-4">
                                <label for="text"
                                    class="block mb-2 text-xs font-semibold text-gray-900 dark:text-white">
                                    ID
                                </label>
                                <input type="text" id="text" disabled
                                    class="bg-transparent py-2 px-4 block w-full rounded-lg border-gray-200 appearance-none dark:text-white dark:border-gray-700"
                                    placeholder="001245" required />

                            </div>

                            <div class="mb-4">
                                <label for="text"
                                    class="block mb-2 text-xs font-semibold text-gray-900 dark:text-white">
                                    NAMA
                                </label>
                                <input type="text" id="text" disabled
                                    class="bg-transparent py-2 px-4 block w-full rounded-lg border-gray-200 appearance-none dark:text-white dark:border-gray-700"
                                    placeholder="Koamang Arnold" required />

                            </div>

                            <div class="mb-4">
                                <label for="text"
                                    class="block  mb-2 text-xs font-semibold text-gray-900 dark:text-white">
                                    OTORITAS
                                </label>
                                <input type="text" id="text" disabled
                                    class="bg-transparent py-2 px-4 block w-full rounded-lg border-gray-200 gappearance-none dark:text-white dark:border-gray-700"
                                    placeholder="Organik" required />

                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
                <div class="flex justify-end gap-x-2 pb-4 px-4">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text- font-semibold rounded-xl border border-transparent shadow-lg bg-blue-600  text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-overlay="#hs-toggle-between-modals-second-modal">
                        <svg xmlns=" http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path
                                d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z">
                            </path>
                            <path
                                d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z">
                            </path>
                        </svg>
                        <p class="text-sm font-semibold">Ubah</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="hs-toggle-between-modals-second-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)]">
            <div
                class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div class="flex justify-between items-center py-3 px-4 shadow-sm border-b-2 dark:border-gray-700">
                    <h3 class="font-bold ml-5 text-gray-800 dark:text-white">
                        EDIT USER
                    </h3>
                    <button type="button"
                        class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700"
                        data-hs-overlay="#hs-toggle-between-modals-second-modal" data-hs-overlay-close="">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <div class="space-y-4 ">
                        <!-- Form -->
                        <form>
                            <div class=" grid gap-y-2 px-5 pt-4">
                                <!-- Nama -->
                                <div>
                                    <label for="text" class="block text-sm mb-2 dark:text-white">Nama</label>
                                    <div class="relative">
                                        <input type="text" id="text" name="text"
                                            class="py-2 px-4 block w-full border-gray-200 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                            required aria-describedby="email-error">
                                    </div>
                                    <!-- End Nama -->

                                    <!-- Username -->
                                    <div>
                                        <label for="text"
                                            class="block text-sm mb-2 mt-4 dark:text-white">Username</label>
                                        <div class="relative">
                                            <input type="text" id="text" name="text"
                                                class="py-2 px-4 block w-full border-gray-200 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                required aria-describedby="email-error">
                                        </div>
                                        <p class="hidden text-xs text-red-600 mt-2" id="email-error">Masukan
                                            Username
                                            Yang Valid
                                        </p>
                                    </div>
                                    <!-- End Username-->

                                    <!-- Otoritas -->
                                    <div class="mt-4 relative z-10">
                                        <label for="password"
                                            class="block text-sm mb-2 dark:text-white">Otoritas</label>
                                        <div class="hs-dropdown relative">
                                            <!-- Tombol dropdown -->
                                            <button id="hs-dropdown-default" type="button"
                                                class="hs-dropdown-toggle py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-500 shadow-sm hover:bg-gray-50 w-full disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800">
                                                <span>Pilih otoritas</span>
                                                <svg class="hs-dropdown-open:rotate-180 size-4 ml-auto"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu items -->
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[calc(100% + 2rem)] bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                                aria-labelledby="hs-dropdown-default">
                                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                                    href="#">
                                                    Administrator
                                                </a>
                                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                                    href="#">
                                                    Organik
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Otoritas -->

                                    <!-- Password -->
                                    <div class="relative z-0">
                                        <label for="password"
                                            class="block text-sm mb-2 mt-4 dark:text-white">Password</label>
                                        <div class="relative">
                                            <input type="password" id="password" name="password"
                                                class="py-2 px-4 block w-full border-gray-200 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                required aria-describedby="password-error">
                                            <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                                <svg class="size-5 text-red-500" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="hidden text-xs text-red-600 mt-2" id="password-error">8+
                                            characters
                                            required
                                        </p>
                                    </div>

                                    <div class="relative z-0">
                                        <label for="confirm-password"
                                            class="block text-sm mb-2 mt-4 dark:text-white">Confirm
                                            Password</label>
                                        <div class="relative">
                                            <input type="password" id="confirm-password" name="confirm-password"
                                                class="py-2 px-4 block w-full border-gray-200 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                required aria-describedby="confirm-password-error">
                                            <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                                <svg class="size-5 text-red-500" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="hidden text-xs text-red-600 mt-2" id="confirm-password-error">
                                            Password
                                            does
                                            not match the password</p>
                                    </div>
                                    <!-- End Password -->
                                </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
                <div class="flex justify-end gap-x-2 pt-5">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-xl border border-transparent shadow-lg bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-overlay="#hs-toggle-between-modals-first-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path
                                d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11h-5v4l-5-5 5-5v4h5v2z">
                            </path>
                        </svg>
                        <p class="text-sm font-semibold">Kembali</p>
                    </button>
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-xl border border-transparent shadow-lg bg-green-500 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-overlay="#hs-toggle-between-modals-second-modal" data-hs-overlay-close="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path
                                d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z">
                            </path>
                        </svg>
                        <p class="text-sm font-semibold">Simpan</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== end Modal========== -->
</body>

</html>
