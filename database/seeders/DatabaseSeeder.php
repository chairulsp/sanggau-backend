<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Layanan;
use App\Models\Skpd;
use App\Models\Banner;
use App\Models\Pengumuman;
use App\Models\Statistik;
use App\Models\ProfilDiskominfo;
use App\Models\StrukturOrganisasi;
use App\Models\Dokumen;
use App\Models\Ppid;
use App\Models\GaleriVideo;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ===================== USERS =====================
        \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@diskominfo.sanggau.go.id'],
            [
                'name'      => 'Superadmin Diskominfo',
                'password'  => bcrypt('password'),
                'role'      => 'superadmin',
                'kecamatan' => null,
            ]
        );
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@diskominfo.sanggau.go.id'],
            [
                'name'      => 'Admin Diskominfo',
                'password'  => bcrypt('password'),
                'role'      => 'admin',
                'kecamatan' => null,
            ]
        );

        // ===================== PROFIL DISKOMINFO =====================
        ProfilDiskominfo::firstOrCreate(
            ['nama_dinas' => 'Dinas Komunikasi dan Informatika'],
            [
                'singkatan'     => 'Diskominfo',
                'nama_kepala'   => 'Drs. H. Ahmad Sarwono, M.Si.',
                'nip_kepala'    => '196701011994031001',
                'nama_kabupaten'=> 'Kabupaten Sanggau',
                'visi'          => 'Terwujudnya Masyarakat Sanggau yang Informatif, Cerdas, dan Berdaya Saing melalui Pemanfaatan Teknologi Informasi dan Komunikasi',
                'misi'          => "1. Meningkatkan kualitas layanan komunikasi dan informatika yang profesional\n2. Mendorong penerapan e-government dalam tata kelola pemerintahan\n3. Memberdayakan masyarakat melalui literasi digital\n4. Memperkuat infrastruktur teknologi informasi dan komunikasi\n5. Meningkatkan transparansi informasi publik",
                'sejarah'       => 'Dinas Komunikasi dan Informatika Kabupaten Sanggau dibentuk berdasarkan Peraturan Daerah Kabupaten Sanggau tentang Pembentukan dan Susunan Perangkat Daerah. Dinas ini merupakan penggabungan dari fungsi komunikasi, informatika, dan statistik yang sebelumnya tersebar di beberapa instansi pemerintah daerah.',
                'tupoksi'       => "Tugas pokok Diskominfo Sanggau adalah melaksanakan urusan pemerintahan bidang komunikasi dan informatika, statistik, dan persandian.\n\nFungsi:\n1. Perumusan kebijakan bidang komunikasi dan informatika\n2. Pelaksanaan kebijakan bidang komunikasi dan informatika\n3. Pelaksanaan evaluasi dan pelaporan\n4. Pelaksanaan administrasi dinas\n5. Pengelolaan sistem informasi pemerintah daerah",
                'alamat'        => 'Jl. Jenderal Sudirman No. 3, Sanggau, Kalimantan Barat 78511',
                'telepon'       => '(0564) 21234',
                'fax'           => '(0564) 21235',
                'email'         => 'diskominfo@sanggau.go.id',
                'website'       => 'https://diskominfo.sanggaukab.go.id',
                'jam_kerja'     => 'Senin - Jumat: 07.30 - 16.00 WIB',
                'facebook'      => 'https://facebook.com/diskominfoSanggau',
                'instagram'     => 'https://instagram.com/diskominfo_sanggau',
                'youtube'       => 'https://youtube.com/@diskominfoSanggau',
            ]
        );

        // ===================== STRUKTUR ORGANISASI =====================
        $strukturs = [
            ['nama' => 'Drs. H. Ahmad Sarwono, M.Si.', 'jabatan' => 'Kepala Dinas', 'nip' => '196701011994031001', 'urutan' => 1],
            ['nama' => 'Ir. Hendra Kusuma, M.T.', 'jabatan' => 'Sekretaris', 'nip' => '197203051998031002', 'urutan' => 2],
            ['nama' => 'Dra. Siti Rahayu, M.M.', 'jabatan' => 'Kabid Informasi & Komunikasi Publik', 'nip' => '196906121995032001', 'urutan' => 3],
            ['nama' => 'Budi Santoso, S.Kom., M.T.', 'jabatan' => 'Kabid Teknologi Informatika', 'nip' => '197504201999031003', 'urutan' => 4],
            ['nama' => 'Agus Priyanto, S.Sos.', 'jabatan' => 'Kabid Statistik & Persandian', 'nip' => '197801152003121001', 'urutan' => 5],
            ['nama' => 'Rini Wulandari, S.T.', 'jabatan' => 'Kasi Pengelolaan Media Komunikasi', 'nip' => '198005102005022002', 'urutan' => 6],
        ];
        foreach ($strukturs as $s) {
            StrukturOrganisasi::firstOrCreate(['nip' => $s['nip']], array_merge($s, [
                'foto'  => null,
                'aktif' => true,
            ]));
        }

        // ===================== MENU =====================
        $menus = [
            ['label' => 'Beranda',       'url' => '/',                     'ikon' => '🏠', 'urutan' => 1],
            ['label' => 'Profil',        'url' => '/profil',               'ikon' => '🏛️', 'urutan' => 2],
            ['label' => 'Berita',        'url' => '/berita',               'ikon' => '📰', 'urutan' => 3],
            ['label' => 'Pengumuman',    'url' => '/pengumuman',           'ikon' => '📢', 'urutan' => 4],
            ['label' => 'Layanan',       'url' => '/layanan',              'ikon' => '🛠️', 'urutan' => 5],
            ['label' => 'Agenda',        'url' => '/agenda',               'ikon' => '📅', 'urutan' => 6],
            ['label' => 'Galeri',        'url' => '/galeri',               'ikon' => '🖼️', 'urutan' => 7],
            ['label' => 'PPID',          'url' => '/ppid',                 'ikon' => '📋', 'urutan' => 8],
            ['label' => 'Pengaduan',     'url' => '/pengaduan',            'ikon' => '💬', 'urutan' => 9],
            ['label' => 'Download',      'url' => '/download',             'ikon' => '⬇️', 'urutan' => 10],
            ['label' => 'Kontak',        'url' => '/kontak',               'ikon' => '📞', 'urutan' => 11],
        ];
        foreach ($menus as $menu) {
            \App\Models\Menu::firstOrCreate(['url' => $menu['url']], array_merge($menu, ['aktif' => true]));
        }

        // ===================== BANNER =====================
        $banners = [
            [
                'judul'    => 'Selamat Datang di Diskominfo Kabupaten Sanggau',
                'subjudul' => 'Menuju Sanggau yang Informatif dan Berdaya Saing Digital',
                'gambar'   => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600',
                'urutan'   => 1,
            ],
            [
                'judul'    => 'Layanan Digital untuk Masyarakat',
                'subjudul' => 'Mudah, Cepat, dan Transparan',
                'gambar'   => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&q=80&w=1600',
                'urutan'   => 2,
            ],
            [
                'judul'    => 'Literasi Digital Masyarakat Sanggau',
                'subjudul' => 'Bersama Membangun Ekosistem Digital yang Sehat',
                'gambar'   => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=1600',
                'urutan'   => 3,
            ],
        ];
        foreach ($banners as $b) {
            Banner::firstOrCreate(['urutan' => $b['urutan']], $b);
        }

        // ===================== LAYANAN =====================
        $layanans = [
            ['nama' => 'SIPD (Sistem Informasi Pemerintah Daerah)', 'ikon' => '🏛️', 'link' => 'https://sipd.kemendagri.go.id', 'warna' => '#1A56DB', 'deskripsi' => 'Sistem informasi untuk pengelolaan keuangan dan perencanaan daerah'],
            ['nama' => 'SIAK (Kependudukan)',        'ikon' => '👤', 'link' => '#', 'warna' => '#1A56DB', 'deskripsi' => 'Sistem Informasi Administrasi Kependudukan'],
            ['nama' => 'OSS (Perizinan Berusaha)',   'ikon' => '📝', 'link' => 'https://oss.go.id', 'warna' => '#0EA5E9', 'deskripsi' => 'Online Single Submission untuk perizinan berusaha'],
            ['nama' => 'LAPOR! (Pengaduan)',         'ikon' => '📣', 'link' => 'https://lapor.go.id', 'warna' => '#F59E0B', 'deskripsi' => 'Layanan Aspirasi dan Pengaduan Online Rakyat'],
            ['nama' => 'SP4N-LAPOR Sanggau',        'ikon' => '💬', 'link' => '#', 'warna' => '#10B981', 'deskripsi' => 'Pengaduan masyarakat Kabupaten Sanggau'],
            ['nama' => 'SiCepat (Perizinan)',        'ikon' => '⚡', 'link' => '#', 'warna' => '#6366F1', 'deskripsi' => 'Pelayanan Perizinan Terpadu Sanggau'],
            ['nama' => 'e-Musrenbang',               'ikon' => '📊', 'link' => '#', 'warna' => '#EC4899', 'deskripsi' => 'Musyawarah Perencanaan Pembangunan Digital'],
            ['nama' => 'Website Resmi Sanggau',      'ikon' => '🌐', 'link' => 'https://sanggaukab.go.id', 'warna' => '#1A56DB', 'deskripsi' => 'Portal resmi Pemerintah Kabupaten Sanggau'],
        ];
        foreach ($layanans as $i => $l) {
            Layanan::firstOrCreate(['nama' => $l['nama']], array_merge($l, ['urutan' => $i + 1]));
        }

        // ===================== STATISTIK =====================
        $statistiks = [
            ['nama' => 'Kecamatan',           'nilai' => '15',      'ikon' => '🗺️', 'urutan' => 1],
            ['nama' => 'Desa/Kelurahan',      'nilai' => '169',     'ikon' => '🏘️', 'urutan' => 2],
            ['nama' => 'Penduduk',            'nilai' => '489.430', 'ikon' => '👥', 'urutan' => 3],
            ['nama' => 'SKPD/OPD',           'nilai' => '32',      'ikon' => '🏢', 'urutan' => 4],
            ['nama' => 'Layanan Digital',     'nilai' => '12',      'ikon' => '💻', 'urutan' => 5],
            ['nama' => 'Website Terintegrasi','nilai' => '25',      'ikon' => '🌐', 'urutan' => 6],
        ];
        foreach ($statistiks as $s) {
            Statistik::firstOrCreate(['nama' => $s['nama']], $s);
        }

        // ===================== BERITA =====================
        $kategoris = ['Teknologi', 'e-Government', 'Literasi Digital', 'Infrastruktur', 'Kebijakan', 'Kegiatan'];
        $imgs = [
            'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&q=80&w=800',
            'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&q=80&w=800',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=800',
            'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800',
            'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&q=80&w=800',
            'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=80&w=800',
        ];
        $judul_beritas = [
            'Diskominfo Sanggau Luncurkan Aplikasi Layanan Digital Terintegrasi',
            'Bimtek Literasi Digital untuk ASN Kabupaten Sanggau Tahun 2024',
            'Pembangunan Jaringan Fiber Optik di 5 Kecamatan Sanggau Rampung',
            'Pemkab Sanggau Raih Penghargaan Smart City dari Kemendagri',
            'Sosialisasi e-Government untuk Kelurahan dan Desa se-Kabupaten Sanggau',
            'Workshop Keamanan Siber untuk Instansi Pemerintah Kabupaten Sanggau',
            'Diskominfo Gelar Festival Teknologi dan Inovasi Sanggau 2024',
            'Peningkatan Bandwidth Internet di Kantor Pemerintah Sanggau',
        ];
        foreach ($judul_beritas as $i => $judul) {
            $slug = Str::slug($judul) . '-' . ($i + 1);
            Berita::firstOrCreate(['slug' => $slug], [
                'judul'        => $judul,
                'slug'         => $slug,
                'ringkasan'    => 'Diskominfo Kabupaten Sanggau terus berkomitmen dalam meningkatkan layanan digital dan informatika untuk masyarakat Sanggau yang lebih maju dan modern.',
                'konten'       => '<p>Dinas Komunikasi dan Informatika Kabupaten Sanggau terus berinovasi dalam memberikan pelayanan terbaik kepada masyarakat. Program digitalisasi yang dijalankan bertujuan untuk mempercepat transformasi digital di lingkungan pemerintahan dan masyarakat.</p><p>Kepala Diskominfo Sanggau menyampaikan bahwa program ini merupakan bagian dari visi besar Kabupaten Sanggau untuk menjadi daerah yang maju dan berdaya saing di era digital. Berbagai upaya telah dilakukan mulai dari pembangunan infrastruktur hingga peningkatan kapasitas SDM.</p><p>Dengan dukungan dari semua pihak, diharapkan Kabupaten Sanggau dapat menjadi contoh terbaik dalam implementasi e-government di Kalimantan Barat.</p>',
                'gambar'       => $imgs[$i % count($imgs)],
                'penulis'      => 'Diskominfo Sanggau',
                'kategori'     => $kategoris[$i % count($kategoris)],
                'published_at' => now()->subDays($i * 3),
            ]);
        }

        // ===================== PENGUMUMAN =====================
        $pengumumans = [
            ['judul' => 'Jadwal Pemeliharaan Sistem Jaringan SKPD', 'konten' => 'Diberitahukan kepada seluruh OPD bahwa akan dilakukan pemeliharaan jaringan pada hari Sabtu, 10 Februari 2024 pukul 22.00 - 06.00 WIB. Selama pemeliharaan, akses internet di lingkungan Pemkab Sanggau akan terganggu sementara.', 'tanggal_mulai' => now()->addDays(3), 'tanggal_selesai' => now()->addDays(4), 'penting' => true],
            ['judul' => 'Pendaftaran Pelatihan Digital Marketing UMKM', 'konten' => 'Diskominfo Sanggau membuka pendaftaran pelatihan digital marketing gratis untuk pelaku UMKM. Pendaftaran dibuka hingga 28 Februari 2024.', 'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addDays(14), 'penting' => false],
            ['judul' => 'Update Kebijakan Keamanan Siber ASN', 'konten' => 'Seluruh ASN Kabupaten Sanggau diwajibkan untuk mengikuti sosialisasi kebijakan keamanan siber terbaru. Kegiatan ini akan dilaksanakan secara online melalui platform Zoom.', 'tanggal_mulai' => now()->addDays(7), 'tanggal_selesai' => now()->addDays(8), 'penting' => true],
        ];
        foreach ($pengumumans as $p) {
            Pengumuman::firstOrCreate(['judul' => $p['judul']], $p);
        }

        // ===================== AGENDA =====================
        $agendas = [
            ['judul' => 'Rapat Koordinasi Tim TIK Kabupaten Sanggau', 'tanggal_mulai' => now()->addDays(2), 'tanggal_selesai' => now()->addDays(2), 'lokasi' => 'Aula Diskominfo Sanggau', 'deskripsi' => 'Rapat koordinasi rutin tim teknologi informasi dan komunikasi.'],
            ['judul' => 'Pelatihan Literasi Digital Masyarakat', 'tanggal_mulai' => now()->addDays(5), 'tanggal_selesai' => now()->addDays(7), 'lokasi' => 'Gedung Serbaguna Sanggau', 'deskripsi' => 'Program literasi digital untuk masyarakat umum Kabupaten Sanggau.'],
            ['judul' => 'Workshop Cybersecurity untuk ASN', 'tanggal_mulai' => now()->addDays(10), 'tanggal_selesai' => now()->addDays(10), 'lokasi' => 'Ruang Rapat Bupati', 'deskripsi' => 'Workshop keamanan siber bagi ASN Pemkab Sanggau.'],
            ['judul' => 'Launching Aplikasi Sanggau Digital', 'tanggal_mulai' => now()->addDays(15), 'tanggal_selesai' => now()->addDays(15), 'lokasi' => 'Pendopo Bupati Sanggau', 'deskripsi' => 'Peresmian aplikasi layanan digital terpadu Kabupaten Sanggau.'],
        ];
        foreach ($agendas as $a) {
            Agenda::firstOrCreate(['judul' => $a['judul']], $a);
        }

        // ===================== DOKUMEN =====================
        $dokumens = [
            ['judul' => 'Renstra Diskominfo 2021-2026', 'kategori' => 'Perencanaan', 'tahun' => '2021', 'deskripsi' => 'Rencana Strategis Dinas Komunikasi dan Informatika Kabupaten Sanggau 2021-2026', 'file_url' => '#'],
            ['judul' => 'Laporan Tahunan Diskominfo 2023', 'kategori' => 'Laporan', 'tahun' => '2023', 'deskripsi' => 'Laporan kinerja tahunan Diskominfo Sanggau tahun 2023', 'file_url' => '#'],
            ['judul' => 'SOP Pelayanan Publik', 'kategori' => 'Kebijakan', 'tahun' => '2024', 'deskripsi' => 'Standar Operasional Prosedur layanan publik Diskominfo', 'file_url' => '#'],
            ['judul' => 'Perda Penyelenggaraan Komunikasi dan Informatika', 'kategori' => 'Peraturan', 'tahun' => '2022', 'deskripsi' => 'Peraturan Daerah tentang penyelenggaraan komunikasi dan informatika di Kabupaten Sanggau', 'file_url' => '#'],
        ];
        foreach ($dokumens as $d) {
            Dokumen::firstOrCreate(['judul' => $d['judul']], array_merge($d, ['aktif' => true]));
        }

        // ===================== PPID =====================
        $ppids = [
            ['judul' => 'Profil Diskominfo Sanggau', 'kategori' => 'Wajib Tersedia', 'tahun' => '2024', 'urutan' => 1, 'deskripsi' => 'Informasi dasar tentang Diskominfo Sanggau'],
            ['judul' => 'Program dan Kegiatan Tahunan', 'kategori' => 'Berkala', 'tahun' => '2024', 'urutan' => 2, 'deskripsi' => 'Daftar program dan kegiatan tahunan Diskominfo'],
            ['judul' => 'Laporan Keuangan', 'kategori' => 'Berkala', 'tahun' => '2023', 'urutan' => 3, 'deskripsi' => 'Laporan realisasi anggaran Diskominfo Sanggau'],
            ['judul' => 'Standar Pelayanan Publik', 'kategori' => 'Wajib Tersedia', 'tahun' => '2024', 'urutan' => 4, 'deskripsi' => 'Standar layanan publik yang disediakan Diskominfo'],
        ];
        foreach ($ppids as $p) {
            Ppid::firstOrCreate(['judul' => $p['judul']], array_merge($p, ['aktif' => true]));
        }

        // ===================== VIDEO =====================
        $videos = [
            ['judul' => 'Profil Diskominfo Sanggau 2024', 'url_youtube' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'tanggal' => now()->subDays(10), 'urutan' => 1],
            ['judul' => 'Launching Aplikasi Sanggau Digital', 'url_youtube' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'tanggal' => now()->subDays(30), 'urutan' => 2],
        ];
        foreach ($videos as $v) {
            GaleriVideo::firstOrCreate(['judul' => $v['judul']], array_merge($v, ['aktif' => true]));
        }
    }
}
