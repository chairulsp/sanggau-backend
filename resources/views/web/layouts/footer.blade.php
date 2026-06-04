<footer class="footer">
    {{-- Ornamen Dayak & Melayu --}}
    <div class="footer-ornament">
        <svg viewBox="0 0 1440 90" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="ornGold" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#B8860B" stop-opacity="0.3"/>
                    <stop offset="50%" stop-color="#DAA520" stop-opacity="0.7"/>
                    <stop offset="100%" stop-color="#B8860B" stop-opacity="0.3"/>
                </linearGradient>
            </defs>
            <line x1="0" y1="2" x2="1440" y2="2" stroke="url(#ornGold)" stroke-width="1.5"/>
            @for($i=0;$i<48;$i++)
            <polygon points="{{ $i*30 }},8 {{ $i*30+15 }},28 {{ $i*30+30 }},8" fill="none" stroke="url(#ornGold)" stroke-width="1" opacity="0.6"/>
            @endfor
            @for($i=0;$i<24;$i++)
            <g transform="translate({{ $i*60+30 }}, 45)">
                <polygon points="0,-14 14,0 0,14 -14,0" fill="none" stroke="url(#ornGold)" stroke-width="1.2" opacity="0.7"/>
                <polygon points="0,-7 7,0 0,7 -7,0" fill="#DAA520" opacity="0.25"/>
                <circle cx="0" cy="0" r="2" fill="#DAA520" opacity="0.5"/>
            </g>
            @endfor
            @for($i=0;$i<48;$i++)
            <polygon points="{{ $i*30 }},82 {{ $i*30+15 }},62 {{ $i*30+30 }},82" fill="none" stroke="#C0392B" stroke-width="0.8" opacity="0.4"/>
            @endfor
            <line x1="0" y1="88" x2="1440" y2="88" stroke="url(#ornGold)" stroke-width="1" opacity="0.5"/>
        </svg>
    </div>

    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                {{-- Brand --}}
                <div>
                    <div class="footer-brand">
                        <img src="{{ asset('logo-sanggau.png') }}" alt="Logo" width="44" height="44">
                        <div>
                            <div class="footer-brand-name">Diskominfo</div>
                            <div class="footer-brand-sub">Kab. Sanggau</div>
                        </div>
                    </div>
                    <p class="footer-desc">Dinas Komunikasi dan Informatika Kabupaten Sanggau — Melayani dengan Inovatif dan Profesional.</p>
                    <div class="social-row">
                        <a href="https://facebook.com" target="_blank" rel="noopener" class="social-icon" aria-label="Facebook">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener" class="social-icon" aria-label="Instagram">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener" class="social-icon" aria-label="YouTube">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33 2.78 2.78 0 001.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.33 29 29 0 00-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="white"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Navigasi --}}
                <div>
                    <div class="footer-heading">Navigasi</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">› Beranda</a></li>
                        <li><a href="{{ route('profil') }}">› Profil</a></li>
                        <li><a href="{{ route('berita') }}">› Berita</a></li>
                        <li><a href="{{ route('layanan') }}">› Layanan</a></li>
                        <li><a href="{{ route('galeri') }}">› Galeri</a></li>
                        <li><a href="{{ route('agenda') }}">› Agenda</a></li>
                    </ul>
                </div>

                {{-- Layanan & Informasi --}}
                <div>
                    <div class="footer-heading">Layanan & Informasi</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('pengumuman') }}">› Pengumuman</a></li>
                        <li><a href="{{ route('ppid') }}">› PPID</a></li>
                        <li><a href="{{ route('download') }}">› Download Dokumen</a></li>
                        <li><a href="{{ route('kontak') }}">› Kontak</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <div class="footer-heading">Hubungi Kami</div>
                    <div class="footer-contact-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Jl. Jend. Sudirman No. 3, Sanggau, Kalimantan Barat</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        <span>(0564) 21234</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>
                        <span>diskominfo@sanggau.go.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Senin–Jumat: 07.30–16.00 WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="footer-divider">

    {{-- Ornamen tengah copyright --}}
    <div class="footer-ornament-center">
        <svg width="120" height="16" viewBox="0 0 120 16"><line x1="0" y1="8" x2="40" y2="8" stroke="#DAA520" stroke-width="0.8" opacity="0.35"/>@for($i=0;$i<4;$i++)<polygon points="{{ $i*10+2 }},8 {{ $i*10+7 }},3 {{ $i*10+12 }},8 {{ $i*10+7 }},13" fill="none" stroke="#DAA520" stroke-width="0.7" opacity="0.35"/>@endfor</svg>
        <svg width="40" height="20" viewBox="0 0 40 20"><polygon points="20,2 36,10 20,18 4,10" fill="none" stroke="#DAA520" stroke-width="1" opacity="0.4"/><polygon points="20,6 30,10 20,14 10,10" fill="#DAA520" opacity="0.2"/><circle cx="20" cy="10" r="2.5" fill="#DAA520" opacity="0.5"/></svg>
        <svg width="120" height="16" viewBox="0 0 120 16" style="transform:scaleX(-1)"><line x1="0" y1="8" x2="40" y2="8" stroke="#DAA520" stroke-width="0.8" opacity="0.35"/>@for($i=0;$i<4;$i++)<polygon points="{{ $i*10+2 }},8 {{ $i*10+7 }},3 {{ $i*10+12 }},8 {{ $i*10+7 }},13" fill="none" stroke="#DAA520" stroke-width="0.7" opacity="0.35"/>@endfor</svg>
    </div>

    <div class="container">
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Diskominfo Kabupaten Sanggau. Hak Cipta Dilindungi.</span>
            <span>Dibuat oleh Diskominfo Sanggau</span>
        </div>
    </div>
</footer>
