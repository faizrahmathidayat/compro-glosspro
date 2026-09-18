<div class="navbar-backdrop" id="navbarBackdrop"></div>

<header class="navbar" id="mainNavbar">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-logo" aria-label="GlossPro — beranda">
            <img src="{{ asset('images/glosspro-logo.png') }}" alt="GlossPro">
            <span class="navbar-logo-text">GlossPro<small>Coating &middot; Detailing &middot; PPF</small></span>
        </a>

        <nav class="navbar-links" id="navbarLinks">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About Us</a>

            <div class="navbar-dropdown" id="layananDropdown">
                <button type="button" id="layananToggle" aria-expanded="false">
                    Layanan <span class="navbar-dropdown-caret"></span>
                </button>

                <div class="mega-menu">
                    <a href="{{ route('services.show', 'car-coating-01') }}" class="mega-menu-item">
                        <span class="mega-menu-icon">{!! '&#10022;' !!}</span>
                        <span>
                            <h5>Car Coating</h5>
                            <p>Nano Ceramic &amp; Graphene Coating</p>
                        </span>
                    </a>
                    <a href="{{ route('services.show', 'detailing-01') }}" class="mega-menu-item">
                        <span class="mega-menu-icon">{!! '&#10038;' !!}</span>
                        <span>
                            <h5>Detailing</h5>
                            <p>Interior, Exterior, Engine &amp; Glass</p>
                        </span>
                    </a>
                    <a href="{{ route('services.show', 'window-film-01') }}" class="mega-menu-item">
                        <span class="mega-menu-icon">{!! '&#9635;' !!}</span>
                        <span>
                            <h5>Window Film</h5>
                            <p>Ceramic &amp; Carbon, semua tingkat VLT</p>
                        </span>
                    </a>
                    <a href="{{ route('services.show', 'ppf-01') }}" class="mega-menu-item">
                        <span class="mega-menu-icon">{!! '&#9906;' !!}</span>
                        <span>
                            <h5>Paint Protection Film</h5>
                            <p>Self-Healing TPU, Gloss/Matte/Satin</p>
                        </span>
                    </a>
                    <a href="{{ route('services.index') }}" class="mega-menu-item" style="grid-column: 1 / -1;">
                        <span class="mega-menu-icon">&rarr;</span>
                        <span>
                            <h5>Lihat Semua Layanan</h5>
                            <p>Katalog lengkap varian &amp; paket GlossPro</p>
                        </span>
                    </a>
                </div>
            </div>

            <a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'is-active' : '' }}">Portfolio</a>
            <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'is-active' : '' }}">Artikel</a>
            <a href="{{ route('sorotan.index') }}" class="{{ request()->routeIs('sorotan.*') ? 'is-active' : '' }}">Sorotan Produk</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact Us</a>
            <a href="{{ route('cek-garansi') }}" class="{{ request()->routeIs('cek-garansi') ? 'is-active' : '' }}">Cek Garansi</a>
        </nav>

        <div class="navbar-cta">
            <button type="button" class="navbar-toggle" id="navbarToggle" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
