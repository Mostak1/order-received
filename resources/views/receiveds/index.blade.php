@extends('main')
@section('styles')
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Received Items</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('receiveds.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Received Item</a>
        </div>
        <div class="w-3/4 bg-white mx-auto">
            <table class="min-w-full bg-white dataTable" id="dataTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="tablebtn" colspan="5"></th>
                        
                    </tr>
                    <tr>
                        <th class=" py-2">SL</th>
                        <th class="w-1/4 py-2">Quantity</th>
                        <th class="w-1/4 py-2">Received Voucher Name</th>
                        <th class="w-1/4 py-2">Total</th>
                        <th class="w-1/4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($receiveds as $order)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->index +1 }}</td>
                            <td class="border px-4 py-2">{{ $order->quantity }}</td>
                            <td class="border px-4 py-2">{{ $order->status }}</td>
                            <td class="border px-4 py-2">{{ $order->total }}</td>
                            <td class="border px-4 py-2 ">
                                <a href="{{ route('receiveds.show', $order->id) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">View</a>
                                <a href="{{ route('receiveds.edit', $order->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                                <form action="{{ route('receiveds.destroy', $order->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                        onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
