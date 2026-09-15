@extends('layouts.app')

@section('title', 'Cek Garansi')
@section('meta_description', 'Cek status garansi resmi layanan GlossPro (Car Coating, Detailing, Window Film, PPF) menggunakan kode warranty Anda.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Cek Garansi</span>
            <h1 class="section-title">Verifikasi Garansi Resmi GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            <div class="check-warranty-card glass">
                <p class="section-subtitle" style="margin: 0 auto var(--space-2);">
                    Masukkan kode warranty yang tertera pada kartu garansi Anda untuk melihat
                    detail perlindungan layanan GlossPro Anda.
                </p>

                <form class="check-warranty-form" id="checkWarrantyForm">
                    <input
                        type="text"
                        id="warrantyCode"
                        name="warranty_code"
                        placeholder="Contoh: WR2026002"
                        autocomplete="off"
                        required>
                    <button type="submit" class="btn btn-gold" id="checkWarrantyBtn">Cek Garansi</button>
                </form>

                <p class="check-warranty-message" id="checkWarrantyMessage"></p>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            var DASHBOARD_BASE_URL = 'http://localhost:8000';

            var form = document.getElementById('checkWarrantyForm');
            var input = document.getElementById('warrantyCode');
            var button = document.getElementById('checkWarrantyBtn');
            var message = document.getElementById('checkWarrantyMessage');

            function setMessage(text, type) {
                message.textContent = text;
                message.className = 'check-warranty-message' + (type ? ' is-' + type : '');
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var kode = input.value.trim().toUpperCase();

                if (!kode) {
                    setMessage('Silakan masukkan kode warranty terlebih dahulu.', 'error');
                    return;
                }

                button.disabled = true;
                button.textContent = 'Memeriksa...';
                setMessage('', '');

                fetch(DASHBOARD_BASE_URL + '/warranty/' + encodeURIComponent(kode) + '/check')
                    .then(function (res) {
                        if (!res.ok) {
                            throw new Error('request_failed');
                        }
                        return res.json();
                    })
                    .then(function (data) {
                        if (data.valid) {
                            setMessage('Kode warranty ditemukan. Mengalihkan...', 'success');
                            window.location.href = DASHBOARD_BASE_URL + '/warranty/' + encodeURIComponent(kode);
                        } else {
                            setMessage('Kode warranty tidak ditemukan atau sudah tidak berlaku.', 'error');
                        }
                    })
                    .catch(function () {
                        setMessage('Gagal menghubungi server. Silakan coba lagi.', 'error');
                    })
                    .finally(function () {
                        button.disabled = false;
                        button.textContent = 'Cek Garansi';
                    });
            });
        })();
    </script>
@endsection
