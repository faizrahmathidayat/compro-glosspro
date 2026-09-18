<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GlossPro') — Car Coating, Detailing, Window Film & PPF</title>
    <meta name="description" content="@yield('meta_description', 'GlossPro - Car Coating, Detailing, Window Film, dan Paint Protection Film premium untuk kendaraan Anda. Nano Ceramic & Graphene Coating, self-healing PPF, garansi resmi hingga 9 tahun.')">
    <link rel="icon" type="image/png" href="{{ asset('images/glosspro-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.lightbox')

    <script>
        (function () {
            var navbar = document.getElementById('mainNavbar');
            var toggle = document.getElementById('navbarToggle');
            var links = document.getElementById('navbarLinks');
            var backdrop = document.getElementById('navbarBackdrop');
            var dropdown = document.getElementById('layananDropdown');
            var dropdownToggle = document.getElementById('layananToggle');

            function onScroll() {
                if (window.scrollY > 40) {
                    navbar.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                }
            }

            window.addEventListener('scroll', onScroll);
            onScroll();

            function closeMobileNav() {
                links.classList.remove('is-open');
                if (backdrop) { backdrop.classList.remove('is-open'); }
                if (dropdown) { dropdown.classList.remove('is-open'); }
                if (dropdownToggle) { dropdownToggle.setAttribute('aria-expanded', 'false'); }
            }

            if (toggle && links) {
                toggle.addEventListener('click', function () {
                    var isOpen = links.classList.toggle('is-open');
                    if (backdrop) { backdrop.classList.toggle('is-open', isOpen); }
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeMobileNav);
            }

            // Close the mobile drawer after following a plain nav link.
            if (links) {
                links.querySelectorAll(':scope > a').forEach(function (link) {
                    link.addEventListener('click', closeMobileNav);
                });
            }

            if (dropdown && dropdownToggle) {
                dropdownToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isOpen = dropdown.classList.toggle('is-open');
                    dropdownToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                dropdown.querySelectorAll('.mega-menu a').forEach(function (link) {
                    link.addEventListener('click', closeMobileNav);
                });

                document.addEventListener('click', function (e) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('is-open');
                        dropdownToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) { closeMobileNav(); }
            });

            // Lightbox — opened by any .cms-gallery-item on the page (Artikel/Sorotan/Portofolio detail views).
            var lightbox = document.getElementById('cmsLightbox');
            var lightboxImage = document.getElementById('cmsLightboxImage');
            var lightboxItems = [];
            var lightboxIndex = 0;

            function openLightboxAt(index) {
                if (!lightboxItems[index]) { return; }
                lightboxIndex = index;
                lightboxImage.setAttribute('src', lightboxItems[index].getAttribute('data-full'));
                lightboxImage.setAttribute('alt', lightboxItems[index].getAttribute('data-alt') || '');
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
            }

            function closeLightbox() {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
            }

            if (lightbox && lightboxImage) {
                lightboxItems = Array.prototype.slice.call(document.querySelectorAll('.cms-gallery-item'));

                lightboxItems.forEach(function (item, index) {
                    item.addEventListener('click', function () { openLightboxAt(index); });
                });

                var lightboxClose = document.getElementById('cmsLightboxClose');
                var lightboxBackdrop = document.getElementById('cmsLightboxBackdrop');
                var lightboxPrev = document.getElementById('cmsLightboxPrev');
                var lightboxNext = document.getElementById('cmsLightboxNext');

                if (lightboxClose) { lightboxClose.addEventListener('click', closeLightbox); }
                if (lightboxBackdrop) { lightboxBackdrop.addEventListener('click', closeLightbox); }
                if (lightboxPrev) { lightboxPrev.addEventListener('click', function () { openLightboxAt((lightboxIndex - 1 + lightboxItems.length) % lightboxItems.length); }); }
                if (lightboxNext) { lightboxNext.addEventListener('click', function () { openLightboxAt((lightboxIndex + 1) % lightboxItems.length); }); }

                document.addEventListener('keydown', function (e) {
                    if (!lightbox.classList.contains('is-open')) { return; }
                    if (e.key === 'Escape') { closeLightbox(); }
                    if (e.key === 'ArrowLeft' && lightboxPrev) { lightboxPrev.click(); }
                    if (e.key === 'ArrowRight' && lightboxNext) { lightboxNext.click(); }
                });
            }
        })();
    </script>

    @yield('scripts')

</body>
</html>
