@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2d3748;
    }

    .form-label .required {
        color: #f56565;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    textarea.form-control {
        min-height: 200px;
        resize: vertical;
        font-family: inherit;
    }

    .form-hint {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 5px;
    }

    .error-text {
        color: #f56565;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .image-preview {
        margin-top: 10px;
        max-width: 300px;
        border-radius: 8px;
        display: none;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e2e8f0;
    }

    .btn-submit {
        padding: 12px 30px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #5a67d8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        padding: 12px 30px;
        background: #e2e8f0;
        color: #2d3748;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .datalist-option {
        padding: 8px 12px;
        cursor: pointer;
    }

    .datalist-option:hover {
        background: #f7fafc;
    }
</style>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <div class="card-title">➕ Tambah Berita Baru</div>
        </div>

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">
                    Judul Berita <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="judul" 
                    class="form-control" 
                    value="{{ old('judul') }}" 
                    placeholder="Masukkan judul berita yang menarik..."
                    required
                >
                @error('judul')
                    <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="form-hint">Judul yang menarik akan meningkatkan engagement pembaca</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Kategori <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="kategori" 
                    class="form-control" 
                    value="{{ old('kategori') }}" 
                    list="kategori-list"
                    placeholder="Pilih atau ketik kategori baru..."
                    required
                >
                <datalist id="kategori-list">
                    @foreach ($kategoris as $kat)
                        <option value="{{ $kat }}">
                    @endforeach
                </datalist>
                @error('kategori')
                    <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="form-hint">Contoh: Teknologi, Pendidikan, Kesehatan, Ekonomi, dll.</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Ringkasan
                </label>
                <textarea 
                    name="ringkasan" 
                    class="form-control" 
                    placeholder="Ringkasan singkat berita (opsional, max 500 karakter)..."
                    maxlength="500"
                    style="min-height: 100px;"
                >{{ old('ringkasan') }}</textarea>
                @error('ringkasan')
                    <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="form-hint">Ringkasan yang baik membantu SEO dan menarik pembaca</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Konten Berita <span class="required">*</span>
                </label>
                <textarea 
                    name="konten" 
                    class="form-control" 
                    placeholder="Tulis konten berita lengkap di sini..."
                    required
                >{{ old('konten') }}</textarea>
                @error('konten')
                    <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="form-hint">Gunakan paragraf terstruktur untuk kemudahan membaca</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Gambar Utama
                </label>
                <input 
                    type="file" 
                    name="gambar" 
                    class="form-control" 
                    accept="image/*"
                    onchange="previewImage(event)"
                >
                <img id="image-preview" class="image-preview" alt="Preview">
                @error('gambar')
                    <div class="error-text">{{ $message }}</div>
                @enderror
                <div class="form-hint">Format: JPG, PNG, GIF (Max: 5MB). Ukuran rekomendasi: 1200x630px</div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Penulis <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="penulis" 
                    class="form-control" 
                    value="{{ old('penulis', Auth::user()->name) }}" 
                    placeholder="Nama penulis..."
                    required
                >
                @error('penulis')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input 
                        type="checkbox" 
                        id="aktif" 
                        name="aktif" 
                        value="1"
                        {{ old('aktif') ? 'checked' : '' }}
                    >
                    <label for="aktif" style="margin: 0; font-weight: normal; cursor: pointer;">
                        ✅ Publikasikan berita ini (jika tidak dicentang, berita akan disimpan sebagai draft)
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    💾 Simpan Berita
                </button>
                <a href="{{ route('admin.berita.index') }}" class="btn-cancel">
                    ↩️ Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('image-preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
