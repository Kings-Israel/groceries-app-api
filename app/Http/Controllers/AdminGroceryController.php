<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;

class AdminGroceryController extends Controller
{
    public function index()
    {
        $groceries = Grocery::with('category')->get();
        return response()->json(['groceries' => $groceries]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'stock' => 'required|integer|min:0'
        ]);

        $imagePath = $request->file('image')->store('groceries', 'public');

        $grocery = Grocery::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'unit' => $request->unit,
            'stock' => $request->stock
        ]);

        return response()->json([
            'message' => 'Grocery created successfully',
            'grocery' => $grocery
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $grocery = Grocery::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'image' => 'sometimes|image',
            'price' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string',
            'stock' => 'sometimes|integer|min:0',
            'is_available' => 'sometimes|boolean'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('groceries', 'public');
            $grocery->image = $imagePath;
        }

        $grocery->update($request->except('image'));

        return response()->json([
            'message' => 'Grocery updated successfully',
            'grocery' => $grocery
        ]);
    }

    public function destroy($id)
    {
        $grocery = Grocery::findOrFail($id);
        $grocery->delete();

        return response()->json(['message' => 'Grocery deleted successfully']);
    }
}
