<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of orders placed by the authenticated customer.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $orders = Auth::user()
            ->orders()
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }

    /**
     * Cancel a pending order placed by the authenticated customer and record an activity log entry.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Order $order): RedirectResponse
    {
        // Only the owner can cancel their own order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only Pending orders can be cancelled
        if ($order->status !== 'Pending') {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        $order->update(['status' => 'Cancelled']);

        \App\Models\ActivityLog::record(
            'cancelled',
            'Orders',
            "Customer cancelled Order #{$order->id} ({$order->pizza_name})."
        );

        return back()->with('success', 'Your order has been cancelled.');
    }
}