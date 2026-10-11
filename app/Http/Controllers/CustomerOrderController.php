<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    // Display the customer's orders
    public function index(): View
    {
        $orders = Auth::user()
            ->orders()
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }

     // Cancel a customer's order
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

         // Update the order status
        $order->update(['status' => 'Cancelled']);

          // Record the cancellation in the activity log
        \App\Models\ActivityLog::record(
            'cancelled',
            'Orders',
            "Customer cancelled Order #{$order->id} ({$order->pizza_name})."
        );

        return back()->with('success', 'Your order has been cancelled.');
    }
}