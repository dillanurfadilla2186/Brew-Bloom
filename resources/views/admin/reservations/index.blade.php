@extends('admin.layouts.admin')

@section('title', 'Data Reservasi Meja')
@section('header_title', 'Kelola Reservasi Pelanggan')

@push('styles')
<style>
    .status-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .status-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        color: var(--text);
        background: #FFFFFF;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }
    .status-pill:hover, .status-pill.active {
        background: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
    }
    .menu-order-pill {
        display: inline-block;
        background: #FDF3E9;
        color: var(--primary-dark);
        border: 1px solid #EAD8C7;
        border-radius: 6px;
        padding: 2px 6px;
        font-size: 11px;
        margin: 2px 2px 0 0;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

    <!-- Status Filter Pills -->
    <div class="status-filters">
        <a href="{{ route('admin.reservations.index', ['status' => 'all', 'date' => request('date'), 'search' => request('search')]) }}" 
           class="status-pill {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">
            Semua ({{ $stats['all'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'pending', 'date' => request('date'), 'search' => request('search')]) }}" 
           class="status-pill {{ request('status') === 'pending' ? 'active' : '' }}" style="{{ request('status') !== 'pending' ? 'border-color: #FCD34D;' : '' }}">
            ⏳ Pending ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'confirmed', 'date' => request('date'), 'search' => request('search')]) }}" 
           class="status-pill {{ request('status') === 'confirmed' ? 'active' : '' }}" style="{{ request('status') !== 'confirmed' ? 'border-color: #A7F3D0;' : '' }}">
            ✅ Confirmed ({{ $stats['confirmed'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'completed', 'date' => request('date'), 'search' => request('search')]) }}" 
           class="status-pill {{ request('status') === 'completed' ? 'active' : '' }}">
            🎉 Completed ({{ $stats['completed'] }})
        </a>
        <a href="{{ route('admin.reservations.index', ['status' => 'cancelled', 'date' => request('date'), 'search' => request('search')]) }}" 
           class="status-pill {{ request('status') === 'cancelled' ? 'active' : '' }}" style="{{ request('status') !== 'cancelled' ? 'border-color: #FCA5A5;' : '' }}">
            ❌ Cancelled ({{ $stats['cancelled'] }})
        </a>
    </div>

    <!-- Bulk Action Form & Toolbar -->
    <form id="bulkForm" action="{{ route('admin.reservations.bulk') }}" method="POST">
        @csrf

        <div id="bulkToolbar" class="bulk-toolbar" style="display: none;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span>✨ <strong id="selectedCount">0</strong> data reservasi dipilih</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <select name="action" id="bulkActionSelect" class="admin-select" style="padding: 5px 10px; font-size: 13px; width: auto; background: #FFFFFF; color: #333;" required>
                    <option value="">-- Pilih Aksi Massal --</option>
                    <option value="confirmed">✅ Ubah Status: Confirmed</option>
                    <option value="completed">🎉 Ubah Status: Completed</option>
                    <option value="cancelled">❌ Ubah Status: Cancelled</option>
                    <option value="delete">🗑️ Hapus yang Dipilih</option>
                </select>
                <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm" onclick="return confirmBulkAction()">Terapkan</button>
            </div>
        </div>

        <div class="admin-card">
            <!-- Search & Date Filter -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="text" name="search" value="{{ request('search') }}" form="filterForm" placeholder="Cari kode / nama / WA..." class="admin-input" style="width: 220px; padding: 8px 14px;">
                    <input type="date" name="date" value="{{ request('date') }}" form="filterForm" class="admin-input" style="width: 170px; padding: 8px 14px;">
                    <button type="submit" form="filterForm" class="btn-admin btn-admin-outline">Filter</button>
                    @if(request('search') || request('date') || (request('status') && request('status') !== 'all'))
                        <a href="{{ route('admin.reservations.index') }}" class="btn-admin btn-admin-outline">Reset</a>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center;">
                                <input type="checkbox" id="selectAll" class="custom-checkbox" title="Pilih Semua">
                            </th>
                            <th style="width: 45px; text-align: center;">No</th>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Kontak & WA</th>
                            <th>Jadwal Booking</th>
                            <th>Tamu & Area</th>
                            <th>Menu Pre-Order</th>
                            <th>Status</th>
                            <th style="text-align: right; min-width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $idx => $res)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $res->customer_phone);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                                
                                $menuListStr = "";
                                if ($res->items->isNotEmpty()) {
                                    $menuListStr = "\n\nMenu pesanan Anda:\n";
                                    foreach ($res->items as $it) {
                                        $menuListStr .= "- {$it->quantity}x {$it->menu_name} ({$it->formatted_subtotal})\n";
                                    }
                                    $menuListStr .= "Estimasi Total: {$res->formatted_total}";
                                }

                                $waText = "Halo Kak {$res->customer_name}, kami dari Brew & Bloom Cafe ingin mengonfirmasi bahwa reservasi meja Anda (Kode: {$res->booking_code}) untuk tanggal {$res->reservation_date->format('d M Y')} jam {$res->reservation_time} WIB telah KAMI SETUJUI.{$menuListStr}\n\nSampai jumpa di Brew & Bloom!";
                                $waUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode($waText);

                                $rowNumber = $reservations->firstItem() + $idx;

                                // Build detail data for modal
                                $modalData = [
                                    'id' => $res->id,
                                    'code' => $res->booking_code,
                                    'name' => $res->customer_name,
                                    'phone' => $res->customer_phone,
                                    'email' => $res->customer_email ?: '-',
                                    'date' => $res->reservation_date->format('d F Y'),
                                    'time' => $res->reservation_time . ' WIB',
                                    'guests' => $res->guests . ' Orang',
                                    'area' => $res->seating_area,
                                    'payment_method' => $res->payment_method_label,
                                    'payment_status' => $res->payment_status_label,
                                    'notes' => $res->notes ?: '-',
                                    'status' => $res->status,
                                    'cancellation_reason' => $res->cancellation_reason ?: null,
                                    'cancelled_at' => $res->cancelled_at ? $res->cancelled_at->format('d M Y H:i') : null,
                                    'total' => $res->formatted_total,
                                    'created_at' => $res->created_at->format('d M Y H:i'),
                                    'items' => $res->items->map(function($i) {
                                        return [
                                            'name' => $i->menu_name,
                                            'qty' => $i->quantity,
                                            'price' => $i->formatted_price,
                                            'subtotal' => $i->formatted_subtotal,
                                        ];
                                    }),
                                    'wa_url' => $waUrl,
                                    'pdf_url' => route('reservation.pdf', $res->booking_code),
                                    'status_url' => route('admin.reservations.status', $res),
                                ];
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="ids[]" value="{{ $res->id }}" class="custom-checkbox row-checkbox">
                                </td>
                                <td style="text-align: center; color: var(--text-muted); font-weight: 500;">
                                    {{ $rowNumber }}
                                </td>
                                <td>
                                    <strong style="color: var(--primary-dark);">{{ $res->booking_code }}</strong>
                                    <div style="color: var(--text-muted); font-size: 11px;">{{ $res->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <strong>{{ $res->customer_name }}</strong>
                                    @if($res->notes)
                                        <div style="color: var(--text-muted); font-size: 11px; font-style: italic;">"{{ Str::limit($res->notes, 30) }}"</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-admin btn-admin-sm" style="background: #25D366; color: #FFFFFF;" title="Chat WhatsApp Pelanggan">
                                        💬 {{ $res->customer_phone }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $res->reservation_date->format('d M Y') }}</strong>
                                    <div style="color: var(--text-muted); font-size: 12px;">{{ $res->reservation_time }} WIB</div>
                                </td>
                                <td>
                                    <div><strong>{{ $res->guests }} Orang</strong></div>
                                    <span style="background: #F3F4F6; padding: 2px 8px; border-radius: 12px; font-size: 11px;">
                                        {{ $res->seating_area }}
                                    </span>
                                </td>
                                <td style="min-width: 170px;">
                                    @if($res->items->isNotEmpty())
                                        <div>
                                            @foreach($res->items as $it)
                                                <span class="menu-order-pill">{{ $it->quantity }}x {{ $it->menu_name }}</span>
                                            @endforeach
                                        </div>
                                        <div style="font-weight: 700; color: var(--coffee); font-size: 12px; margin-top: 4px;">
                                            Total: {{ $res->formatted_total }}
                                        </div>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 12px;">Tanpa Pre-Order</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $res->status }}">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 5px; align-items: center;">
                                        <!-- Detail Button -->
                                        <button type="button" 
                                                class="btn-admin btn-admin-primary btn-admin-sm btn-detail" 
                                                data-detail='@json($modalData)'
                                                title="Lihat Detail Lengkap">
                                            🔍 Detail
                                        </button>

                                        <!-- PDF Button -->
                                        <a href="{{ route('reservation.pdf', $res->booking_code) }}" target="_blank" class="btn-admin btn-admin-outline btn-admin-sm" title="Unduh PDF">
                                            📄 PDF
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteSingleItem('{{ $res->id }}', '{{ $res->booking_code }}')" title="Hapus">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 35px; color: var(--text-muted);">
                                    Belum ada data reservasi yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $reservations->links() }}
            </div>
        </div>
    </form>

    <!-- Hidden Filter Form for GET Requests -->
    <form id="filterForm" action="{{ route('admin.reservations.index') }}" method="GET">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
    </form>

    <!-- Single Delete Form -->
    <form id="singleDeleteForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Interactive Detail Modal -->
    <div id="detailModal" class="admin-modal-backdrop">
        <div class="admin-modal">
            <div class="admin-modal-header">
                <h3>📋 Detail Lengkap Reservasi: <span id="mBookingCode" style="color: var(--primary);"></span></h3>
                <button type="button" class="admin-modal-close" onclick="closeDetailModal()">&times;</button>
            </div>

            <div class="admin-modal-body">
                <!-- Status & Booking Time Banner -->
                <div style="display: flex; justify-content: space-between; align-items: center; background: #FDFBF8; border: 1px solid #EAE3DA; padding: 12px 18px; border-radius: 12px; margin-bottom: 20px;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-muted);">WAKTU PEMESANAN</div>
                        <strong id="mCreatedAt" style="font-size: 13px;"></strong>
                    </div>
                    <div>
                        <span id="mStatusBadge" class="badge"></span>
                    </div>
                </div>

                <!-- Customer & Schedule Details Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; font-size: 13px;">
                    <div style="background: #FAFAFA; padding: 12px; border-radius: 10px; border: 1px solid #EEEEEE;">
                        <span style="color: var(--text-muted); font-size: 11px; display: block; margin-bottom: 2px;">INFORMASI PELANGGAN</span>
                        <div style="font-weight: 600; font-size: 15px; margin-bottom: 4px;" id="mCustomerName"></div>
                        <div style="color: var(--text);">📞 <span id="mPhone"></span></div>
                        <div style="color: var(--text);">✉️ <span id="mEmail"></span></div>
                    </div>

                    <div style="background: #FAFAFA; padding: 12px; border-radius: 10px; border: 1px solid #EEEEEE;">
                        <span style="color: var(--text-muted); font-size: 11px; display: block; margin-bottom: 2px;">JADWAL & AREA</span>
                        <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">📅 <span id="mDate"></span> • <span id="mTime"></span></div>
                        <div style="color: var(--text);">👥 <span id="mGuests"></span></div>
                        <div style="color: var(--text);">📍 Area: <span id="mArea" style="font-weight: 600;"></span></div>
                    </div>
                </div>

                <!-- Payment Method Box in Modal -->
                <div style="background: #FDFBF8; border: 1px solid #EAE3DA; padding: 10px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="color: var(--text-muted); font-size: 11px; display: block;">METODE PEMBAYARAN</span>
                        <strong id="mPaymentMethod" style="color: var(--coffee);"></strong>
                    </div>
                    <span id="mPaymentStatus" class="badge" style="background: #EAE3DA; color: #444;"></span>
                </div>

                <!-- Cancellation Reason (Shown only if cancelled) -->
                <div id="mCancellationBox" style="display: none; background: #FEF2F2; border-left: 3px solid #DC2626; padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; color: #991B1B;">
                    <strong>Dibatalkan:</strong> <span id="mCancellationReason"></span>
                    <div style="font-size: 11px; color: #B91C1C; margin-top: 2px;" id="mCancelledAt"></div>
                </div>

                <!-- Notes -->
                <div style="background: #FFFDF9; border-left: 3px solid var(--primary); padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 20px;">
                    <strong style="color: var(--primary-dark);">Catatan Khusus:</strong>
                    <div id="mNotes" style="font-style: italic; color: #444444; margin-top: 2px;"></div>
                </div>

                <!-- Ordered Menus Breakdown -->
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--dark); font-weight: 600;">🍽️ Rincian Menu Pre-Order</h4>
                    <div id="mMenuItemsContainer"></div>
                </div>

                <!-- Change Status Form inside Modal -->
                <div style="border-top: 1px dashed #DDD; padding-top: 15px;">
                    <form id="mStatusForm" action="" method="POST" style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                        @csrf
                        @method('PATCH')
                        <span style="font-weight: 600; font-size: 13px;">Ubah Status Reservasi:</span>
                        <div style="display: flex; gap: 8px;">
                            <select name="status" id="mStatusSelect" class="admin-select" style="padding: 6px 12px; font-size: 13px; width: auto; font-weight: 600;">
                                <option value="pending">⏳ Pending</option>
                                <option value="confirmed">✅ Confirmed</option>
                                <option value="completed">🎉 Completed</option>
                                <option value="cancelled">❌ Cancelled</option>
                            </select>
                            <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-modal-footer">
                <a id="mWaLink" href="" target="_blank" rel="noopener noreferrer" class="btn-admin btn-admin-sm" style="background: #25D366; color: #FFFFFF;">
                    💬 Chat WhatsApp
                </a>
                <a id="mPdfLink" href="" target="_blank" class="btn-admin btn-admin-outline btn-admin-sm">
                    📄 Unduh E-Ticket PDF
                </a>
                <button type="button" class="btn-admin btn-admin-outline btn-admin-sm" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select All Checkbox Handler
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkToolbar = document.getElementById('bulkToolbar');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkToolbar() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            selectedCount.innerText = checked.length;
            if (checked.length > 0) {
                bulkToolbar.style.display = 'flex';
            } else {
                bulkToolbar.style.display = 'none';
            }
            if (selectAll) {
                selectAll.checked = checked.length === rowCheckboxes.length && rowCheckboxes.length > 0;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkToolbar();
            });
        }

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkToolbar);
        });

        // Detail Modal Handler
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.getAttribute('data-detail'));
                openDetailModal(data);
            });
        });
    });

    function confirmBulkAction() {
        const select = document.getElementById('bulkActionSelect');
        if (!select.value) {
            alert('Silakan pilih aksi yang ingin diterapkan terlebih dahulu.');
            return false;
        }
        if (select.value === 'delete') {
            return confirm('Apakah Anda yakin ingin menghapus seluruh data reservasi yang dipilih?');
        }
        return confirm('Apakah Anda yakin ingin mengubah status data yang dipilih?');
    }

    function deleteSingleItem(id, code) {
        if (confirm(`Apakah Anda yakin ingin menghapus data reservasi ${code}?`)) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/admin/reservations/${id}`;
            form.submit();
        }
    }

    // Modal functions
    const detailModal = document.getElementById('detailModal');

    function openDetailModal(data) {
        document.getElementById('mBookingCode').innerText = data.code;
        document.getElementById('mCreatedAt').innerText = data.created_at;
        document.getElementById('mCustomerName').innerText = data.name;
        document.getElementById('mPhone').innerText = data.phone;
        document.getElementById('mEmail').innerText = data.email;
        document.getElementById('mDate').innerText = data.date;
        document.getElementById('mTime').innerText = data.time;
        document.getElementById('mGuests').innerText = data.guests;
        document.getElementById('mArea').innerText = data.area;
        document.getElementById('mNotes').innerText = data.notes;
        document.getElementById('mPaymentMethod').innerText = data.payment_method;
        document.getElementById('mPaymentStatus').innerText = data.payment_status;

        const cancelBox = document.getElementById('mCancellationBox');
        if (data.status === 'cancelled' && data.cancellation_reason) {
            cancelBox.style.display = 'block';
            document.getElementById('mCancellationReason').innerText = data.cancellation_reason;
            document.getElementById('mCancelledAt').innerText = data.cancelled_at ? `Pada: ${data.cancelled_at}` : '';
        } else {
            cancelBox.style.display = 'none';
        }

        const badge = document.getElementById('mStatusBadge');
        badge.className = `badge badge-${data.status}`;
        badge.innerText = data.status.toUpperCase();

        document.getElementById('mStatusSelect').value = data.status;
        document.getElementById('mStatusForm').action = data.status_url;
        document.getElementById('mWaLink').href = data.wa_url;
        document.getElementById('mPdfLink').href = data.pdf_url;

        // Render Menu Items
        const container = document.getElementById('mMenuItemsContainer');
        if (data.items && data.items.length > 0) {
            let html = '<table class="admin-table" style="font-size: 12px; margin-top: 0;">';
            html += '<thead><tr><th>Menu</th><th style="text-align:right;">Harga</th><th style="text-align:center;">Qty</th><th style="text-align:right;">Subtotal</th></tr></thead><tbody>';
            data.items.forEach(it => {
                html += `<tr><td><strong>${it.name}</strong></td><td style="text-align:right;">${it.price}</td><td style="text-align:center;">${it.qty}</td><td style="text-align:right; font-weight:600;">${it.subtotal}</td></tr>`;
            });
            html += `<tr style="background:#FDF8F3; font-weight:700;"><td colspan="3" style="text-align:right; color:var(--primary-dark);">TOTAL PESANAN:</td><td style="text-align:right; color:var(--primary-dark);">${data.total}</td></tr>`;
            html += '</tbody></table>';
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p style="color:var(--text-muted); font-size:12px; font-style:italic; background:#FAFAFA; padding:10px; border-radius:8px;">Pelanggan tidak memilih menu pre-order.</p>';
        }

        detailModal.classList.add('active');
    }

    function closeDetailModal() {
        detailModal.classList.remove('active');
    }

    // Close on backdrop click
    detailModal.addEventListener('click', (e) => {
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });
</script>
@endpush
