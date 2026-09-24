<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'reservation_date',
        'reservation_time',
        'guests',
        'seating_area',
        'notes',
        'total_amount',
        'payment_method',
        'payment_status',
        'cancellation_reason',
        'cancelled_at',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'guests' => 'integer',
        'total_amount' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            'pay_at_cafe' => 'Bayar di Cafe (Kasir / Cash / QRIS)',
            'qris_online' => 'QRIS Online / E-Wallet',
            'bank_transfer_bca' => 'Transfer Bank BCA',
            'bank_transfer_mandiri' => 'Transfer Bank Mandiri',
            default => 'Bayar di Cafe',
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'paid' => 'Lunas (Paid)',
            'unpaid' => 'Belum Bayar (Unpaid)',
            'pay_on_arrival' => 'Bayar saat Tiba',
            default => 'Bayar saat Tiba',
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'confirmed' => 'badge-success',
            'pending' => 'badge-warning',
            'cancelled' => 'badge-danger',
            'completed' => 'badge-info',
            default => 'badge-secondary',
        };
    }
}
