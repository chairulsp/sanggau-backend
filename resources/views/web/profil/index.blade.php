@extends('layouts.app')

@section('title', 'Profil & Struktur Organisasi - ' . config('app.name'))

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb" style="color: rgba(255,255,255,0.6); margin-bottom: 0.75rem;">
            <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.7);">Beranda</a>
            <span>/</span>
            <span style="color: white;">Profil & Struktur</span>
        </div>
        <h1>Profil Diskominfo</h1>
        <p>Mengenal lebih dekat Dinas Komunikasi dan Informatika Kabupaten Sanggau</p>
    </div>
</div>

<section class="section" style="background: linear-gradient(180deg, #F8FAFC 0%, #EFF6FF 100%);">
    <div class="container">
        {{-- Tabs --}}
        <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; justify-content: center; background: var(--bg-surface); border-radius: 14px; padding: 0.5rem; border: 1px solid #E2E8F0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <button onclick="switchTab('profil')" id="tab-profil" class="tab-btn active">📖 Profil Diskominfo</button>
            <button onclick="switchTab('struktur')" id="tab-struktur" class="tab-btn">👥 Struktur Organisasi</button>
        </div>

        {{-- Tab: Profil --}}
        <div id="content-profil" class="tab-content active">
            <div style="background: var(--bg-surface); border-radius: 24px; border: 1px solid var(--border); padding: 3rem; box-shadow: 0 8px 32px rgba(0,0,0,0.06);">
                
                @if($profil)
                    {{-- Sejarah --}}
                    <div style="margin-bottom: 2.5rem;">
                        <h2 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1rem; border-bottom: 2px solid #EFF6FF; padding-bottom: 0.5rem;">
                            📜 Sejarah Diskominfo
                        </h2>
                        <p style="line-height: 1.8; color: var(--text-secondary); font-size: 1rem; white-space: pre-line;">
                            {{ $profil->sejarah ?? 'Belum ada data sejarah.' }}
                        </p>
                    </div>

                    {{-- Visi Misi --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2.5rem;">
                        <div style="background: #EFF6FF; padding: 2rem; border-radius: 16px; border: 1px solid #BFDBFE;">
                            <h2 style="font-size: 1.5rem; color: #1E40AF; margin-bottom: 1rem;">🎯 Visi</h2>
                            <p style="line-height: 1.8; font-size: 1.05rem; font-weight: 600; font-style: italic; color: #1E3A8A;">
                                "{{ $profil->visi ?? 'Belum ada data visi.' }}"
                            </p>
                        </div>
                        <div style="background: #F0FDF4; padding: 2rem; border-radius: 16px; border: 1px solid #BBF7D0;">
                            <h2 style="font-size: 1.5rem; color: #166534; margin-bottom: 1rem;">🚀 Misi</h2>
                            <p style="line-height: 1.8; font-size: 1rem; white-space: pre-line; color: #14532D;">
                                {{ $profil->misi ?? 'Belum ada data misi.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tupoksi --}}
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1rem; border-bottom: 2px solid #EFF6FF; padding-bottom: 0.5rem;">
                            📋 Tugas Pokok & Fungsi
                        </h2>
                        <p style="line-height: 1.8; color: var(--text-secondary); font-size: 1rem; white-space: pre-line;">
                            {{ $profil->tupoksi ?? 'Belum ada data tupoksi.' }}
                        </p>
                    </div>
                @else
                    <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">📖</div>
                        <p style="font-size: 1.1rem; font-weight: 600;">Data profil belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Struktur --}}
        <div id="content-struktur" class="tab-content">
            <div style="background: var(--bg-surface); border-radius: 24px; border: 1px solid var(--border); padding: 3rem 2rem; box-shadow: 0 8px 32px rgba(0,0,0,0.06);">
                
                @if($pegawai->isEmpty())
                    <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🏛️</div>
                        <p style="font-size: 1.1rem; font-weight: 600;">Data struktur organisasi belum tersedia.</p>
                    </div>
                @else
                    {{-- Pimpinan --}}
                    @php
                        $pimpinan = $pegawai->where('tipe_jabatan', 'pimpinan');
                        $fungsional = $pegawai->where('tipe_jabatan', 'fungsional');
                        $pelaksana = $pegawai->where('tipe_jabatan', 'pelaksana');
                    @endphp

                    @if($pimpinan->count() > 0)
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; padding: 0.5rem 1.5rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; border: 1px solid #FCD34D; margin-bottom: 1.5rem;">
                                🏛️ Pimpinan
                                <span style="background: #92400E; color: white; padding: 0.15rem 0.65rem; border-radius: 999px; font-size: 0.7rem; margin-left: 0.25rem;">
                                    {{ $pimpinan->count() }}
                                </span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 2rem;">
                            @foreach($pimpinan->sortBy('urutan') as $p)
                                <div onclick="openModal({{ $p->id }})" class="pegawai-card" data-id="{{ $p->id }}"
                                    style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 1.75rem; text-align: center; transition: all 0.3s; cursor: pointer; width: 240px; max-width: 100%;">
                                    <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin: 0 auto 1rem; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border: 4px solid white; box-shadow: 0 4px 20px rgba(26,86,219,0.2); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                                        @if($p->foto)
                                            <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            👤
                                        @endif
                                    </div>
                                    <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3; color: var(--text-primary);">
                                        {{ $p->nama_lengkap }}
                                    </h3>
                                    <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 0.3rem 0.85rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; color: white; margin-bottom: 0.35rem;">
                                        {{ $p->jabatan }}
                                    </div>
                                    <span style="font-size: 0.68rem; padding: 0.15rem 0.6rem; border-radius: 999px; background: {{ $p->status_pegawai === 'PNS' ? '#DBEAFE' : '#D1FAE5' }}; color: {{ $p->status_pegawai === 'PNS' ? '#1E40AF' : '#065F46' }}; font-weight: 600;">
                                        {{ $p->status_pegawai }}
                                    </span>
                                    <div style="font-size: 0.6rem; color: var(--text-muted); margin-top: 0.5rem; opacity: 0.7;">Klik untuk detail</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Divider --}}
                    @if($fungsional->count() > 0 || $pelaksana->count() > 0)
                        <div style="width: 100%; height: 2px; background: linear-gradient(to right, transparent, #E2E8F0, transparent); margin: 3rem 0;"></div>
                    @endif

                    {{-- Fungsional --}}
                    @if($fungsional->count() > 0)
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #F3E8FF, #E9D5FF); color: #6B21A8; padding: 0.5rem 1.5rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; border: 1px solid #C084FC;">
                                ⭐ Pejabat Fungsional
                                <span style="background: #6B21A8; color: white; padding: 0.15rem 0.65rem; border-radius: 999px; font-size: 0.7rem;">{{ $fungsional->count() }}</span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
                            @foreach($fungsional->sortBy('urutan') as $p)
                                <div onclick="openModal({{ $p->id }})" class="pegawai-card" data-id="{{ $p->id }}"
                                    style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 1.25rem 1rem; text-align: center; cursor: pointer; width: 190px;">
                                    <div style="width: 95px; height: 95px; border-radius: 50%; overflow: hidden; margin: 0 auto 1rem; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border: 4px solid white; box-shadow: 0 4px 20px rgba(26,86,219,0.2); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                                        @if($p->foto)
                                            <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            👤
                                        @endif
                                    </div>
                                    <h3 style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3;">{{ $p->nama_lengkap }}</h3>
                                    <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 0.3rem 0.85rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; color: white; margin-bottom: 0.35rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $p->jabatan }}
                                    </div>
                                    <span style="font-size: 0.68rem; padding: 0.15rem 0.6rem; border-radius: 999px; background: {{ $p->status_pegawai === 'PNS' ? '#DBEAFE' : '#D1FAE5' }}; color: {{ $p->status_pegawai === 'PNS' ? '#1E40AF' : '#065F46' }}; font-weight: 600;">
                                        {{ $p->status_pegawai }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Divider --}}
                    @if($pelaksana->count() > 0)
                        <div style="width: 100%; height: 2px; background: linear-gradient(to right, transparent, #E2E8F0, transparent); margin: 3rem 0;"></div>
                    @endif

                    {{-- Pelaksana --}}
                    @if($pelaksana->count() > 0)
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #F3F4F6, #E5E7EB); color: #374151; padding: 0.5rem 1.5rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; border: 1px solid #D1D5DB;">
                                👥 Pelaksana
                                <span style="background: #374151; color: white; padding: 0.15rem 0.65rem; border-radius: 999px; font-size: 0.7rem;">{{ $pelaksana->count() }}</span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
                            @foreach($pelaksana->sortBy('urutan') as $p)
                                <div onclick="openModal({{ $p->id }})" class="pegawai-card" data-id="{{ $p->id }}"
                                    style="background: var(--bg-surface); border-radius: 16px; border: 1px solid var(--border); padding: 1.25rem 1rem; text-align: center; cursor: pointer; width: 190px;">
                                    <div style="width: 95px; height: 95px; border-radius: 50%; overflow: hidden; margin: 0 auto 1rem; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border: 4px solid white; box-shadow: 0 4px 20px rgba(26,86,219,0.2); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                                        @if($p->foto)
                                            <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            👤
                                        @endif
                                    </div>
                                    <h3 style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3;">{{ $p->nama_lengkap }}</h3>
                                    <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 0.3rem 0.85rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; color: white; margin-bottom: 0.35rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $p->jabatan }}
                                    </div>
                                    <span style="font-size: 0.68rem; padding: 0.15rem 0.6rem; border-radius: 999px; background: {{ $p->status_pegawai === 'PNS' ? '#DBEAFE' : '#D1FAE5' }}; color: {{ $p->status_pegawai === 'PNS' ? '#1E40AF' : '#065F46' }}; font-weight: 600;">
                                        {{ $p->status_pegawai }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Modal Detail Pegawai --}}
