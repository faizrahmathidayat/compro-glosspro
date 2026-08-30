<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Glosspro') — Premium Automotive Window Film</title>
    <meta name="description" content="@yield('meta_description', 'Glosspro - kaca film otomotif premium dengan perlindungan panas dan privasi kelas atas.')">
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
