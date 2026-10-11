<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    // BITOIN - Display orders and reports on the admin page.
    public function index(Request $request): View
    {
        // Check if the selected dates are valid.
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $fromDate = $request->query('from');
        $toDate = $request->query('to');

        // Get orders based on the selected date range.
        $ordersQuery = Order::latest();
        if ($fromDate) {
            $ordersQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $ordersQuery->whereDate('created_at', '<=', $toDate);
        }
        $orders = $ordersQuery->get();

        // Get the data needed for the reports.
        $reports = $this->getReportData($fromDate, $toDate);

        return view('admin.orders', compact('orders', 'reports', 'fromDate', 'toDate'));
    }

    // Update an order's status and record the activity
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => [
                'required',
                'in:Pending,Preparing,Cooking,Delivery,Completed',
            ],
        ]);
    // Find the order using its ID.
        $order = Order::findOrFail($id);

    // Do not allow changes if the order is cancelled.
        if ($order->status === 'Cancelled') {
            return redirect()
                ->route('admin.orders')
                ->with('success', 'This order was cancelled and can no longer be changed.');
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;
    // Save the new order status. 
        $order->status = $newStatus;
        $order->save();

    // Record the status change in the activity log.
        ActivityLog::record(
            'updated',
            'Orders',
            "Admin updated Order #{$order->id} status from {$oldStatus} to {$newStatus}."
        );

        return redirect()
            ->route('admin.orders')
            ->with('success', 'Order status updated successfully!');
    }

    // Delete an order after recording the activity.
    public function destroy($id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        // Record the deletion before deleting the order.
        ActivityLog::record(
            'deleted',
            'Orders',
            "Admin deleted Order #{$order->id} for {$order->customer_name} ({$order->pizza_name})."
        );

        $order->delete();

        return redirect()
            ->route('admin.orders')
            ->with('success', "Order #{$id} has been deleted successfully.");
    }

    // Get the data needed for the admin reports.
    private function getReportData(?string $fromDate, ?string $toDate): array
    {
        $baseQuery = Order::query();

        if ($fromDate) {
            $baseQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $baseQuery->whereDate('created_at', '<=', $toDate);
        }

        // Count the orders.
        $totalOrders = (clone $baseQuery)->count();

        // Calculate sales without cancelled orders. 
        $totalSales = (clone $baseQuery)
            ->where('status', '!=', 'Cancelled')
            ->sum('pizza_price');

        // Count the orders for each status.
        $statusCounts = (clone $baseQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get the number of orders and sales for each day. 
        $ordersPerDayQuery = (clone $baseQuery)
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('count(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status != "Cancelled" THEN pizza_price ELSE 0 END) as total_sales')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc(DB::raw('DATE(created_at)'));

        // Show only the latest 14 days if no dates are selected.
        if (!$fromDate && !$toDate) {
            $ordersPerDayQuery->limit(14);
        }

        $ordersPerDay = $ordersPerDayQuery->get();

        // Get the top 5 pizzas based on order count.
        $topItems = (clone $baseQuery)
            ->select(
                'pizza_name',
                DB::raw('count(*) as order_count'),
                DB::raw('SUM(CASE WHEN status != "Cancelled" THEN pizza_price ELSE 0 END) as total_sales')
            )
            ->groupBy('pizza_name')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();

        // Return all the report date.
        return [
            'total_orders'  => $totalOrders,
            'total_sales'   => (float) $totalSales,
            'status_counts' => $statusCounts,
            'orders_per_day'=> $ordersPerDay,
            'top_items'     => $topItems,
        ];
    }
}
