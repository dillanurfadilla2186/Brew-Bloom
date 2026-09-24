@extends('admin.layouts.admin')

@section('title', 'Edit Menu: ' . $menu->name)
@section('header_title', 'Edit Data Menu')

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

        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="admin-form-group">
                <label class="admin-form-label" for="name">Nama Menu *</label>
                <input type="text" name="name" id="name" class="admin-input" value="{{ old('name', $menu->name) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="category_id">Kategori Menu *</label>
                    <select name="category_id" id="category_id" class="admin-select" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="price">Harga (Rupiah) *</label>
                    <input type="number" name="price" id="price" class="admin-input" value="{{ old('price', $menu->price) }}" min="0" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="description">Deskripsi Menu</label>
                <textarea name="description" id="description" rows="3" class="admin-textarea">{{ old('description', $menu->description) }}</textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="image_url">URL Foto / Gambar (Online)</label>
                <input type="url" name="image_url" id="image_url" class="admin-input" value="{{ old('image_url', $menu->image) }}">
                
                @if($menu->image)
                    <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px;">
                        <img src="{{ $menu->image }}" alt="Preview" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd;">
                        <small style="color: var(--text-muted);">Foto saat ini</small>
                    </div>
                @endif
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label" for="image_file">Ganti dengan Upload File Foto Baru</label>
                <input type="file" name="image_file" id="image_file" class="admin-input" accept="image/*">
            </div>

            <div style="display: flex; gap: 30px; margin: 25px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}>
                    <span>Jadikan <strong>Signature / Unggulan</strong> (Tampil di Homepage)</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                    <span>Menu <strong>Tersedia</strong> untuk dipesan</span>
                </label>
            </div>

            <button type="submit" class="btn-admin btn-admin-primary" style="padding: 12px 24px; font-size: 14px;">
                Perbarui Data Menu
            </button>
        </form>
    </div>

@endsection
