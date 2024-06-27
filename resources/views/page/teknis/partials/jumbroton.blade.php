<!-- Halaman ketika parameter berada pada fungsi maka jumbroton tampil sesuai dengan fungsi tersebut -->

<!-- ========== UMUM ========== -->

@if ($fungsi == 'Umum')
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/umum.jpg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                U M U M</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif
<!-- ========== END UMUM ========== -->

@if ($fungsi == 'Produksi')
    <!-- ========== PRODUKSI ========== -->
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/produksi.jpg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                P R O D U K S I</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif

<!-- ========== END PRODUKSI ========== -->

<!-- ========== DISTRIBUSI ========== -->
@if ($fungsi == 'Distribusi')
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/distribusi.jpg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                D I S T R I B U S I</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif
<!-- ========== END DISTRIBUSI ========== -->
<!-- ========== NERACA ========== -->
@if ($fungsi == 'Neraca')
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/neraca.jpeg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                N E R A C A</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif

<!-- ========== END NERACA ========== -->
<!-- ========== IPDS ========== -->

@if ($fungsi == 'IPDS')
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/ipds.jpeg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                I P D S</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif
<!-- ========== END IPDS ========== -->
<!-- ========== SOSIAL ========== -->

@if ($fungsi == 'Sosial')
    <section class="bg-center bg-no-repeat bg-cover bg-gray-400 bg-blend-multiply"
        style="background-image: url({{ asset('storage/img/sosial.jpeg') }})">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-28">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                S O S I A L</h1>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            </div>
        </div>
    </section>
@endif
<!-- ========== END SOSIAL ========== -->
