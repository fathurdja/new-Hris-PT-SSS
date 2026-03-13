<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Sistem Absensi' }}</title>
        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="bg-gray-100 font-sans antialiased relative selection:bg-[#D62828] selection:text-white"
          style="background-image: url('/bg.png'); background-size: cover; background-position: center; background-attachment: fixed;">
        
        <!-- Dark Overlay for better contrast -->
        <div class="absolute inset-0 bg-black/40 z-0"></div>
        
        <!-- Main Content -->
        <main class="relative z-10 hidden sm:block">
            <!-- Example Header (optional) -->
        </main>
        
        <div class="relative z-20">
        {{ $slot }}

        </div>

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
