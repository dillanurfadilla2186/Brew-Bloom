<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Brew & Bloom Cafe - Nikmati kopi pilihan, pastry fresh setiap hari, dan suasana hangat untuk bekerja, ngobrol maupun menikmati waktu sendiri.">
    <meta name="theme-color" content="#6F4E37">
    
    <title>@yield('title', 'Brew & Bloom | Coffee • Food • Good Vibes')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Cafe CSS -->
    <link rel="stylesheet" href="{{ asset('css/cafe.css') }}">
    @stack('styles')
</head>
<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Custom Cafe JS -->
    <script src="{{ asset('js/cafe.js') }}"></script>
    @stack('scripts')
</body>
</html>
