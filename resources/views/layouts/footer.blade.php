@php
    $profil = \App\Models\ProfilDiskominfo::first();
@endphp

<style>
    .footer {
        background: #07102A;
        color: rgba(255,255,255,.75);
    }
    .footer-main {
        padding: 4.5rem 0;
    }
    .footer-brand-name {
        font-size: 1rem;
        font-weight: 800;
        color: white;
    }
    .footer-brand-sub {
        font-size: .65rem;
        color: #60A5FA;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .footer-desc {
        font-size: .85rem;
        line-height: 1.7;
        color: rgba(255,255,255,.55);
        margin-top: .5rem;
    }
    .footer-heading {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: rgba(255,255,255,.4);
        margin-bottom: 1rem;
    }
    .footer-links {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }
    .footer-links a {
        font-size: .85rem;
        color: rgba(255,255,255,.6);
        text-decoration: none;
        transition: color .2s;
    }
    .footer-links a:hover {
        color: white;
    }
    .footer-contact-item {
        display: flex;
        gap: .75rem;
        font-size: .84rem;
        margin-bottom: .75rem;
        align-items: flex-start;
    }
    .footer-contact-icon {
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: .1rem;
    }
    .footer-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,.07);
    }
    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 0;
        font-size: .78rem;
        color: rgba(255,255,255,.35);
        flex-wrap: wrap;
        gap: .5rem;
    }
    .social-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.65);
        border: 1px solid rgba(255,255,255,.1);
        transition: all .2s ease;
        text-decoration: none;
    }
    .social-icon:hover {
        background: #1A56DB;
        color: white;
        border-color: #1A56DB;
        transform: translateY(-2px);
    }
    .social-row {
        display: flex;
        gap: .6rem;
        margin-top: .875rem;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 3rem;
    }
    
    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
    }
    @media (max-width: 560px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }
    }
</style>

<footer class="footer">
    <!-- Ornamen Dayak & Melayu Sanggau -->
    <div style="position: relative; overflow: hidden; line-height: 0;">
        <!-- Gradien transisi dari halaman ke footer -->
        <div style="height: 32px; background: linear-gradient(to bottom, transparent, #07102A);"></div>

        <!-- Ornamen SVG full-width -->
        <svg viewBox="0 0 1440 90" preserveAspectRatio="none" style="display: block; width: 100%; height: 90px; background: #07102A;" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="ornGold" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#B8860B" stop-opacity="0.3" />
                    <stop offset="50%" stop-color="#DAA520" stop-opacity="0.7" />
                    <stop offset="100%" stop-color="#B8860B" stop-opacity="0.3" />
                </linearGradient>
            </defs>
            
            <line x1="0" y1="2" x2="1440" y2="2" stroke="url(#ornGold)" stroke-width="1.5" />
            
            @for($i = 0; $i < 48; $i++)
            <polygon points="{{ $i * 30 }},8 {{ $i * 30 + 15 }},28 {{ $i * 30 + 30 }},8" fill="none" stroke="url(#ornGold)" stroke-width="1" opacity="0.6" />
            @endfor
            
            @for($i = 0; $i < 24; $i++)
            <g transform="translate({{ $i * 60 + 30 }}, 45)">
                <polygon points="0,-14 14,0 0,14 -14,0" fill="none" stroke="url(#ornGold)" stroke-width="1.2" opacity="0.7" />
                <circle cx="0" cy="0" r="4" fill="url(#ornGold)" opacity="0.5" />
            </g>
            @endfor
        </svg>
    </div>

    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand -->
                <div>
                    <div style="display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem;">
                        <img src="{{ asset('images/logo-sanggau.png') }}" 
                             alt="Logo" 
                             style="width: 48px; height: 48px; object-fit: contain;"
                             onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lambang_Kabupaten_Sanggau.png/600px-Lambang_Kabupaten_Sanggau.png'">
                        <div>
                            <div class="footer-brand-name">{{ $profil->nama_dinas ?? 'Diskominfo' }}</div>
                            <div class="footer-brand-sub">Kab. Sanggau</div>
                        </div>
                    </div>
                    <p class="footer-desc">
                        Dinas Komunikasi dan Informatika Kabupaten Sanggau melayani masyarakat dengan sepenuh hati dalam bidang komunikasi, informatika, dan teknologi informasi.
                    </p>
                    @if($profil && ($profil->facebook || $profil->instagram || $profil->youtube))
                    <div class="social-row">
                        @if($profil->facebook)
                        <a href="{{ $profil->facebook }}" target="_blank" rel="noopener" class="social-icon" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        @endif
                        @if($profil->instagram)
                        <a href="{{ $profil->instagram }}" target="_blank" rel="noopener" class="social-icon" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        @endif
                        @if($profil->youtube)
                        <a href="{{ $profil->youtube }}" target="_blank" rel="noopener" class="social-icon" aria-label="YouTube">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Tautan Cepat -->
                <div>
                    <h3 class="footer-heading">Tautan Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('profil') }}">Profil Dinas</a></li>
                        <li><a href="{{ route('berita') }}">Berita</a></li>
                        <li><a href="{{ route('pengumuman') }}">Pengumuman</a></li>
                        <li><a href="{{ route('agenda') }}">Agenda</a></li>
                        <li><a href="{{ route('galeri') }}">Galeri</a></li>
                    </ul>
                </div>

                <!-- Layanan -->
                <div>
                    <h3 class="footer-heading">Layanan</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('layanan') }}">Layanan Publik</a></li>
                        <li><a href="{{ route('ppid') }}">PPID</a></li>
                        <li><a href="{{ route('download') }}">Download</a></li>
                        <li><a href="{{ route('kontak') }}">Kontak Kami</a></li>
                        <li><a href="/admin">Portal Admin</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="footer-heading">Hubungi Kami</h3>
                    @if($profil)
                    @if($profil->alamat)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">📍</div>
                        <div>{{ $profil->alamat }}</div>
                    </div>
                    @endif
                    @if($profil->telepon)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">📞</div>
                        <div>{{ $profil->telepon }}</div>
                    </div>
                    @endif
                    @if($profil->email)
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">✉️</div>
                        <div>{{ $profil->email }}</div>
                    </div>
                    @endif
                    @else
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">📍</div>
                        <div>Kabupaten Sanggau, Kalimantan Barat</div>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">✉️</div>
                        <div>diskominfo@sanggau.go.id</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr class="footer-divider">

    <div class="container">
        <div class="footer-bottom">
            <div>
                © {{ date('Y') }} Dinas Komunikasi dan Informatika Kabupaten Sanggau. All rights reserved.
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: .78rem;">
                <a href="#" style="color: rgba(255,255,255,.35); text-decoration: none;">Kebijakan Privasi</a>
                <a href="#" style="color: rgba(255,255,255,.35); text-decoration: none;">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
