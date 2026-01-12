<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.grocery_id' => 'required|exists:groceries,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_location' => 'required|string',
            'delivery_latitude' => 'nullable|numeric',
            'delivery_longitude' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = $request->user();
        $totalAmount = 0;

        // Calculate total and validate stock
        foreach ($request->items as $item) {
            $grocery = Grocery::find($item['grocery_id']);
            if (!$grocery->is_available || $grocery->stock < $item['quantity']) {
                return response()->json([
                    'message' => 'Item not available: ' . $grocery->name
                ], 400);
            }
            $totalAmount += $grocery->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'delivery_location' => $request->delivery_location,
            'delivery_latitude' => $request->delivery_latitude,
            'delivery_longitude' => $request->delivery_longitude,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Create order items and update stock
        foreach ($request->items as $item) {
            $grocery = Grocery::find($item['grocery_id']);
            $subtotal = $grocery->price * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'grocery_id' => $grocery->id,
                'quantity' => $item['quantity'],
                'price' => $grocery->price,
                'subtotal' => $subtotal
            ]);

            $grocery->decrement('stock', $item['quantity']);
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order->load('items.grocery')
        ], 201);
    }

    public function show($id)
    {
        $order = Order::with('items.grocery')->findOrFail($id);
        info($order);
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['order' => $order]);
    }

    public function myOrders()
    {
        $orders = Order::with('items.grocery')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'delivered') {
            return response()->json(['message' => 'Can only rate delivered orders'], 400);
        }

        $order->update([
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'order' => $order
        ]);
    }
}
