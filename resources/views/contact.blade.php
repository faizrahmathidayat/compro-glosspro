@extends('layouts.app')

@section('title', 'Contact Us')
@section('meta_description', 'Hubungi GlossPro untuk booking Car Coating, Detailing, Window Film, atau PPF. Temukan cabang workshop terdekat dan chat langsung via WhatsApp.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Contact Us</span>
            <h1 class="section-title">Booking &amp; Lokasi Workshop GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container booking-grid">
            <div class="booking-form glass">
                <span class="eyebrow">Form Booking</span>
                <h3 class="section-title" style="font-size: 1.6rem;">Isi Detail Kendaraan Anda</h3>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    Form ini akan menyiapkan pesan WhatsApp berisi detail booking Anda untuk
                    dikirim langsung ke cabang pilihan &mdash; tanpa perlu mengetik ulang.
                </p>

                <form id="bookingForm">
                    <div class="form-field">
                        <label for="bookingName">Nama Lengkap</label>
                        <input type="text" id="bookingName" name="name" required placeholder="Nama Anda">
                    </div>

                    <div class="form-field">
                        <label for="bookingPhone">Nomor HP</label>
                        <input type="tel" id="bookingPhone" name="phone" required placeholder="08xx-xxxx-xxxx">
                    </div>

                    <div class="form-field">
                        <label id="bookingServiceLabel">Layanan</label>
                        <div class="custom-select" id="bookingServiceSelect">
                            <button type="button" class="custom-select-trigger" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="bookingServiceLabel">
                                <span class="custom-select-value is-placeholder" data-placeholder="Pilih layanan">Pilih layanan</span>
                                <span class="custom-select-caret"></span>
                            </button>
                            <ul class="custom-select-list" role="listbox" hidden>
                                @foreach($pillars as $p)
                                    <li role="option" tabindex="0" data-value="{{ $p['name'] }}">{{ $p['name'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" id="bookingService" name="service" required>
                    </div>

                    <div class="form-field">
                        <label id="bookingBranchLabel">Cabang</label>
                        <div class="custom-select" id="bookingBranchSelect">
                            <button type="button" class="custom-select-trigger" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="bookingBranchLabel">
                                <span class="custom-select-value is-placeholder" data-placeholder="Pilih cabang terdekat">Pilih cabang terdekat</span>
                                <span class="custom-select-caret"></span>
                            </button>
                            <ul class="custom-select-list" role="listbox" hidden>
                                @foreach($branches as $branch)
                                    <li role="option" tabindex="0" data-value="{{ $branch['name'] }}" data-whatsapp="{{ $branch['whatsapp'] }}">{{ $branch['name'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" id="bookingBranch" name="branch" required>
                    </div>

                    <div class="form-field">
                        <label for="bookingDate">Tanggal Rencana Kunjungan</label>
                        <input type="date" id="bookingDate" name="date">
                    </div>

                    <div class="form-field">
                        <label for="bookingMessage">Catatan Tambahan (opsional)</label>
                        <textarea id="bookingMessage" name="message" placeholder="Contoh: tipe mobil, warna cat, kebutuhan khusus"></textarea>
                    </div>

                    <button type="submit" class="btn btn-gold btn-block" style="margin-top: var(--space-2);">Kirim via WhatsApp</button>
                </form>
            </div>

            <div>
                <div class="map-embed" style="margin-bottom: var(--space-3);">
                    Placeholder Peta &mdash; sematkan embed Google Maps cabang terpilih di sini
                </div>

                <span class="eyebrow">Cabang Workshop</span>
                <div class="branch-grid">
                    @foreach($branches as $branch)
                        <div class="branch-card glass">
                            <div>
                                <h4>{{ $branch['name'] }}</h4>
                                <p>{{ $branch['address'] }}</p>
                                <p>{{ $branch['phone'] }}</p>
                            </div>
                            <a href="{{ $branch['maps_url'] }}" target="_blank" rel="noopener" class="btn btn-outline">Buka Peta</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            // Custom dropdown: a styled button + listbox driving a hidden
            // input, so the open list stays fully themeable (native <select>
            // option popups can't be restyled consistently across browsers).
            document.querySelectorAll('.custom-select').forEach(function (root) {
                var trigger = root.querySelector('.custom-select-trigger');
                var valueEl = root.querySelector('.custom-select-value');
                var list = root.querySelector('.custom-select-list');
                var hiddenInput = root.nextElementSibling;
                var options = root.querySelectorAll('.custom-select-list li');

                function close() {
                    root.classList.remove('is-open');
                    list.hidden = true;
                    trigger.setAttribute('aria-expanded', 'false');
                }

                function open() {
                    root.classList.add('is-open');
                    list.hidden = false;
                    trigger.setAttribute('aria-expanded', 'true');
                }

                function select(option) {
                    var value = option.getAttribute('data-value');
                    valueEl.textContent = value;
                    valueEl.classList.remove('is-placeholder');
                    hiddenInput.value = value;
                    var whatsapp = option.getAttribute('data-whatsapp');
                    if (whatsapp) { hiddenInput.setAttribute('data-whatsapp', whatsapp); }
                    options.forEach(function (o) { o.classList.toggle('is-selected', o === option); });
                    close();
                    trigger.focus();
                }

                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (root.classList.contains('is-open')) { close(); } else { open(); }
                });

                options.forEach(function (option) {
                    option.addEventListener('click', function () { select(option); });
                    option.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            select(option);
                        }
                    });
                });

                document.addEventListener('click', function (e) {
                    if (!root.contains(e.target)) { close(); }
                });
            });

            var DEFAULT_WHATSAPP = '6285771855911';
            var form = document.getElementById('bookingForm');
            var branchInput = document.getElementById('bookingBranch');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var name = document.getElementById('bookingName').value.trim();
                var phone = document.getElementById('bookingPhone').value.trim();
                var service = document.getElementById('bookingService').value;
                var branchName = branchInput.value;
                var whatsapp = branchInput.getAttribute('data-whatsapp') || DEFAULT_WHATSAPP;
                var date = document.getElementById('bookingDate').value;
                var message = document.getElementById('bookingMessage').value.trim();

                var lines = [
                    'Halo GlossPro, saya ingin booking:',
                    'Nama: ' + name,
                    'No. HP: ' + phone,
                    'Layanan: ' + service,
                    'Cabang: ' + branchName
                ];

                if (date) { lines.push('Tanggal: ' + date); }
                if (message) { lines.push('Catatan: ' + message); }

                var text = encodeURIComponent(lines.join('\n'));
                window.open('https://wa.me/' + whatsapp + '?text=' + text, '_blank', 'noopener');
            });
        })();
    </script>
@endsection
