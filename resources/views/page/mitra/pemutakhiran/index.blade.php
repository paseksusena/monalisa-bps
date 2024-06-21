<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mitra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<html>

<body class="bg-gray-50 dark:bg-gray-400 w-full">
    @include('page.mitra.partials.navbar')
    @include('page.mitra.partials.sidebar')
    <h1 class="ml-[100px] ">
        Nama Mitra: {{ Auth::guard('usermitra')->user()->name }}
    </h1>
    @include('page.mitra.partials.tab')


    <div class="bg-white h-auto max-w-[75%] mx-auto p-3">
        {{-- ======================START RUMAH TANGGA======================  --}}


        <div class="p-3">

            <div class=" ml-0 flex justify-between mb-2 mt-5">
                <span class="font-bold text-2xl">
                    RUMAH TANGGA
                </span>
            </div>

            @foreach ($kegiatanPemutakhiranRumahTanggas as $kegiatanPemutakhiranRumahTangga)
                @if ($kegiatanPemutakhiranRumahTangga->pemutakhiranRumahTangga->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id)->isEmpty())
                @else
                    <div class="mb-10">
                        <div class=" ml-0 flex justify-between">
                            <span class="font-bold text-2xl">
                                {{ $kegiatanPemutakhiranRumahTangga->nama }}

                            </span>
                        </div>
                        <div class="mt-2">
                            {{ \Carbon\Carbon::parse($kegiatanPemutakhiranRumahTangga->pemutakhiranRumahTangga->first()->tgl_awal)->locale('id')->translatedFormat('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse($kegiatanPemutakhiranRumahTangga->pemutakhiranRumahTangga->first()->tgl_akhir)->locale('id')->translatedFormat('d M Y') }}
                        </div>


                        <div class="flex flex-col  mb-10">
                            <div class="-m-1.5 overflow-x-auto">
                                <div class="pt-3 min-w-full inline-block align-middle">
                                    <div class="border rounded-sm overflow-hidden ">
                                        <table class="min-w-full divide-y rounded-sm">
                                            <thead class="bg-gray-200 dark:bg-gray-900">
                                                <tr class="border-y border-x border-gray-300">
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase ">
                                                        No</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase ">
                                                        Aksi</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Penyelesaian Ruta</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase ">
                                                        ID Kegiatan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        NBS dan SLS</th>

                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        ID PML</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        PML</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        ID PPL</th>
                                                    <th scope="col"
                                                        class="px-6
                                            py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        PPL</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Kode Kec</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Kecamatan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Kode Desa/Kelurahan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Desa/Kelurahan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        NBS</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        NKS</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Nama SLS</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        Beban Kerja</th>

                                                    @foreach ($kegiatanPemutakhiranRumahTangga->pemutakhiranRumahTangga->first()->rutaRumahTangga ?? [] as $ruta)
                                                        <th scope="col"
                                                            class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                            @if ($ruta->tanggal)
                                                                {{ \Carbon\Carbon::parse($ruta->tanggal)->format('d/m/Y') }}
                                                            @else
                                                                Tanggal Kosong
                                                            @endif
                                                        </th>
                                                    @endforeach



                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        KK Awal</th>

                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        KK Akhir</th>

                                                </tr>
                                            </thead>
                                            <tbody class="border-y border-x border-gray-300">
                                                @foreach ($kegiatanPemutakhiranRumahTangga->pemutakhiranRumahTangga->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id) as $pemutakhiranRumahTangga)
                                                    <tr class="border-b border-gray-200">
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $loop->iteration }}</td>
                                                        <td
                                                            class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <button type="button"
                                                                data-id="{{ $pemutakhiranRumahTangga->id }}"
                                                                onclick="openInputRumahTangga(this)"
                                                                class="py-1 px-1 inline-flex items-center gap-x-2 rounded border border-transparent bg-blue-600  text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                                                data-hs-overlay="#hs-toggle-between-modals-pemutakhiran-rumah-tangga">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                                    height="19" viewBox="0 0 24 24"
                                                                    style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                                    <path
                                                                        d="M14 12c-1.095 0-2-.905-2-2 0-.354.103-.683.268-.973C12.178 9.02 12.092 9 12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-.092-.02-.178-.027-.268-.29.165-.619.268-.973.268z">
                                                                    </path>
                                                                    <path
                                                                        d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold dark:text-gray-200">

                                                            {{ $pemutakhiranRumahTangga->penyelesaian_ruta }}%</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->id }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->nbs }} &
                                                            {{ $pemutakhiranRumahTangga->nama_sls }}</td>


                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->id_pml }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->pml }}</td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->id_ppl }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->ppl }}</td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->kode_kec }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->kecamatan }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->kode_desa }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->desa }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->nbs }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->nama_sls }}</td>


                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->nks }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->beban_kerja }}</td>

                                                        @foreach ($pemutakhiranRumahTangga->rutaRumahTangga as $ruta)
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">

                                                                {{ $ruta->progres }}
                                                            </td>
                                                        @endforeach
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->keluarga_awal }}</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            {{ $pemutakhiranRumahTangga->keluarga_akhir }}</td>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
            @endforeach

        </div>
        {{-- ======================END RUMAH TANGGA======================  --}}


        {{-- ======================START PETANI======================  --}}

        <div class="p-3 mt-8">
            <div class="mt-10 flex justify-between">
                <span class="font-bold text-2xl">
                    PETANI
                </span>
            </div>

            @foreach ($kegiatanPemutakhiranPetanis as $kegiatanPemutakhiranPetani)
                @if ($kegiatanPemutakhiranPetani->pemutakhiranPetani->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id)->isEmpty())
                @else
                    <div class="flex justify-between">
                        <span class="font-bold text-2xl">
                            {{ $kegiatanPemutakhiranPetani->nama }}
                        </span>
                    </div>
                    <div class="mt-2">
                        {{ \Carbon\Carbon::parse($kegiatanPemutakhiranPetani->pemutakhiranPetani->first()->tgl_awal)->locale('id')->translatedFormat('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($kegiatanPemutakhiranPetani->pemutakhiranPetani->first()->tgl_akhir)->locale('id')->translatedFormat('d M Y') }}
                    </div>
                    <div class="flex flex-col shadow-md mb-10">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="pt-3 min-w-full inline-block align-middle">
                                <div class="border rounded-sm overflow-hidden bg-white">
                                    <table class="min-w-full divide-y">
                                        <thead class="bg-gray-200 dark:bg-gray-900">
                                            <tr class="border-y border-x border-gray-300">
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    No</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Aksi</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Penyelesaian Ruta</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID Kegiatan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    NBS dan SLS</th>

                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID PML</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    PML</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID PPL</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    PPL</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Kode Kec</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Kecamatan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Kode Desa/Kelurahan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Desa/Kelurahan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    NBS</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    NKS</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Nama SLS</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Beban Kerja</th>

                                                @foreach ($kegiatanPemutakhiranPetani->pemutakhiranPetani->first()->rutaPetani ?? [] as $ruta)
                                                    <th scope="col"
                                                        class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                        @if ($ruta->tanggal)
                                                            {{ \Carbon\Carbon::parse($ruta->tanggal)->format('d/m/Y') }}
                                                        @else
                                                            Tanggal Kosong
                                                        @endif
                                                    </th>
                                                @endforeach


                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KK Awal</th>

                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KK Akhir</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Kegiatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-y border-x border-gray-300">
                                            @foreach ($kegiatanPemutakhiranPetani->pemutakhiranPetani->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id) as $pemutakhiranPetani)
                                                <tr class="border-b border-gray-200">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $loop->iteration }}</td>
                                                    <td
                                                        class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <button type="button" onclick="openInputPetani(this)"
                                                            data-id="{{ $pemutakhiranPetani->id }}"
                                                            class="py-1 px-1 inline-flex items-center gap-x-2 rounded border border-transparent bg-blue-600  text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                                            data-hs-overlay="#hs-toggle-between-modals-input-petani">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                                height="19" viewBox="0 0 24 24"
                                                                style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M14 12c-1.095 0-2-.905-2-2 0-.354.103-.683.268-.973C12.178 9.02 12.092 9 12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-.092-.02-.178-.027-.268-.29.165-.619.268-.973.268z">
                                                                </path>
                                                                <path
                                                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold dark:text-gray-200">

                                                        {{ $pemutakhiranPetani->penyelesaian_ruta }}%</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->id }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->nbs }} &
                                                        {{ $pemutakhiranPetani->nama_sls }}</td>


                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->id_pml }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->pml }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->id_ppl }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->ppl }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->kode_kec }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->kecamatan }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->kode_desa }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->desa }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->nbs }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->nama_sls }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->nks }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->beban_kerja }}</td>

                                                    @foreach ($pemutakhiranPetani->rutaPetani as $ruta)
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">

                                                            {{ $ruta->progres }}
                                                        </td>
                                                    @endforeach

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->keluarga_awal }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPetani->keluarga_akhir }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        {{-- ======================END PETANI======================  --}}

        {{-- ======================START PERUSAHAAN======================  --}}

        <div class="p-3 mt-8">
            <div class="mt-10 flex justify-between">
                <span class="font-bold text-2xl">
                    PERUSAHAAN
                </span>
            </div>

            @foreach ($kegiatanPemutakhiranPerusahaans as $kegiatanPemutakhiranPerusahaan)
                @if ($kegiatanPemutakhiranPerusahaan->pemutakhiranPerusahaan->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id)->isEmpty())
                @else
                    <div class="flex justify-between">
                        <span class="font-bold text-2xl">
                            {{ $kegiatanPemutakhiranPerusahaan->nama }}
                        </span>
                    </div>
                    <div class="mt-2">
                        {{ \Carbon\Carbon::parse($kegiatanPemutakhiranPerusahaan->pemutakhiranPerusahaan->first()->tgl_awal)->locale('id')->translatedFormat('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($kegiatanPemutakhiranPerusahaan->pemutakhiranPerusahaan->first()->tgl_akhir)->locale('id')->translatedFormat('d M Y') }}
                    </div>
                    <div class="flex flex-col shadow-md mb-10">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="pt-3 min-w-full inline-block align-middle">
                                <div class="border rounded-sm overflow-hidden bg-white">
                                    <table class="min-w-full divide-y">
                                        <thead class="bg-gray-200 dark:bg-gray-900">
                                            <tr class="border-y border-x border-gray-300">
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    No</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    Aksi</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID Kegiatan</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    PERUSAHAAN</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID PML</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    PML</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    ID PPL</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    PPL</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KODE KECAMATAN</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KECAMATAN</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KODE DESA/KELURAHAN</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    DESA/KELURAHAN</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">
                                                    KODE SBR</th>

                                            </tr>
                                        </thead>
                                        <tbody class="border-y border-x border-gray-300">
                                            @foreach ($kegiatanPemutakhiranPerusahaan->pemutakhiranPerusahaan->where('id_ppl', Auth::guard('usermitra')->user()->ppl_id) as $pemutakhiranPerusahaan)
                                                <tr class="border-b border-gray-200">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $loop->iteration }}</td>

                                                    <td
                                                        class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <button type="button" onclick="openInputPerusahaan(this)"
                                                            data-id="{{ $pemutakhiranPerusahaan->id }}"
                                                            class="py-1 px-1 inline-flex items-center gap-x-2 rounded border border-transparent bg-blue-600  text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                                            data-hs-overlay="#hs-toggle-between-modals-first-modal1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                                height="19" viewBox="0 0 24 24"
                                                                style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                                <path
                                                                    d="M14 12c-1.095 0-2-.905-2-2 0-.354.103-.683.268-.973C12.178 9.02 12.092 9 12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-.092-.02-.178-.027-.268-.29.165-.619.268-.973.268z">
                                                                </path>
                                                                <path
                                                                    d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->id }}
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">

                                                        {{ $pemutakhiranPerusahaan->perusahaan }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->id_pml }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->pml }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->id_ppl }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->ppl }}</td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->kode_kec }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->kecamatan }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->kode_desa }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->desa }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $pemutakhiranPerusahaan->kode_sbr }}</td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- ======================END PERUSAHAAN======================  --}}



    </div>




    @include('page.mitra.pemutakhiran.input-rumah-tangga')
    @include('page.mitra.pemutakhiran.input-petani')



</body>
