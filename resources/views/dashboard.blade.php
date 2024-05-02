<x-app-layout>
    @section('jumbroton')
        <!-- ========== JUMBROTON ========== -->
        <section class="bg-center bg-no-repeat bg-cover bg-gray-700 bg-blend-multiply" style="background-image: url({{asset('storage/img/gambargif.gif')}})">
            <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-40">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">MONALISA</h1>
                <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Sistem Informasi Monitoring dan Kualitas Statistik Kabupaten Buleleng</p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                    @guest
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-sky-600 hover:bg-sky-500 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                        Log In
                        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                    @endguest
                </div>
            </div>
        </section>
        <!-- ========== END JUMBROTON ========== -->
    @endsection

    @section('cardhome')
        @include('layouts.partials.cardhome')
    @endsection

</x-app-layout>
