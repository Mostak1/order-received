@extends('main')
@section('styles')
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Create New Product</h1>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="w-1/4 mx-auto">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Use PUT method for updating -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input type="text" name="name" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('name', $product->name) }}"> <!-- Populate with existing data -->
                </div>
                <div class="mb-4">
                    <label for="details" class="block text-gray-700 font-bold mb-2">Details:</label>
                    <textarea name="details" id="details"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('details', $product->details) }}</textarea> <!-- Populate with existing data -->
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-bold mb-2">Price:</label>
                    <input type="text" name="price" id="price"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('price', $product->price) }}"> <!-- Populate with existing data -->
                </div>
                <div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                </div>
            </form>
        </div>
        
    </div>
@endsection
