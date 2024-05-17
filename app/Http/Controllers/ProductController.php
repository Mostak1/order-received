<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount(['orders', 'received'])->get();
        return view('products.index', compact('products'));
    }

    // Show the form for creating a new product
    public function create()
    {
        return view('products.create');
    }

    // Store a newly created product in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Display the specified product
    public function show(Product $product)
    {
        $product = Product::withCount(['orders', 'received'])->findOrFail($product->id);

        $totalOrdered = $product->total_ordered;
        $totalReceived = $product->total_received;
        $remainingQuantity = $product->remaining;
    
        return view('products.show', compact('product', 'totalOrdered', 'totalReceived', 'remainingQuantity'));
    }

    // Show the form for editing the specified product
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update the specified product in the database
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Remove the specified product from the database
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
