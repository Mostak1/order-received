@extends('main')
@section('styles')
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Products</h1>
        <div class="w-1/4 mx-auto">
            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

            <div class="mb-4">
                <h3 class="text-xl font-bold mb-2">Orders</h3>
                <ul>
                    @foreach ($product->orders as $item)
                        <li class="flex">
                            <span>{{date('Y-m-d', strtotime($item->orders_time)) }}</span>  => 
                            <span>{{ $item->quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="mb-2">Total Ordered: <span class="text-blue-500">{{ $totalOrdered }}</span></p>
            <div class="mb-4">
                <h3 class="text-xl font-bold mb-2">Received</h3>
                <ul>
                    @foreach ($product->received as $item)
                        <li class="flex">
                            <span>{{ $item->status }}</span>  => 
                            <span>{{ $item->quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <p class="mb-2">Total Received: <span class="text-green-500">{{ $totalReceived }}</span></p>
            <p>Remaining Quantity: <span class="text-red-500">{{ $remainingQuantity }}</span></p>
        </div>
    </div>
@endsection
