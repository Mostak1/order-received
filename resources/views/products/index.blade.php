@extends('main')
@section('styles')
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Products</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('products.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Product</a>
        </div>
        <div class=" bg-white mx-auto">
            <table class="min-w-full bg-white dataTable" id="dataTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="tablebtn" colspan="6"></th>

                    </tr>
                    <tr>
                        <th class=" py-2">SL</th>
                        <th class="w-1/6 py-2">Name</th>
                        <th class="w-2/6 py-2">Orders</th>
                        <th class="w-2/6 py-2">Received</th>
                        <th class=" py-2">Remaining Orders</th>
                        <th class="w-1/6 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($products as $product)
                        <tr>
                            <td class="border px-4 py-2">{{ $product->id }}</td>
                            <td class="border px-4 py-2">{{ $product->name }}</td>
                            <td class="border px-4 py-2">       
                                @foreach ($product->orders as $item)
                                <li class="flex mb-1">
                                    <span class="text-yellow-600">{{date('Y-m-d', strtotime($item->orders_time)) }}</span>:
                                    <span class="text-green-600">{{ $item->status }}</span>=
                                    <span> {{ $item->quantity }}</span>
                                    <a href="{{ route('orders.edit', $item->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 mx-1 text-white font-bold py-1 px-2 rounded"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <form action="{{ route('orders.destroy', $item->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                            onclick="return confirm('Are you sure you want to delete this order?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </li>
                            @endforeach
                            <p class="text-green-500">Total Ordered: {{ $product->total_ordered }}</p>
                            </td>
                            <td class="border px-4 py-2">       
                                @foreach ($product->received as $item)
                                <li class="flex mb-1">
                                    <span>{{ $item->status }}</span>  => 
                                    <span>{{ $item->quantity }}</span>
                                    <a href="{{ route('receiveds.edit', $item->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white mx-1 font-bold p-1 rounded"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <form action="{{ route('receiveds.destroy', $item->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                            onclick="return confirm('Are you sure you want to delete this order?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </li>
                            @endforeach
                            <p class="text-green-500">Total Received: {{ $product->total_received }}</p>
                            </td>
                            <td class="border px-4 py-2">{{ $product->total_ordered -$product->total_received }} Pcs</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('products.show', $product->id) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">View</a>
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                        onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
