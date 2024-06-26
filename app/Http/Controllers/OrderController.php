<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
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
        $orders = Order::get();
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
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->quantity = $request->totalItems;
            $order->status = $request->status;
            $order->total = $request->orderTotal;
            $order->orders_time = $request->date;
            $order->save();
            foreach ($request->items as $item) {
                $details = new OrderDetail();
                $details->order_id = $order->id;
                $details->product_id = $item['id'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
                $details->total = $item['quantity'] * $item['price'];
                $details->status = $request->status;
                $details->orders_time = $request->date;
                $details->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'data' => $order]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Order submission failed: ' . $e->getMessage()); // Log the error
            return response()->json(['success' => false, 'error' => 'Order could not be submitted properly. Please try again. ' . $e->getMessage()]);
        }
    }
    // Display the specified order
    public function show(Order $order)
    {
        $details = OrderDetail::with('product')->where('order_id', $order->id)->get();
        return view('orders.show', compact('order', 'details'));
    }

    // Show the form for editing the specified order
    public function edit(Order $order)
    {
        $products = Product::all();
        $details = OrderDetail::with('product')->where('order_id', $order->id)->get();
        return view('orders.edit', compact('order', 'products', 'details'));
    }

    // Update the specified order in the database
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Find the order
            $order = Order::findOrFail($id);

            // Update order details
            $order->quantity = $request->totalItems;
            $order->status = $request->status;
            $order->total = $request->orderTotal;
            $order->orders_time = $request->date;
            $order->save();

            // Clear existing order details
            OrderDetail::where('order_id', $id)->delete();

            // Add updated order details
            foreach ($request->items as $item) {
                $details = new OrderDetail();
                $details->order_id = $order->id;
                $details->product_id = $item['id'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
                $details->total = $item['quantity'] * $item['price'];
                $details->status = $request->status;
                $details->orders_time = $request->date;
                $details->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $order]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Order update failed: ' . $e->getMessage()); // Log the error
            return response()->json(['success' => false, 'error' => 'Order could not be updated properly. Please try again. ' . $e->getMessage()]);
        }
    }
    // public function update(Request $request, Order $order)
    // {
    //     // Validate the incoming request data
    //     $validatedData = $request->validate([
    //         'product_id' => 'sometimes|integer',
    //         'quantity' => 'sometimes|integer',
    //         'status' => 'sometimes|string|max:255',
    //         'orders_time' => 'sometimes|date',
    //     ]);

    //     try {
    //         // Use DB transaction to ensure data consistency
    //         DB::transaction(function () use ($order, $validatedData, $request) {
    //             // Update each field individually if present in the request
    //             if ($request->has('product_id')) {
    //                 $order->product_id = $request->product_id;
    //             }
    //             if ($request->has('quantity')) {
    //                 $order->quantity = $request->quantity;
    //             }
    //             if ($request->has('status')) {
    //                 $order->status = $request->status;
    //             }
    //             if ($request->has('orders_time')) {
    //                 $order->orders_time = $request->orders_time;
    //             }
    //             $order->save();
    //         });

    //         return redirect()->route('orders.index')->with('success', 'Order updated successfully.');

    //     } catch (\Exception $e) {
    //         // Handle the error and redirect back with an error message
    //         return redirect()->back()->withErrors(['error' => 'An error occurred while updating the order. Please try again.'])->withInput();
    //     }
    // }

    // Remove the specified order from the database
    public function destroy(Order $order)
    {
        // Wrap the delete operation in a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            OrderDetail::where('order_id', $order->id)->delete();
            $order->delete();
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order deletion failed: ' . $e->getMessage()); // Log the error
            return redirect()->route('orders.index')->with('error', 'Order deletion failed. Please try again.');
        }
    }
    
    // index,create,store,show,edit,update,delete, methode
}
