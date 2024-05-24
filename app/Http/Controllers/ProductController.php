<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Received;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::get();
        $receiveds = Received::get();
        $query = Product::withCount(['orders', 'received'])
            ->where(function ($query) {
                $query->has('orders')
                    ->orHas('received');
            });

        // Apply filter based on order IDs
        if ($request->has('order_ids')) {
            $orderIds =  $request->input('order_ids');
            $query->whereHas('orders', function ($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            });
        }

        // Apply filter based on received IDs
        if ($request->has('received_ids')) {
            $receivedIds = $request->input('received_ids');
            $query->whereHas('received', function ($query) use ($receivedIds) {
                $query->whereIn('received_id', $receivedIds);
            });
        }
        $products = $query->get();
        if ($request->ajax()) {
            $query = Product::withCount(['orders', 'received']);

            // Apply filter based on order IDs
            if ($request->has('filterProduct') && $request->filterProduct == 1) {

                $query->where(function ($query) {
                    $query->has('orders')
                        ->orHas('received');
                });
            }
            // Apply filter based on order IDs
            if ($request->has('order_ids')) {
                $orderIds =  $request->input('order_ids');
                $query->whereHas('orders', function ($query) use ($orderIds) {
                    $query->whereIn('order_id', $orderIds);
                });
            }

            // Apply filter based on received IDs
            if ($request->has('received_ids')) {
                $receivedIds = $request->input('received_ids');
                $query->whereHas('received', function ($query) use ($receivedIds) {
                    $query->whereIn('received_id', $receivedIds);
                });
            }

            return DataTables::of($query)
                ->addColumn('received_list', function ($product) {
                    $html = '<ul>';
                    foreach ($product->received as $item) {
                        $html .= '<li class="flex mb-1">';
                        $html .= '<span class="text-yellow-600">' . date('Y-m-d', strtotime($item->orders_time)) . '</span>: ';
                        $html .= '<a href="' . route('receiveds.show', $item->received_id) . '"> <span class="text-green-600">' . $item->status . '</span></a> = ';
                        $html .= '<span>' . $item->quantity . '</span>';
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->addColumn('orders_list', function ($product) {
                    $html = '<ul>';
                    foreach ($product->orders as $item) {
                        $html .= '<li class="flex mb-1">';
                        $html .= '<span class="text-yellow-600">' . date('Y-m-d', strtotime($item->orders_time)) . '</span>: ';
                        $html .= '<a href="' . route('orders.show', $item->order_id) . '"> <span class="text-green-600">' . $item->status . '</span></a> = ';
                        $html .= '<span>' . $item->quantity . '</span>';
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->addColumn('remaining_orders', function ($product) {
                    return $product->orders_count - $product->received_count;
                })
                ->addColumn('action', function ($product) {
                    return '<a href="' . route('products.show', $product->id) . '"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">View</a>
                            <a href="' . route('products.edit', $product->id) . '"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                            <form action="' . route('products.destroy', $product->id) . '" method="POST" class="inline-block" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                    onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</button>
                            </form>';
                })
                ->rawColumns(['action', 'orders_list','received_list'])
                ->make(true);
        }

        return view('products.index', compact('orders', 'receiveds', 'products'));
    }
    // public function index()
    // {
    //     if (request()->ajax()) {
    //         $products = Product::withCount(['orders', 'received'])->get();
    //         return DataTables::of($products);
    //     }
    //     $products = Product::withCount(['orders', 'received'])->get();
    //     return view('products.index', compact('products'));
    // }

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
            'price' => 'required',
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
            'price' => 'required',
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
