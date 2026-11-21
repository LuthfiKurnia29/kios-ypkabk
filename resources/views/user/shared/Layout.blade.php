<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kios Mandiri YPKABK')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon-180.png') }}">

    {{-- Typography: Google Fonts Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Scripts & Styles via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-krem text-teks font-sans antialiased selection:bg-pink-muda selection:text-primary">

    {{-- Reusable Navigation Bar --}}
    @include('user.shared.navbar')

    {{-- Flash Notifications (If Any) --}}
    @if (session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 w-full">
            @if (session('success'))
                <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl shadow-xs text-sm font-medium">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold ml-4 cursor-pointer">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center justify-between p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl shadow-xs text-sm font-medium">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-950 font-bold ml-4 cursor-pointer">&times;</button>
                </div>
            @endif
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow w-full">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    {{-- Reusable Footer --}}
    @include('user.shared.footer')

    {{-- Modal Donasi Global (Dapat diakses dari semua halaman) --}}
    @include('user.pages.home._modal-donasi')

    {{-- Modal Print on Demand Global --}}
    @include('user.pages.home._modal-on-demand')

    {{-- Floating Quick WhatsApp Help --}}
    @php
        $siteLayout = $siteSettings ?? \App\Models\SiteSetting::getPublicSettings();
    @endphp
    <a href="{{ $siteLayout['contact_whatsapp_link'] ?? 'https://wa.me/6281234567890' }}"
       target="_blank"
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-40 bg-emerald-600 hover:bg-emerald-700 text-white min-h-[44px] px-4 py-2.5 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all text-xs sm:text-sm font-semibold flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-emerald-500 cursor-pointer"
       title="Hubungi Admin Kios via WhatsApp">
        <svg class="w-5 h-5 shrink-0 fill-current" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.03-1.25-.75-.67-1.26-1.5-1.41-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.12-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.49-.4-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.12.17 1.78 2.72 4.31 3.81.6.26 1.07.42 1.44.54.61.19 1.16.17 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.59.21-1.09.14-1.2-.06-.1-.22-.16-.47-.28"/>
        </svg>
        <span>Tanya Kios (WA)</span>
    </a>

    {{-- Global Custom Modal Pop-up (Ganti browser alert) --}}
    <div id="app-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-4 shadow-2xl relative border border-teks/10 text-center">
            <button type="button" onclick="closeAppModal()" class="absolute top-4 right-4 text-teks/40 hover:text-teks p-1.5 rounded-lg transition-colors cursor-pointer text-lg leading-none font-bold" aria-label="Tutup">
                &times;
            </button>

            <div class="space-y-1.5 pt-2">
                <h3 id="app-modal-title" class="text-lg font-bold text-teks">Pemberitahuan</h3>
                <p id="app-modal-message" class="text-xs sm:text-sm text-teks/75 leading-relaxed"></p>
            </div>

            <div id="app-modal-actions" class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-2">
                <button type="button" id="app-modal-primary-btn" onclick="closeAppModal()" class="w-full py-2.5 px-5 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs sm:text-sm shadow-xs transition-all active:scale-[0.98] cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- Global Custom Toast Notifications --}}
    <div id="app-toast-container" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 pointer-events-none flex flex-col items-center gap-2"></div>

    <script>
        // Modal Donasi Global Script
        function openDonationModal() {
            const modal = document.getElementById('donation-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeDonationModal() {
            const modal = document.getElementById('donation-modal');
            if (modal) modal.classList.add('hidden');
        }

        function previewDonationPhotos(e) {
            const input = e.target;
            const container = document.getElementById('donation-photos-preview');
            const countLabel = document.getElementById('donation-photos-count');
            if (!container) return;

            container.innerHTML = '';
            const files = Array.from(input.files || []);

            if (files.length > 5) {
                window.showAppToast('Maksimal 5 foto barang donasi yang dapat dipilih.');
                input.value = '';
                if (countLabel) countLabel.innerText = '0/5 foto';
                container.classList.add('hidden');
                return;
            }

            if (countLabel) {
                countLabel.innerText = `${files.length}/5 foto dipilih`;
            }

            if (files.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            files.forEach((file, idx) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        const thumb = document.createElement('div');
                        thumb.className = 'relative w-full aspect-square rounded-lg overflow-hidden border border-teks/15 bg-stone-100 shadow-xs';
                        thumb.innerHTML = `<img src="${evt.target.result}" alt="Foto ${idx+1}" class="w-full h-full object-cover">
                                           <span class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[9px] text-center font-semibold py-0.5">#${idx+1}</span>`;
                        container.appendChild(thumb);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        async function handleDonationSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('donation-submit-btn');
            const originalText = btn ? btn.innerHTML : 'Kirim Formulir Donasi';

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>Mengirim data...</span>';
            }

            try {
                const formData = new FormData(form);
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();

                if (res.ok) {
                    form.reset();
                    const container = document.getElementById('donation-photos-preview');
                    const countLabel = document.getElementById('donation-photos-count');
                    if (container) { container.innerHTML = ''; container.classList.add('hidden'); }
                    if (countLabel) countLabel.innerText = '0/5 foto';
                    closeDonationModal();

                    window.showAppModal({
                        title: 'Formulir Donasi Terkirim',
                        message: data.message || 'Terima kasih banyak! Tim Kios Mandiri YPKABK akan segera menghubungi nomor WhatsApp Anda untuk koordinasi donasi.',
                        type: 'success',
                        closeText: 'Selesai'
                    });
                } else {
                    window.showAppModal({
                        title: 'Periksa Data Formulir',
                        message: data.message || 'Mohon lengkapi formulir dengan benar.',
                        type: 'error',
                        closeText: 'Perbaiki'
                    });
                }
            } catch (err) {
                window.showAppToast('Terjadi kendala jaringan. Silakan hubungi kami via WhatsApp atau coba sesaat lagi.');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }
        }

        // Global Custom Toast
        window.showAppToast = function(message, duration = 3000) {
            const container = document.getElementById('app-toast-container');
            if (!container || !message) return;
            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto max-w-sm px-4 py-2.5 rounded-full bg-[#2B2A33] text-white text-xs font-semibold shadow-xl border border-white/10 flex items-center gap-2 transition-all transform duration-200 translate-y-2 opacity-0 text-center';
            toast.innerText = message;
            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 250);
            }, duration);
        };

        // Global Custom Modal
        window.showAppModal = function(options) {
            const modal = document.getElementById('app-modal');
            if (!modal) return;
            const titleEl = document.getElementById('app-modal-title');
            const msgEl = document.getElementById('app-modal-message');
            const actionsContainer = document.getElementById('app-modal-actions');

            if (titleEl) titleEl.innerText = options.title || 'Informasi';
            if (msgEl) msgEl.innerText = options.message || '';

            if (actionsContainer) {
                actionsContainer.innerHTML = '';
                if (options.actionText && options.actionUrl) {
                    const actBtn = document.createElement('a');
                    actBtn.href = options.actionUrl;
                    actBtn.className = 'w-full py-2.5 px-5 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs sm:text-sm shadow-xs text-center transition-all cursor-pointer';
                    actBtn.innerText = options.actionText;
                    actionsContainer.appendChild(actBtn);
                }
                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = options.actionText
                    ? 'w-full py-2 px-4 rounded-full border border-teks/20 hover:bg-stone-50 text-teks font-semibold text-xs transition-colors cursor-pointer'
                    : 'w-full py-2.5 px-5 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs sm:text-sm shadow-xs transition-all cursor-pointer';
                closeBtn.innerText = options.closeText || (options.actionText ? 'Tutup' : 'Mengerti');
                closeBtn.onclick = () => {
                    closeAppModal();
                    if (typeof options.onClose === 'function') options.onClose();
                };
                actionsContainer.appendChild(closeBtn);
            }

            modal.classList.remove('hidden');
        };

        window.closeAppModal = function() {
            const modal = document.getElementById('app-modal');
            if (modal) modal.classList.add('hidden');
        };

        // Override window.alert native browser
        window.alert = function(msg) {
            window.showAppToast(msg);
        };

        document.addEventListener('DOMContentLoaded', () => {
            const donationModal = document.getElementById('donation-modal');
            if (donationModal) {
                donationModal.addEventListener('click', (e) => {
                    if (e.target === donationModal) {
                        donationModal.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
