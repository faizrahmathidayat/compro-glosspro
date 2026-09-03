<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LEXENT') — Building Window Film</title>
    <meta name="description" content="@yield('meta_description', 'LEXENT - kaca film gedung premium. Kontrol panas & silau, penolakan UV hingga 99%, efisiensi energi, dan garansi resmi hingga 8 tahun.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        (function () {
            var navbar = document.getElementById('mainNavbar');
            var toggle = document.getElementById('navbarToggle');
            var links = document.getElementById('navbarLinks');

            function onScroll() {
                if (window.scrollY > 40) {
                    navbar.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                }
            }

            window.addEventListener('scroll', onScroll);
            onScroll();

            if (toggle && links) {
                toggle.addEventListener('click', function () {
                    links.classList.toggle('is-open');
                });
            }
        })();
    </script>

    @yield('scripts')

</body>
</html>
