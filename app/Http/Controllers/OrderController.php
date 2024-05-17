<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('product')->get();
        return view('orders.index', compact('orders'));
    }

    // Show the form for creating a new order
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    // Store a newly created order in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'status' => 'string|max:255',
            'orders_time' => 'required',
        ]);
        Order::create($validatedData);
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    // Display the specified order
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // Show the form for editing the specified order
    public function edit(Order $order)
    {
        $products = Product::all();
        return view('orders.edit', compact('order','products'));
    }

    // Update the specified order in the database
    public function update(Request $request, Order $order)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'product_id' => 'sometimes|integer',
            'quantity' => 'sometimes|integer',
            'status' => 'sometimes|string|max:255',
            'orders_time' => 'sometimes|date',
        ]);
    
        try {
            // Use DB transaction to ensure data consistency
            DB::transaction(function () use ($order, $validatedData, $request) {
                // Update each field individually if present in the request
                if ($request->has('product_id')) {
                    $order->product_id = $request->product_id;
                }
                if ($request->has('quantity')) {
                    $order->quantity = $request->quantity;
                }
                if ($request->has('status')) {
                    $order->status = $request->status;
                }
                if ($request->has('orders_time')) {
                    $order->orders_time = $request->orders_time;
                }
                $order->save();
            });
    
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    
        } catch (\Exception $e) {
            // Handle the error and redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the order. Please try again.'])->withInput();
        }
    }

    // Remove the specified order from the database
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    // index,create,store,show,edit,update,delete, methode
}
