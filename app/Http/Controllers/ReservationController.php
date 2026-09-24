<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Category;
use App\Models\Menu;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    /**
     * Download reservation receipt as PDF.
     */
    public function downloadPdf($code)
    {
        $reservation = Reservation::with('items')
            ->where('booking_code', strtoupper(trim($code)))
            ->firstOrFail();

        $cafeInfo = [
            'name' => 'Brew & Bloom',
            'phone' => '0812-3456-7890',
            'address' => 'Jl. Melati No.123, Cirebon',
        ];

        $pdf = Pdf::loadView('reservation.pdf', compact('reservation', 'cafeInfo'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('E-Ticket-' . $reservation->booking_code . '.pdf');
    }

    /**
     * Display the dedicated reservation page.
     */
    public function index(Request $request)
    {
        $checkedReservation = null;
        if ($request->filled('code')) {
            $checkedReservation = Reservation::with('items')
                ->where('booking_code', strtoupper(trim($request->code)))
                ->first();
        }

        $categories = Category::with(['menus' => function ($query) {
            $query->where('is_available', true);
        }])->get();

        $cafeInfo = [
            'name' => 'Brew & Bloom',
            'phone' => '0812-3456-7890',
            'phone_raw' => '6281234567890',
            'address' => 'Jl. Melati No.123, Cirebon',
            'hours_weekday' => 'Senin – Jumat : 08.00 – 22.00',
            'hours_weekend' => 'Sabtu – Minggu : 07.00 – 23.00',
        ];

        return view('reservation.index', compact('cafeInfo', 'categories', 'checkedReservation'));
    }

    /**
     * Store a new reservation with optional menu pre-orders and payment method.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|string',
            'guests' => 'required|integer|min:1|max:50',
            'seating_area' => 'required|string',
            'payment_method' => 'required|string|in:pay_at_cafe,qris_online,bank_transfer_bca,bank_transfer_mandiri',
            'notes' => 'nullable|string|max:500',
            'menu_qty' => 'nullable|array',
            'menu_qty.*' => 'nullable|integer|min:0',
        ]);

        $bookingCode = 'BNB-' . strtoupper(Str::random(6));

        $reservation = DB::transaction(function () use ($validated, $bookingCode, $request) {
            $totalAmount = 0;
            $itemsToCreate = [];

            if ($request->has('menu_qty') && is_array($request->menu_qty)) {
                $menuIds = array_keys(array_filter($request->menu_qty, fn($qty) => (int)$qty > 0));

                if (!empty($menuIds)) {
                    $menus = Menu::whereIn('id', $menuIds)->where('is_available', true)->get()->keyBy('id');

                    foreach ($request->menu_qty as $menuId => $qty) {
                        $quantity = (int)$qty;
                        if ($quantity > 0 && isset($menus[$menuId])) {
                            $menu = $menus[$menuId];
                            $subtotal = $menu->price * $quantity;
                            $totalAmount += $subtotal;

                            $itemsToCreate[] = [
                                'menu_id' => $menu->id,
                                'menu_name' => $menu->name,
                                'price' => $menu->price,
                                'quantity' => $quantity,
                                'subtotal' => $subtotal,
                            ];
                        }
                    }
                }
            }

            $paymentStatus = match ($validated['payment_method']) {
                'pay_at_cafe' => 'pay_on_arrival',
                default => 'unpaid',
            };

            $res = Reservation::create([
                'booking_code' => $bookingCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'guests' => $validated['guests'],
                'seating_area' => $validated['seating_area'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($itemsToCreate as $item) {
                $res->items()->create($item);
            }

            return $res;
        });

        $reservation->load('items');

        // Build WhatsApp notification text
        $menuListText = "";
        if ($reservation->items->isNotEmpty()) {
            $menuListText = "\n🍽️ *Menu Pre-Order:*\n";
            foreach ($reservation->items as $item) {
                $menuListText .= "• {$item->quantity}x {$item->menu_name} (" . $item->formatted_subtotal . ")\n";
            }
            $menuListText .= "*Estimasi Total Menu:* " . $reservation->formatted_total . "\n";
        }

        $waMessage = "Halo Brew & Bloom! Saya ingin konfirmasi booking meja:\n\n"
            . "• *Kode Booking:* " . $reservation->booking_code . "\n"
            . "• *Nama:* " . $reservation->customer_name . "\n"
            . "• *Tanggal:* " . $reservation->reservation_date->format('d M Y') . "\n"
            . "• *Jam:* " . $reservation->reservation_time . " WIB\n"
            . "• *Jumlah Tamu:* " . $reservation->guests . " Orang\n"
            . "• *Area Meja:* " . $reservation->seating_area . "\n"
            . "• *Metode Bayar:* " . $reservation->payment_method_label . "\n"
            . ($reservation->notes ? "• *Catatan:* " . $reservation->notes . "\n" : "")
            . $menuListText
            . "\nMohon konfirmasi ketersediaan meja & pesanan kami. Terima kasih!";

        $waLink = "https://wa.me/6281234567890?text=" . urlencode($waMessage);

        return redirect()->route('reservation.index')
            ->with('success_booking', $reservation)
            ->with('wa_link', $waLink);
    }

    /**
     * Customer cancel their own reservation using booking code.
     */
    public function cancel(Request $request, $code)
    {
        $reservation = Reservation::where('booking_code', strtoupper(trim($code)))->firstOrFail();

        if (in_array($reservation->status, ['cancelled', 'completed'])) {
            return back()->with('cancel_error', 'Reservasi ini tidak dapat dibatalkan lagi karena statusnya sudah ' . $reservation->status . '.');
        }

        $reason = $request->input('reason', 'Dibatalkan oleh pelanggan');

        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);

        return redirect()->route('reservation.index', ['code' => $reservation->booking_code])
            ->with('cancel_success', "Reservasi dengan kode {$reservation->booking_code} telah berhasil dibatalkan.");
    }
}
