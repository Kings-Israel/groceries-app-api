<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;

class GroceryController extends Controller
{
    public function index(Request $request)
    {
        $query = Grocery::with('category')->where('is_available', true);

        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        $groceries = $query->get();
        return response()->json(['groceries' => $groceries]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:groceries,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $unavailable = [];
        foreach ($request->items as $item) {
            $grocery = Grocery::find($item['id']);
            if (!$grocery->is_available || $grocery->stock < $item['quantity']) {
                $unavailable[] = [
                    'id' => $grocery->id,
                    'name' => $grocery->name,
                    'available_stock' => $grocery->stock
                ];
            }
        }

        return response()->json([
            'available' => empty($unavailable),
            'unavailable_items' => $unavailable
        ]);
    }
}
