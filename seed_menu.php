<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$m = [
    ['label'=>'Beranda', 'url'=>'/'], 
    ['label'=>'Profil', 'url'=>'/profil'], 
    ['label'=>'Berita', 'url'=>'/berita'], 
    ['label'=>'Pengumuman', 'url'=>'/pengumuman'], 
    ['label'=>'Layanan', 'url'=>'/layanan'], 
    ['label'=>'Agenda', 'url'=>'/agenda'], 
    ['label'=>'Galeri', 'url'=>'/galeri'], 
    ['label'=>'PPID', 'url'=>'/ppid'], 
    ['label'=>'Kontak', 'url'=>'/kontak']
]; 
foreach($m as $k => $v) { 
    \App\Models\Menu::create([
        'label'=>$v['label'], 
        'url'=>$v['url'], 
        'urutan'=>$k+1, 
        'aktif'=>true
    ]); 
} 
echo "Seeded!\n";
