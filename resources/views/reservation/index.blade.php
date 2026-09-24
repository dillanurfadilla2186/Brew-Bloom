@extends('layouts.app')

@section('title', 'Reservasi Meja & Pre-Order Menu | Brew & Bloom')

@push('styles')
<style>
    .res-hero {
        padding: 140px 0 60px;
        background: linear-gradient(135deg, #231C18 0%, #3D291D 100%);
        color: #ffffff;
        text-align: center;
    }
    .res-hero h1 {
        color: #ffffff;
        font-size: clamp(34px, 5vw, 52px);
        margin-bottom: 12px;
    }
    .res-hero p {
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: auto;
    }
    .res-container {
        padding: 60px 0 100px;
    }
    .res-grid {
        display: grid;
        grid-template-columns: 1.3fr 0.7fr;
        gap: 45px;
        align-items: flex-start;
    }
    .res-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 24px;
        box-shadow: var(--card-shadow);
    }
    .form-group {
        margin-bottom: 22px;
    }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
    }
    .form-control, .form-select {
        width: 100%;
        padding: 12px 18px;
        border: 1px solid #E0D9D0;
        border-radius: 12px;
        font-family: inherit;
        font-size: 15px;
        transition: var(--transition);
        background: #FAFAFA;
        outline: none;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--caramel);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(201, 138, 82, 0.2);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Menu Pre-Order Box */
    .menu-picker-section {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px dashed #EAE3DA;
    }
    .menu-picker-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .menu-picker-title h3 {
        font-size: 20px;
        color: var(--coffee);
    }
    .menu-items-scroll {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 8px;
        margin-bottom: 20px;
    }
    .menu-items-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .menu-items-scroll::-webkit-scrollbar-thumb {
        background: #D9CEC4;
        border-radius: 10px;
    }
    .menu-select-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 15px;
        border: 1px solid #EAE3DA;
        border-radius: 14px;
        margin-bottom: 10px;
        background: #FDFBF8;
        transition: var(--transition);
        gap: 15px;
    }
    .menu-select-card:hover {
        border-color: var(--caramel);
        background: #FFFFFF;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }
    .menu-select-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex-grow: 1;
    }
    .menu-select-img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .menu-select-text h4 {
        font-size: 14px;
        margin-bottom: 2px;
        color: var(--dark);
    }
    .menu-select-text .price-tag {
        font-size: 13px;
        font-weight: 600;
        color: var(--coffee);
    }
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #D1C7BD;
        background: #FFFFFF;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .qty-btn:hover {
        background: var(--caramel);
        color: #FFFFFF;
        border-color: var(--caramel);
    }
    .qty-input {
        width: 36px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 14px;
        outline: none;
    }
    .total-summary-card {
        background: #F7F1EB;
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .total-summary-card span {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
    }
    .total-summary-card .total-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--coffee);
    }

    /* Payment Methods Grid */
    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 10px;
    }
    .payment-option {
        border: 1.5px solid #E0D9D0;
        border-radius: 14px;
        padding: 14px;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #FAFAFA;
    }
    .payment-option:hover {
        border-color: var(--caramel);
        background: #FFFFFF;
    }
    .payment-option.active {
        border-color: var(--caramel);
        background: #FDF9F5;
        box-shadow: 0 0 0 2px rgba(201, 138, 82, 0.2);
    }
    .payment-option input[type="radio"] {
        margin-top: 3px;
        accent-color: var(--caramel);
    }
    .payment-text strong {
        display: block;
        font-size: 13px;
        color: var(--dark);
        margin-bottom: 2px;
    }
    .payment-text small {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        line-height: 1.4;
    }
    .payment-instructions-box {
        background: #F7F3EE;
        border: 1px solid #E2D7CC;
        border-radius: 12px;
        padding: 15px 18px;
        margin-top: 15px;
        font-size: 13px;
        color: var(--text);
    }

    /* Success Box */
    .success-box {
        background: #EAF7ED;
        border: 1px solid #B8E7C0;
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 35px;
        text-align: center;
    }
    .booking-badge {
        display: inline-block;
        background: var(--coffee);
        color: #ffffff;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 2px;
        margin: 10px 0 15px;
    }
    .side-info-card {
        background: #ffffff;
        padding: 35px;
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
    }
    .side-info-card h3 {
        font-size: 20px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-list {
        list-style: none;
    }
    .info-list li {
        padding: 12px 0;
        border-bottom: 1px solid #F0ECE6;
        font-size: 14px;
        display: flex;
        gap: 12px;
        color: var(--text);
    }
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-pending { background: #FEF3C7; color: #92400E; }
    .badge-confirmed { background: #D1FAE5; color: #065F46; }
    .badge-cancelled { background: #FEE2E2; color: #991B1B; }
    .badge-completed { background: #E0E7FF; color: #3730A3; }

    @media (max-width: 992px) {
        .res-grid {
            grid-template-columns: 1fr;
        }
        .payment-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

    <section class="res-hero">
        <div class="container">
            <span class="small-title" style="color: var(--caramel-light);">Reservasi & Pre-Order</span>
            <h1>Booking Meja & Pilih Menu</h1>
            <p>Pilih meja favorit Anda dan pesan menu terlebih dahulu agar siap disajikan saat Anda tiba di Brew & Bloom.</p>
        </div>
    </section>

    <section class="res-container">
        <div class="container">

            @if(session('cancel_success'))
                <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 20px; border-radius: 16px; margin-bottom: 30px; text-align: center;">
                    <span style="font-size: 32px;">❌</span>
                    <h3 style="margin-top: 8px;">Reservasi Dibatalkan</h3>
                    <p style="font-size: 14px; margin-top: 4px;">{{ session('cancel_success') }}</p>
                </div>
            @endif

            @if(session('cancel_error'))
                <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
                    {{ session('cancel_error') }}
                </div>
            @endif

            @if(session('success_booking'))
                @php $res = session('success_booking'); @endphp
                <div class="success-box reveal">
                    <span style="font-size: 40px;">🎉</span>
                    <h2 style="color: #2E7D32; margin-top: 10px;">Reservasi Berhasil Dikirim!</h2>
                    <p style="color: #4B6F50;">Simpan Kode Booking Anda untuk pengecekan status.</p>
                    <div class="booking-badge">{{ $res->booking_code }}</div>
                    <p>Atas nama <strong>{{ $res->customer_name }}</strong> • {{ $res->reservation_date->format('d M Y') }} jam <strong>{{ $res->reservation_time }} WIB</strong> ({{ $res->guests }} Tamu - {{ $res->seating_area }})</p>
                    <p style="font-size: 13px; color: #555; margin-top: 4px;">Metode Pembayaran: <strong>{{ $res->payment_method_label }}</strong></p>

                    @if($res->items->isNotEmpty())
                        <div style="margin: 20px auto; max-width: 500px; background: #FFFFFF; border-radius: 12px; padding: 15px 20px; text-align: left; border: 1px solid #D4EED8;">
                            <strong style="color: var(--coffee); font-size: 14px;">🍽️ Menu yang Dipilih:</strong>
                            <ul style="margin: 10px 0 10px 20px; font-size: 13px;">
                                @foreach($res->items as $item)
                                    <li>{{ $item->quantity }}x <strong>{{ $item->menu_name }}</strong> ({{ $item->formatted_subtotal }})</li>
                                @endforeach
                            </ul>
                            <div style="text-align: right; font-weight: 700; color: var(--coffee);">
                                Total: {{ $res->formatted_total }}
                            </div>
                        </div>
                    @endif

                    <div style="margin-top: 25px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('reservation.pdf', $res->booking_code) }}" target="_blank" class="btn" style="background: #1E1B18; color: #FFFFFF;">
                            📄 Unduh E-Ticket (PDF)
                        </a>
                        <a href="{{ session('wa_link') }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            📲 Konfirmasi WhatsApp
                        </a>
                        <a href="{{ route('reservation.index') }}" class="btn btn-outline-dark">
                            Booking Baru
                        </a>
                    </div>
                </div>
            @endif

            <div class="res-grid">
                <!-- Reservation Form -->
                <div class="res-card reveal">
                    <span class="small-title">Formulir Booking</span>
                    <h2 class="section-title" style="font-size: 28px; margin-bottom: 25px;">Isi Detail Kunjungan Anda</h2>

                    @if($errors->any())
                        <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                            <ul style="margin-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reservation.store') }}" method="POST" id="reservationForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="customer_name">Nama Lengkap *</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Contoh: Budi Santoso" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="customer_phone">Nomor WhatsApp *</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="0812xxxxxxxx" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="customer_email">Alamat Email (Opsional)</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email') }}" placeholder="budi@example.com">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="reservation_date">Tanggal Kunjungan *</label>
                                <input type="date" name="reservation_date" id="reservation_date" class="form-control" value="{{ old('reservation_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="reservation_time">Jam Kedatangan *</label>
                                <select name="reservation_time" id="reservation_time" class="form-select" required>
                                    <option value="">Pilih Jam...</option>
                                    <option value="09:00" {{ old('reservation_time') == '09:00' ? 'selected' : '' }}>09:00 WIB</option>
                                    <option value="10:00" {{ old('reservation_time') == '10:00' ? 'selected' : '' }}>10:00 WIB</option>
                                    <option value="11:30" {{ old('reservation_time') == '11:30' ? 'selected' : '' }}>11:30 WIB</option>
                                    <option value="13:00" {{ old('reservation_time') == '13:00' ? 'selected' : '' }}>13:00 WIB</option>
                                    <option value="15:00" {{ old('reservation_time') == '15:00' ? 'selected' : '' }}>15:00 WIB</option>
                                    <option value="17:00" {{ old('reservation_time') == '17:00' ? 'selected' : '' }}>17:00 WIB</option>
                                    <option value="19:00" {{ old('reservation_time') == '19:00' ? 'selected' : '' }}>19:00 WIB</option>
                                    <option value="20:30" {{ old('reservation_time') == '20:30' ? 'selected' : '' }}>20:30 WIB</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="guests">Jumlah Tamu *</label>
                                <select name="guests" id="guests" class="form-select" required>
                                    <option value="1" {{ old('guests') == '1' ? 'selected' : '' }}>1 Orang</option>
                                    <option value="2" {{ old('guests', 2) == '2' ? 'selected' : '' }}>2 Orang</option>
                                    <option value="3" {{ old('guests') == '3' ? 'selected' : '' }}>3 Orang</option>
                                    <option value="4" {{ old('guests') == '4' ? 'selected' : '' }}>4 Orang</option>
                                    <option value="5" {{ old('guests') == '5' ? 'selected' : '' }}>5 Orang</option>
                                    <option value="6" {{ old('guests') == '6' ? 'selected' : '' }}>6 - 8 Orang</option>
                                    <option value="10" {{ old('guests') == '10' ? 'selected' : '' }}>Group (> 8 Orang)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="seating_area">Pilihan Area *</label>
                                <select name="seating_area" id="seating_area" class="form-select" required>
                                    <option value="Indoor AC" {{ old('seating_area') == 'Indoor AC' ? 'selected' : '' }}>Indoor AC (Non-Smoking / Cozy)</option>
                                    <option value="Outdoor Garden" {{ old('seating_area') == 'Outdoor Garden' ? 'selected' : '' }}>Outdoor Garden (Aesthetic & Relax)</option>
                                    <option value="Semi-Outdoor" {{ old('seating_area') == 'Semi-Outdoor' ? 'selected' : '' }}>Semi-Outdoor Terrace</option>
                                    <option value="Private Meeting Corner" {{ old('seating_area') == 'Private Meeting Corner' ? 'selected' : '' }}>Private Meeting Corner</option>
                                </select>
                            </div>
                        </div>

                        <!-- Menu Pre-Order Section -->
                        <div class="menu-picker-section">
                            <div class="menu-picker-title">
                                <h3>🍽️ Pilih Menu (Pre-Order Opsional)</h3>
                                <small style="color: var(--text-muted); font-size: 12px;">Pesan lebih awal agar siap saat datang</small>
                            </div>

                            <div class="menu-items-scroll">
                                @foreach($categories as $cat)
                                    @if($cat->menus->isNotEmpty())
                                        <div style="font-weight: 600; font-size: 13px; color: var(--caramel); margin: 12px 0 6px; text-transform: uppercase;">
                                            {{ $cat->icon }} {{ $cat->name }}
                                        </div>
                                        @foreach($cat->menus as $menu)
                                            <div class="menu-select-card" data-price="{{ $menu->price }}">
                                                <div class="menu-select-info">
                                                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="menu-select-img" loading="lazy">
                                                    <div class="menu-select-text">
                                                        <h4>{{ $menu->name }}</h4>
                                                        <div class="price-tag">{{ $menu->formatted_price }}</div>
                                                    </div>
                                                </div>
                                                <div class="qty-controls">
                                                    <button type="button" class="qty-btn btn-minus" data-id="{{ $menu->id }}">−</button>
                                                    <input type="number" 
                                                           name="menu_qty[{{ $menu->id }}]" 
                                                           id="qty_{{ $menu->id }}" 
                                                           value="0" 
                                                           min="0" 
                                                           max="20" 
                                                           class="qty-input item-qty" 
                                                           data-price="{{ $menu->price }}"
                                                           readonly>
                                                    <button type="button" class="qty-btn btn-plus" data-id="{{ $menu->id }}">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>

                            <!-- Live Total Box -->
                            <div class="total-summary-card">
                                <span>Estimasi Total Menu:</span>
                                <span class="total-price" id="liveTotalDisplay">Rp0</span>
                            </div>
                        </div>

                        <!-- Payment Method Section -->
                        <div class="form-group" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #EAE3DA;">
                            <label class="form-label" style="font-size: 16px; color: var(--coffee); margin-bottom: 4px;">💳 Metode Pembayaran *</label>
                            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Pilih cara pembayaran yang paling nyaman untuk Anda:</p>

                            <div class="payment-grid">
                                <label class="payment-option active" id="opt_pay_at_cafe">
                                    <input type="radio" name="payment_method" value="pay_at_cafe" checked onchange="updatePaymentMethod('pay_at_cafe')">
                                    <div class="payment-text">
                                        <strong>☕ Bayar di Cafe</strong>
                                        <small>Bayar saat tiba di kasir (Cash, Debit, atau QRIS).</small>
                                    </div>
                                </label>

                                <label class="payment-option" id="opt_qris_online">
                                    <input type="radio" name="payment_method" value="qris_online" onchange="updatePaymentMethod('qris_online')">
                                    <div class="payment-text">
                                        <strong>📱 QRIS / E-Wallet</strong>
                                        <small>GoPay, OVO, Dana, ShopeePay, LinkAja.</small>
                                    </div>
                                </label>

                                <label class="payment-option" id="opt_bank_transfer_bca">
                                    <input type="radio" name="payment_method" value="bank_transfer_bca" onchange="updatePaymentMethod('bank_transfer_bca')">
                                    <div class="payment-text">
                                        <strong>🏦 Transfer Bank BCA</strong>
                                        <small>No. Rek: <strong>123-456-7890</strong> a.n Brew & Bloom.</small>
                                    </div>
                                </label>

                                <label class="payment-option" id="opt_bank_transfer_mandiri">
                                    <input type="radio" name="payment_method" value="bank_transfer_mandiri" onchange="updatePaymentMethod('bank_transfer_mandiri')">
                                    <div class="payment-text">
                                        <strong>🏦 Transfer Mandiri</strong>
                                        <small>No. Rek: <strong>987-654-3210</strong> a.n Brew & Bloom.</small>
                                    </div>
                                </label>
                            </div>

                            <div id="paymentInstructions" class="payment-instructions-box" style="display: none;">
                                <div id="payInstructionContent"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Catatan Khusus (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Contoh: Kursi bayi, dekorasi ulang tahun, request latte art...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">
                            Konfirmasi & Kirim Reservasi Meja
                        </button>
                    </form>
                </div>

                <!-- Side Info & Status Checker -->
                <div>
                    <!-- Check Booking Status Box -->
                    <div class="side-info-card reveal">
                        <h3>🔍 Cek Status Reservasi</h3>
                        <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 15px;">Sudah pernah booking? Masukkan kode booking Anda di bawah ini:</p>
                        
                        <form action="{{ route('reservation.index') }}" method="GET" style="display: flex; gap: 8px;">
                            <input type="text" name="code" value="{{ request('code') }}" placeholder="Contoh: BNB-AB12CD" class="form-control" style="text-transform: uppercase;" required>
                            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Cek</button>
                        </form>

                        @if(request('code'))
                            <div style="background: #FDFBF8; border: 1px solid #E8DFD5; border-radius: 18px; padding: 20px; margin-top: 18px;">
                                @if($checkedReservation)
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                        <strong>{{ $checkedReservation->booking_code }}</strong>
                                        <span class="badge-status badge-{{ $checkedReservation->status }}">
                                            {{ ucfirst($checkedReservation->status) }}
                                        </span>
                                    </div>
                                    <p style="font-size: 13px; margin-bottom: 4px;">Nama: <strong>{{ $checkedReservation->customer_name }}</strong></p>
                                    <p style="font-size: 13px; margin-bottom: 4px;">Jadwal: {{ $checkedReservation->reservation_date->format('d M Y') }} • {{ $checkedReservation->reservation_time }} WIB</p>
                                    <p style="font-size: 13px; margin-bottom: 4px;">Area: {{ $checkedReservation->seating_area }} ({{ $checkedReservation->guests }} Tamu)</p>
                                    <p style="font-size: 13px; margin-bottom: 6px;">Pembayaran: <strong>{{ $checkedReservation->payment_method_label }}</strong> ({{ $checkedReservation->payment_status_label }})</p>
                                    
                                    @if($checkedReservation->items->isNotEmpty())
                                        <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #DDD;">
                                            <strong style="font-size: 12px; color: var(--coffee);">Menu Dipesan:</strong>
                                            <ul style="margin: 4px 0 4px 18px; font-size: 12px; color: var(--text);">
                                                @foreach($checkedReservation->items as $it)
                                                    <li>{{ $it->quantity }}x {{ $it->menu_name }}</li>
                                                @endforeach
                                            </ul>
                                            <div style="font-size: 12px; font-weight: 600; color: var(--coffee); text-align: right;">
                                                Total: {{ $checkedReservation->formatted_total }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($checkedReservation->status === 'cancelled' && $checkedReservation->cancellation_reason)
                                        <div style="margin-top: 10px; padding: 8px 12px; background: #FEF2F2; border-radius: 8px; font-size: 12px; color: #991B1B;">
                                            <strong>Alasan Pembatalan:</strong> {{ $checkedReservation->cancellation_reason }}
                                        </div>
                                    @endif

                                    <div style="margin-top: 15px; padding-top: 12px; border-top: 1px solid #EAE3DA; display: flex; flex-direction: column; gap: 8px;">
                                        <a href="{{ route('reservation.pdf', $checkedReservation->booking_code) }}" target="_blank" class="btn btn-outline-dark" style="width: 100%; font-size: 12px; padding: 8px 12px; justify-content: center;">
                                            📄 Unduh Bukti Reservasi (PDF)
                                        </a>

                                        @if(in_array($checkedReservation->status, ['pending', 'confirmed']))
                                            <button type="button" 
                                                    class="btn btn-outline-dark" 
                                                    style="width: 100%; font-size: 12px; padding: 8px 12px; justify-content: center; color: #DC2626; border-color: #FCA5A5;"
                                                    onclick="openCancelDialog('{{ $checkedReservation->booking_code }}')">
                                                ❌ Batalkan Reservasi Ini
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <p style="color: #991B1B; font-size: 13px;">Kode booking <strong>{{ request('code') }}</strong> tidak ditemukan dalam sistem.</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Reservation Policies -->
                    <div class="side-info-card reveal">
                        <h3>📋 Ketentuan Reservasi</h3>
                        <ul class="info-list">
                            <li>
                                <span>⏰</span>
                                <span>Meja akan ditahan maksimal 15 menit dari jam reservasi.</span>
                            </li>
                            <li>
                                <span>💳</span>
                                <span>Pembayaran dapat dilakukan langsung di kasir atau via transfer online.</span>
                            </li>
                            <li>
                                <span>❌</span>
                                <span>Pelanggan dapat membatalkan reservasi kapan saja melalui fitur Cek Status di halaman ini.</span>
                            </li>
                            <li>
                                <span>💬</span>
                                <span>Admin kami siap membantu konfirmasi ketersediaan meja melalui WhatsApp.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const liveTotalDisplay = document.getElementById('liveTotalDisplay');
        const qtyInputs = document.querySelectorAll('.item-qty');

        function updateTotal() {
            let grandTotal = 0;
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseInt(input.getAttribute('data-price')) || 0;
                grandTotal += (qty * price);
            });
            liveTotalDisplay.innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
        }

        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const input = document.getElementById('qty_' + id);
                let val = parseInt(input.value) || 0;
                if (val < 20) {
                    input.value = val + 1;
                    updateTotal();
                }
            });
        });

        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const input = document.getElementById('qty_' + id);
                let val = parseInt(input.value) || 0;
                if (val > 0) {
                    input.value = val - 1;
                    updateTotal();
                }
            });
        });
    });

    function updatePaymentMethod(method) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
        const activeOpt = document.getElementById('opt_' + method);
        if (activeOpt) activeOpt.classList.add('active');

        const box = document.getElementById('paymentInstructions');
        const content = document.getElementById('payInstructionContent');

        if (method === 'qris_online') {
            box.style.display = 'block';
            content.innerHTML = '<strong>📱 Pembayaran QRIS / E-Wallet:</strong><br>Setelah formulir dikirim, kirim bukti transfer ke WhatsApp admin kami. QRIS dinamis juga dapat di-scan saat Anda tiba di cafe.';
        } else if (method === 'bank_transfer_bca') {
            box.style.display = 'block';
            content.innerHTML = '<strong>🏦 Transfer Bank BCA:</strong><br>No. Rekening: <strong>123-456-7890</strong> a.n <strong>Brew & Bloom Cafe</strong>.<br>Silakan simpan bukti transfer untuk dikonfirmasi ke admin via WhatsApp.';
        } else if (method === 'bank_transfer_mandiri') {
            box.style.display = 'block';
            content.innerHTML = '<strong>🏦 Transfer Bank Mandiri:</strong><br>No. Rekening: <strong>987-654-3210</strong> a.n <strong>Brew & Bloom Cafe</strong>.<br>Silakan simpan bukti transfer untuk dikonfirmasi ke admin via WhatsApp.';
        } else {
            box.style.display = 'none';
        }
    }

    // Cancel Dialog Handler
    const cancelModal = document.getElementById('cancelModal');
    function openCancelDialog(code) {
        document.getElementById('cancelCodeDisplay').innerText = code;
        document.getElementById('cancelReservationForm').action = `/reservasi/${code}/cancel`;
        cancelModal.classList.add('active');
    }

    function closeCancelDialog() {
        cancelModal.classList.remove('active');
    }

    if (cancelModal) {
        cancelModal.addEventListener('click', (e) => {
            if (e.target === cancelModal) {
                closeCancelDialog();
            }
        });
    }
</script>
@endpush
