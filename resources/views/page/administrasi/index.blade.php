<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administrasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- ========== HEADER ========== -->
    @include('page.administrasi.partials.header') 
    <!-- ========== END HEADER ========== -->
  
    <!-- ========== Side Bar ========== -->
    @include('page.administrasi.partials.sidebar')
    <!-- ========== End Side Bar ========== -->
    
    
    
    <!-- ========== MAIN CONTENT ========== -->
    <div class="w-full lg:ps-64 flex justify-center items-center h-screen">
        <!-- Content -->
        <div class="text-center">
          <img src="{{asset('storage/img/welcome-administrasi.png')}}" alt="Monalisa" class="mx-auto mb-4 sm:max-w-sm md:max-w-lg lg:max-w-lg xl:max-w-xl">
          <h1 class="text-xl sm:text-xl md:text-xl lg:text-xl xl:text-xl font-medium text-gray-700">Selamat Datang Di Halaman Administrasi</h1>
          <h1 class="text-2xl sm:text-1xl md:text-1xl lg:text-1xl xl:text-1xl font-bold text-gray-700">Monalisa</h1>
        </div>
        <!-- End Content -->
      </div>
    <!-- ========== END MAIN CONTENT ========== -->
  </body>
</html>