<div id="modal-pegawai" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem;" onclick="closeModal()">
    <div onclick="event.stopPropagation()" style="background: var(--bg-surface); border-radius: 24px; max-width: 500px; width: 100%; max-height: 90vh; overflow: auto; padding: 2.5rem 2rem; text-align: center; box-shadow: 0 24px 64px rgba(0,0,0,0.3); position: relative;">
        <button onclick="closeModal()" style="position: absolute; top: 1rem; right: 1rem; width: 36px; height: 36px; border-radius: 50%; border: none; background: #F3F4F6; font-size: 1.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6B7280;">✕</button>
        <div id="modal-content"></div>
    </div>
</div>

<style>
.tab-btn {
    padding: 0.7rem 2rem;
    font-size: 0.95rem;
    font-weight: 700;
    background: transparent;
    color: #64748B;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    flex: 1;
    font-family: inherit;
}
.tab-btn.active {
    background: var(--primary);
    color: white;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.pegawai-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
}
</style>

<script>
const pegawaiData = @json($pegawai);

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('content-' + tab).classList.add('active');
}

function openModal(id) {
    const pegawai = pegawaiData.find(p => p.id === id);
    if (!pegawai) return;
    
    const fotoUrl = pegawai.foto ? '{{ asset('storage') }}/' + pegawai.foto : '';
    const content = `
        <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: 0 auto 1.5rem; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border: 6px solid white; box-shadow: 0 8px 32px rgba(26,86,219,0.25); display: flex; align-items: center; justify-content: center; font-size: 5rem;">
            ${fotoUrl ? `<img src="${fotoUrl}" alt="${pegawai.nama_lengkap}" style="width: 100%; height: 100%; object-fit: cover;">` : '👤'}
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 0.5rem; color: #111827;">${pegawai.nama_lengkap}</h2>
        <div style="display: inline-flex; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; padding: 0.4rem 1.25rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;">${pegawai.jabatan}</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; text-align: left;">
            ${pegawai.nip ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">NIP</div><div style="font-size: 0.9rem; font-weight: 600;">${pegawai.nip}</div></div>` : ''}
            ${pegawai.status_pegawai ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">Status</div><span style="background: ${pegawai.status_pegawai === 'PNS' ? '#DBEAFE' : '#D1FAE5'}; color: ${pegawai.status_pegawai === 'PNS' ? '#1E40AF' : '#065F46'}; padding: 0.15rem 0.65rem; border-radius: 999px; font-weight: 600; font-size: 0.9rem;">${pegawai.status_pegawai}</span></div>` : ''}
            ${pegawai.pangkat_golongan ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">Pangkat/Gol</div><div style="font-size: 0.9rem; font-weight: 600;">${pegawai.pangkat_golongan}</div></div>` : ''}
            ${pegawai.bidang ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB; grid-column: 1/-1;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">Bidang</div><div style="font-size: 0.9rem; font-weight: 600;">${pegawai.bidang}</div></div>` : ''}
            ${pegawai.pendidikan_terakhir ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">Pendidikan</div><div style="font-size: 0.9rem; font-weight: 600;">${pegawai.pendidikan_terakhir}</div></div>` : ''}
            ${pegawai.tahun_bergabung ? `<div style="background: #F9FAFB; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #E5E7EB;"><div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem;">Thn Bergabung</div><div style="font-size: 0.9rem; font-weight: 600;">${pegawai.tahun_bergabung}</div></div>` : ''}
        </div>
        <button onclick="closeModal()" style="margin-top: 2rem; padding: 0.65rem 2rem; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; font-family: inherit;">Tutup</button>
    `;
    
    document.getElementById('modal-content').innerHTML = content;
    document.getElementById('modal-pegawai').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modal-pegawai').style.display = 'none';
    document.body.style.overflow = 'auto';
}
</script>
@endsection
