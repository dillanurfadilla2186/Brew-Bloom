<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationManagementController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('items');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $reservations = $query->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'all' => Reservation::count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'stats'));
    }

    /**
     * Update the status of the specified reservation.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Status reservasi {$reservation->booking_code} berhasil diubah menjadi " . ucfirst($validated['status']) . ".");
    }

    /**
     * Handle bulk actions for reservations.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id',
            'action' => 'required|in:delete,pending,confirmed,completed,cancelled',
        ]);

        $count = count($validated['ids']);

        if ($validated['action'] === 'delete') {
            Reservation::whereIn('id', $validated['ids'])->delete();
            return back()->with('success', "{$count} data reservasi berhasil dihapus.");
        }

        Reservation::whereIn('id', $validated['ids'])->update([
            'status' => $validated['action'],
        ]);

        return back()->with('success', "Status {$count} data reservasi berhasil diubah menjadi " . ucfirst($validated['action']) . ".");
    }

    /**
     * Remove the specified reservation from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Data reservasi berhasil dihapus.');
    }
}
