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
    /**
     * Display the admin orders list and summary reports.
     *
     * Supports optional date range filtering ('from' and 'to') validated via $request->validate().
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $fromDate = $request->query('from');
        $toDate = $request->query('to');

        // Fetch orders for the management table (filtered if date range provided)
        $ordersQuery = Order::latest();
        if ($fromDate) {
            $ordersQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $ordersQuery->whereDate('created_at', '<=', $toDate);
        }
        $orders = $ordersQuery->get();

        // Compute report data using Eloquent & query builder with bindings
        $reports = $this->getReportData($fromDate, $toDate);

        return view('admin.orders', compact('orders', 'reports', 'fromDate', 'toDate'));
    }

    /**
     * Update the workflow status of an existing order and log the activity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => [
                'required',
                'in:Pending,Preparing,Cooking,Delivery,Completed',
            ],
        ]);

        $order = Order::findOrFail($id);
        if ($order->status === 'Cancelled') {
            return redirect()
                ->route('admin.orders')
                ->with('success', 'This order was cancelled and can no longer be changed.');
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;
        $order->save();

        ActivityLog::record(
            'updated',
            'Orders',
            "Admin updated Order #{$order->id} status from {$oldStatus} to {$newStatus}."
        );

        return redirect()
            ->route('admin.orders')
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Soft-delete an order and record an activity log entry before deletion.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        // Record log BEFORE deleting as required by Task 2.3
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

    /**
     * Compute analytics and report summaries for the admin dashboard.
     *
     * Cancelled and soft-deleted orders are excluded from total sales.
     *
     * @param  string|null  $fromDate
     * @param  string|null  $toDate
     * @return array
     */
    private function getReportData(?string $fromDate, ?string $toDate): array
    {
        $baseQuery = Order::query();

        if ($fromDate) {
            $baseQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $baseQuery->whereDate('created_at', '<=', $toDate);
        }

        // a. Total orders and total sales (excluding Cancelled and soft-deleted orders)
        $totalOrders = (clone $baseQuery)->count();

        $totalSales = (clone $baseQuery)
            ->where('status', '!=', 'Cancelled')
            ->sum('pizza_price');

        // b. Orders by status
        $statusCounts = (clone $baseQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // c. Orders per day (newest first, limit 14 days if no date range specified)
        $ordersPerDayQuery = (clone $baseQuery)
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('count(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status != "Cancelled" THEN pizza_price ELSE 0 END) as total_sales')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc(DB::raw('DATE(created_at)'));

        if (!$fromDate && !$toDate) {
            $ordersPerDayQuery->limit(14);
        }

        $ordersPerDay = $ordersPerDayQuery->get();

        // d. Top 5 items: pizza name, number of orders, sales (excluding Cancelled from sales)
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

        return [
            'total_orders'  => $totalOrders,
            'total_sales'   => (float) $totalSales,
            'status_counts' => $statusCounts,
            'orders_per_day'=> $ordersPerDay,
            'top_items'     => $topItems,
        ];
    }
}
