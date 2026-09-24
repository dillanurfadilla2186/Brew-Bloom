<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Reservasi - {{ $reservation->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2D2D2D;
            background: #FFFFFF;
            padding: 30px 35px;
            font-size: 13px;
            line-height: 1.5;
        }
        .ticket-box {
            border: 2px solid #6F4E37;
            border-radius: 12px;
            padding: 25px;
            position: relative;
        }
        .header {
            border-bottom: 2px dashed #C98A52;
            padding-bottom: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 26px;
            color: #6F4E37;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .header .tagline {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #C98A52;
            font-weight: bold;
        }
        .header .cafe-info {
            font-size: 11px;
            color: #666666;
            margin-top: 6px;
        }

        .booking-banner {
            background: #F8F3ED;
            border: 1px solid #E5D5C5;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .banner-left {
            display: table-cell;
            vertical-align: middle;
        }
        .banner-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        .code-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #777777;
            letter-spacing: 1px;
        }
        .booking-code {
            font-size: 22px;
            font-weight: bold;
            color: #6F4E37;
            letter-spacing: 2px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-confirmed { background: #D1FAE5; color: #065F46; }

        .details-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .details-grid td {
            padding: 7px 0;
            vertical-align: top;
        }
        .label {
            color: #666666;
            font-size: 12px;
            width: 140px;
        }
        .value {
            color: #111111;
            font-weight: 600;
            font-size: 13px;
        }

        /* Menu Table */
        .section-heading {
            font-size: 14px;
            font-weight: bold;
            color: #6F4E37;
            margin: 18px 0 10px;
            border-bottom: 1px solid #EAE3DA;
            padding-bottom: 4px;
        }
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .menu-table th {
            background: #6F4E37;
            color: #FFFFFF;
            font-size: 11px;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        .menu-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #EEEEEE;
            font-size: 12px;
        }
        .menu-table tr:nth-child(even) td {
            background: #FAFAFA;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            background: #F8F3ED !important;
            color: #6F4E37;
            font-size: 13px;
            border-top: 1px solid #C98A52;
        }

        .notes-box {
            background: #FFFDF9;
            border-left: 3px solid #C98A52;
            padding: 8px 12px;
            font-size: 12px;
            color: #555555;
            margin-bottom: 20px;
            font-style: italic;
        }

        .footer-rules {
            border-top: 1px dashed #CCCCCC;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 10px;
            color: #777777;
        }
        .footer-rules ul {
            margin-left: 16px;
            margin-top: 4px;
        }
        .footer-rules li {
            margin-bottom: 3px;
        }
        .printed-at {
            text-align: right;
            font-size: 9px;
            color: #999999;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="ticket-box">
        <!-- Header -->
        <div class="header">
            <h1>Brew & Bloom</h1>
            <div class="tagline">Coffee • Food • Good Vibes</div>
            <div class="cafe-info">
                {{ $cafeInfo['address'] ?? 'Jl. Melati No.123, Cirebon' }} • WhatsApp: {{ $cafeInfo['phone'] ?? '0812-3456-7890' }}
            </div>
        </div>

        <!-- Booking Banner -->
        <div class="booking-banner">
            <div class="banner-left">
                <div class="code-title">Kode Booking Reservasi</div>
                <div class="booking-code">{{ $reservation->booking_code }}</div>
            </div>
            <div class="banner-right">
                <span class="badge badge-{{ $reservation->status }}">
                    Status: {{ strtoupper($reservation->status) }}
                </span>
            </div>
        </div>

        <!-- Reservation Info -->
        <table class="details-grid">
            <tr>
                <td class="label">Nama Pemesan:</td>
                <td class="value">{{ $reservation->customer_name }}</td>
                <td class="label">Tanggal Kunjungan:</td>
                <td class="value">{{ $reservation->reservation_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nomor WhatsApp:</td>
                <td class="value">{{ $reservation->customer_phone }}</td>
                <td class="label">Waktu / Jam:</td>
                <td class="value">{{ $reservation->reservation_time }} WIB</td>
            </tr>
            <tr>
                <td class="label">Alamat Email:</td>
                <td class="value">{{ $reservation->customer_email ?: '-' }}</td>
                <td class="label">Jumlah Tamu:</td>
                <td class="value">{{ $reservation->guests }} Orang</td>
            </tr>
            <tr>
                <td class="label">Area Meja:</td>
                <td class="value">{{ $reservation->seating_area }}</td>
                <td class="label">Metode Pembayaran:</td>
                <td class="value">{{ $reservation->payment_method_label }} ({{ $reservation->payment_status_label }})</td>
            </tr>
        </table>

        @if($reservation->notes)
            <div class="notes-box">
                <strong>Catatan Khusus:</strong> "{{ $reservation->notes }}"
            </div>
        @endif

        <!-- Menu Pre-Order Table -->
        @if($reservation->items->isNotEmpty())
            <div class="section-heading">Rincian Menu Pre-Order</div>
            <table class="menu-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">No</th>
                        <th>Menu</th>
                        <th style="width: 90px;" class="text-right">Harga</th>
                        <th style="width: 50px;" class="text-right">Qty</th>
                        <th style="width: 100px;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservation->items as $idx => $item)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td><strong>{{ $item->menu_name }}</strong></td>
                            <td class="text-right">{{ $item->formatted_price }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ $item->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" class="text-right">ESTIMASI TOTAL PEMESANAN:</td>
                        <td class="text-right">{{ $reservation->formatted_total }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Footer / Rules -->
        <div class="footer-rules">
            <strong>Ketentuan Kunjungan & Reservasi:</strong>
            <ul>
                <li>Tunjukkan bukti e-tiket reservasi ini kepada staf atau kasir Brew & Bloom saat Anda tiba.</li>
                <li>Meja akan ditahan selama 15 menit dari jam kedatangan yang tertera.</li>
                <li>Pesanan menu pre-order akan diproses dan disiapkan ketika Anda melakukan check-in di kasir.</li>
                <li>Untuk perubahan jadwal atau pembatalan, silakan hubungi kami via WhatsApp: {{ $cafeInfo['phone'] ?? '0812-3456-7890' }}.</li>
            </ul>
            <div class="printed-at">
                Dicetak pada: {{ date('d-m-Y H:i:s') }} WIB • E-Ticket Resmi Brew & Bloom Cafe
            </div>
        </div>
    </div>

</body>
</html>
