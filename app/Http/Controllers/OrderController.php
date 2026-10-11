<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
      // Save a new pizza order
    public function store(Request $request)
    {

    // Check if the order details are valid
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:1000',
            'pizza_name' => 'required|string',
            'message' => 'nullable|string|max:2000',
        ]);

         // Set the price of each pizza
        $prices = [
            'Creamy Spinach Pizza' => 299,
            'All Meat Pizza' => 349,
            'Garlic Shrimp' => 379,
        ];

        $pizzaName = $request->input('pizza_name');

        // Check if the selected pizza is valid
        if (!array_key_exists($pizzaName, $prices)) {
            return back()->withErrors([
                'pizza_name' => 'Please select a valid pizza.',
            ]);
        }

          // Save the order under the logged-in customer
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'pizza_name' => $pizzaName,
            'pizza_price' => $prices[$pizzaName],
            'message' => $request->input('message'),
            'status' => 'Pending',
        ]);

        \App\Models\ActivityLog::record(
            'created',
            'Orders',
            "Order #{$order->id} created for {$order->pizza_name} (₱" . number_format($order->pizza_price, 2) . ") by {$order->customer_name}."
        );

         // Return a success message
        return response()->json([
            'message' => 'Your order has been saved successfully!',
        ], 201);
    }
}