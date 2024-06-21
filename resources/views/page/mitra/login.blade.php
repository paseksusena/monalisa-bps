<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mitra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<html class="h-full">

<body class="bg-gray-50 dark:bg-gray-950 w-full">
    <!-- ========== HEADER ========== -->
    <nav class="fixed top-0 z-50 w-full bg-blue-950 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <a class="flex ms-2 md:me-24">
                    <img class="h-10 w-58" src="{{ asset('storage/img/logo_monalisa.png') }}">
                </a>
            </div>
        </div>
    </nav>
    <!-- ========== END HEADER ========== -->

    <main class="max-w-md mx-auto mt-40 p-6">
        <!-- Selamat Datang -->
        <div class="text-center mb-4">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Selamat Datang<br> Mitra</h1>
        </div>
        <!-- End Selamat Datang -->

        <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-md dark:bg-gray-700 dark:border-gray-600">
            <div class="p-4 sm:p-10">

                <!-- Form -->
                <form action="/mitra/login" method="POST">
                    @csrf
                    <div class="grid gap-y-4">

                        <!-- Form Group -->
                        <div>
                            <label for="ppl_id" class="block text-sm mb-1 dark:text-white">Masukan ID Mitra</label>
                            <div class="relative">
                                <input type="text" id="ppl_id" name="ppl_id"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                    required aria-describedby="ppl_id-error">

                                <!-- Display Session Error -->
                                @if (session('error'))
                                    <p class="text-red-500 mt-1 text-sm">
                                        {{ session('error') }}
                                    </p>
                                @endif

                            </div>
                            <p class="hidden text-xs text-red-600 mt-2" id="ppl_id-error">Masukan ID yang valid</p>
                        </div>
                        <!-- End Form Group -->

                        <button type="submit"
                            class="w-auto py-2 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Masuk
                        </button>
                    </div>
                </form>
                <!-- End Form -->

            </div>
        </div>
    </main>
</body>

</html>
