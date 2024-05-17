<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Received;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivedController extends Controller
{
    public function index()
    {
        $receiveds = Received::all();
        return view('receiveds.index', compact('receiveds'));
    }

    // Show the form for creating a new received item
    public function create()
    {
        $products = Product::all();
        return view('receiveds.create',compact('products'));
    }

    // Store a newly created received item in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'status' => 'required|string|max:255',
        ]);

        Received::create($validatedData);

        return redirect()->back()->with('success', 'Received item created successfully.');
    }

    // Display the specified received item
    public function show(Received $received)
    {
        return view('receiveds.show', compact('received'));
    }

    // Show the form for editing the specified received item
    public function edit(Received $received)
    {
        $products = Product::all();
        return view('receiveds.edit', compact('received', 'products'));
    }
    // Update the specified received item in the database
    public function update(Request $request, Received $received)
    {
        // Validate the incoming request data
        // $validatedData = $request->validate([
        //     'product_id' => 'sometimes|integer',
        //     'quantity' => 'sometimes|integer',
        //     'status' => 'sometimes|string|max:255',
        //     'received_time' => 'sometimes|date',
        // ]);
        try {
            // Use DB transaction to ensure data consistency
            DB::transaction(function () use ($received, $request) {
                // Update each field individually if present in the request
                if ($request->has('product_id')) {
                    $received->product_id = $request->product_id;
                }
                if ($request->has('quantity')) {
                    $received->quantity = $request->quantity;
                }
                if ($request->has('status')) {
                    $received->status = $request->status;
                }
                if ($request->has('received_time')) {
                    $received->received_time = $request->received_time;
                }
                $received->save();
            });
            return redirect()->back()->with('success', 'Received updated successfully.');
        } catch (\Exception $e) {
            // Handle the error and redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the Received. Please try again.'])->withInput();
        }
    }
    // Remove the specified received item from the database
    public function destroy(Received $received)
    {
        $received->delete();
        return redirect()->back()->with('success', 'Received item deleted successfully.');
    }
}
