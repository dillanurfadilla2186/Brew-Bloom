@extends('admin.layouts.admin')

@section('title', 'Kelola Menu')
@section('header_title', 'Daftar Menu Makanan & Minuman')

@section('content')

    <!-- Bulk Action Form & Toolbar -->
    <form id="bulkMenuForm" action="{{ route('admin.menus.bulk') }}" method="POST">
        @csrf

        <div id="bulkToolbar" class="bulk-toolbar" style="display: none;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span>🍽️ <strong id="selectedCount">0</strong> menu dipilih</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <select name="action" id="bulkActionSelect" class="admin-select" style="padding: 5px 10px; font-size: 13px; width: auto; background: #FFFFFF; color: #333;" required>
                    <option value="">-- Pilih Aksi Massal --</option>
                    <option value="available">✅ Set Status: Tersedia</option>
                    <option value="unavailable">🚫 Set Status: Habis / Non-Aktif</option>
                    <option value="delete">🗑️ Hapus yang Dipilih</option>
                </select>
                <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm" onclick="return confirmBulkMenuAction()">Terapkan</button>
            </div>
        </div>

        <div class="admin-card">
            <!-- Top Action & Search Bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="text" name="search" value="{{ request('search') }}" form="menuFilterForm" placeholder="Cari nama / deskripsi..." class="admin-input" style="width: 220px; padding: 8px 14px;">
                    
                    <select name="category" form="menuFilterForm" class="admin-select" style="width: 180px; padding: 8px 14px;">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" form="menuFilterForm" class="btn-admin btn-admin-outline">Filter</button>
                    @if(request('search') || request('category'))
                        <a href="{{ route('admin.menus.index') }}" class="btn-admin btn-admin-outline">Reset</a>
                    @endif
                </div>

                <a href="{{ route('admin.menus.create') }}" class="btn-admin btn-admin-primary">
                    + Tambah Menu Baru
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center;">
                                <input type="checkbox" id="selectAllMenus" class="custom-checkbox" title="Pilih Semua">
                            </th>
                            <th style="width: 45px; text-align: center;">No</th>
                            <th style="width: 65px;">Foto</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Signature</th>
                            <th>Status</th>
                            <th style="text-align: right; width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $idx => $menu)
                            @php
                                $rowNumber = $menus->firstItem() + $idx;
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="ids[]" value="{{ $menu->id }}" class="custom-checkbox menu-checkbox">
                                </td>
                                <td style="text-align: center; color: var(--text-muted); font-weight: 500;">
                                    {{ $rowNumber }}
                                </td>
                                <td>
                                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}" style="width: 46px; height: 46px; border-radius: 8px; object-fit: cover; border: 1px solid #EAEAEA;">
                                </td>
                                <td>
                                    <strong style="color: var(--dark); font-size: 14px;">{{ $menu->name }}</strong>
                                    <div style="color: var(--text-muted); font-size: 12px;">{{ Str::limit($menu->description, 45) }}</div>
                                </td>
                                <td>
                                    <span style="background: #F3F4F6; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                        {{ $menu->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: var(--primary-dark);">{{ $menu->formatted_price }}</strong>
                                </td>
                                <td>
                                    @if($menu->is_featured)
                                        <span style="color: #F59E0B; font-weight: 600; font-size: 12px;">⭐ Ya</span>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 12px;">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" 
                                            class="badge {{ $menu->is_available ? 'badge-active' : 'badge-inactive' }}" 
                                            style="cursor: pointer; border: none;" 
                                            onclick="toggleMenuStatus('{{ $menu->id }}')"
                                            title="Klik untuk ganti status ketersediaan">
                                        {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                    </button>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn-admin btn-admin-outline btn-admin-sm">Edit</a>
                                        <button type="button" class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteSingleMenu('{{ $menu->id }}', '{{ $menu->name }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 35px; color: var(--text-muted);">
                                    Tidak ada data menu yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $menus->links() }}
            </div>
        </div>
    </form>

    <!-- Hidden Filter Form for GET Requests -->
    <form id="menuFilterForm" action="{{ route('admin.menus.index') }}" method="GET">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
    </form>

    <!-- Single Delete Form -->
    <form id="singleDeleteMenuForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Single Toggle Availability Form -->
    <form id="singleToggleMenuForm" action="" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAllMenus = document.getElementById('selectAllMenus');
        const menuCheckboxes = document.querySelectorAll('.menu-checkbox');
        const bulkToolbar = document.getElementById('bulkToolbar');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkToolbar() {
            const checked = document.querySelectorAll('.menu-checkbox:checked');
            selectedCount.innerText = checked.length;
            if (checked.length > 0) {
                bulkToolbar.style.display = 'flex';
            } else {
                bulkToolbar.style.display = 'none';
            }
            if (selectAllMenus) {
                selectAllMenus.checked = checked.length === menuCheckboxes.length && menuCheckboxes.length > 0;
            }
        }

        if (selectAllMenus) {
            selectAllMenus.addEventListener('change', () => {
                menuCheckboxes.forEach(cb => cb.checked = selectAllMenus.checked);
                updateBulkToolbar();
            });
        }

        menuCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkToolbar);
        });
    });

    function confirmBulkMenuAction() {
        const select = document.getElementById('bulkActionSelect');
        if (!select.value) {
            alert('Silakan pilih aksi yang ingin diterapkan.');
            return false;
        }
        if (select.value === 'delete') {
            return confirm('Apakah Anda yakin ingin menghapus seluruh menu yang dipilih?');
        }
        return confirm('Apakah Anda yakin ingin mengubah status menu yang dipilih?');
    }

    function deleteSingleMenu(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus menu '${name}'?`)) {
            const form = document.getElementById('singleDeleteMenuForm');
            form.action = `/admin/menus/${id}`;
            form.submit();
        }
    }

    function toggleMenuStatus(id) {
        const form = document.getElementById('singleToggleMenuForm');
        form.action = `/admin/menus/${id}/toggle`;
        form.submit();
    }
</script>
@endpush
