<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Received;
use App\Models\ReceivedDetail;
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
        return view('receiveds.create', compact('products'));
    }

    // Store a newly created received item in the database
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = new Received();
            $order->quantity = $request->totalItems;
            $order->status = $request->status;
            $order->total = $request->orderTotal;
            $order->received_time = $request->date;
            $order->save();
            foreach ($request->items as $item) {
                $details = new ReceivedDetail();
                $details->received_id = $order->id;
                $details->product_id = $item['id'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
                $details->total = $item['quantity']*$item['price'];
                $details->status = $request->status;
                $details->received_time = $request->date;
                $details->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'data' => $order]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Order received failed: ' . $e->getMessage()); // Log the error
            return response()->json(['success' => false, 'error' => 'Order could not be received properly. Please try again. ' . $e->getMessage()]);
        }
    }

    // Display the specified received item
    public function show(Received $received)
    {
        $details = ReceivedDetail::with('product')->where('received_id', $received->id)->get();
        return view('receiveds.show', compact('received', 'details'));
    }

    // Show the form for editing the specified received item
    public function edit(Received $received)
    {
        $products = Product::all();
        $details = ReceivedDetail::with('product')->where('received_id', $received->id)->get();
        return view('receiveds.edit', compact('received', 'products', 'details'));
    }
    // Update the specified received item in the database
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Find the order
            $received = Received::findOrFail($id);
            // Update received details
            $received->quantity = $request->totalItems;
            $received->status = $request->status;
            $received->total = $request->orderTotal;
            $received->received_time = $request->date;
            $received->save();
            // Clear existing received details
            ReceivedDetail::where('received_id', $id)->delete();
            // Add updated received details
            foreach ($request->items as $item) {
                $details = new ReceivedDetail();
                $details->received_id = $received->id;
                $details->product_id = $item['pid'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
                $details->total = $item['quantity']* $item['price'];
                $details->status = $request->status;
                $details->received_time = $request->date;
                $details->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'data' => $received]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('received update failed: ' . $e->getMessage()); // Log the error
            return response()->json(['success' => false, 'error' => 'received could not be updated properly. Please try again. ' . $e->getMessage()]);
        }
    }
    // Remove the specified received item from the database
    public function destroy(Received $received)
    {
         DB::beginTransaction();
         try {
             ReceivedDetail::where('received_id', $received->id)->delete();
             $received->delete();
             DB::commit();
             return redirect()->route('receiveds.index')->with('success', 'Received Order deleted successfully.');
         } catch (\Exception $e) {
             DB::rollback();
             Log::error('receiveds deletion failed: ' . $e->getMessage()); // Log the error
             return redirect()->route('receiveds.index')->with('error', 'Received Order deletion failed. Please try again.');
         }
    }
}
