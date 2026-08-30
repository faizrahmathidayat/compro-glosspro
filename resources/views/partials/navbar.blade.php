<header class="navbar" id="mainNavbar">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-logo">GLOSS<span>PRO</span></a>

        <nav class="navbar-links" id="navbarLinks">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">Produk</a>
            <a href="{{ route('dealers') }}" class="{{ request()->routeIs('dealers') ? 'is-active' : '' }}">Dealer</a>
            <a href="{{ route('cek-garansi') }}" class="{{ request()->routeIs('cek-garansi') ? 'is-active' : '' }}">Cek Garansi</a>
        </nav>

        <div class="navbar-cta">
            <a href="{{ route('dealers') }}" class="btn btn-gold">Hubungi Kami</a>
            <button type="button" class="navbar-toggle" id="navbarToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
