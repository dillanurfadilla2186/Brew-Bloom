@extends('admin.layouts.admin')

@section('title', 'Tambah Menu Baru')
@section('header_title', 'Tambah Menu Baru')

@section('content')

    <div class="admin-card form-card">
        <div style="margin-bottom: 25px;">
            <a href="{{ route('admin.menus.index') }}" class="btn-admin btn-admin-outline btn-admin-sm">← Kembali ke Daftar Menu</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="admin-form-group">
                <label class="admin-form-label" for="name">Nama Menu *</label>
                <input type="text" name="name" id="name" class="admin-input" value="{{ old('name') }}" placeholder="Contoh: Hazelnut Caramel Frappe" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="category_id">Kategori Menu *</label>
                    <select name="category_id" id="category_id" class="admin-select" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="price">Harga (Rupiah) *</label>
                    <input type="number" name="price" id="price" class="admin-input" value="{{ old('price') }}" placeholder="Contoh: 28000" min="0" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="description">Deskripsi Menu</label>
                <textarea name="description" id="description" rows="3" class="admin-textarea" placeholder="Tuliskan bahan, rasa, atau keunikan menu...">{{ old('description') }}</textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="image_url">URL Foto / Gambar (Online)</label>
                <input type="url" name="image_url" id="image_url" class="admin-input" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-xxxx">
                <small style="color: var(--text-muted); font-size: 12px;">Bisa menggunakan link foto dari Unsplash atau upload file di bawah ini.</small>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="image_file">Atau Upload Foto Menu</label>
                <input type="file" name="image_file" id="image_file" class="admin-input" accept="image/*">
            </div>

            <div style="display: flex; gap: 30px; margin: 25px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Jadikan <strong>Signature / Unggulan</strong> (Tampil di Homepage)</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                    <span>Menu <strong>Tersedia</strong> untuk dipesan</span>
                </label>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 12px 24px; font-size: 14px;">
                Simpan Menu Baru
            </button>
        </form>
    </div>

@endsection
