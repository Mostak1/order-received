<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="product_id">
        Product
    </label>
    <select  name="product_id" id="product_id"
        class="select2 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        @foreach ($products as $product)
            <option value="{{ $product->id }}" {{ ($received->product_id ?? old('product_id')) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
        @endforeach
    </select>
    @error('product_id')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
        Quantity
    </label>
    <input
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        id="quantity" name="quantity" type="text" value="{{$received->quantity ?? ''}}" placeholder="Quantity">
    @error('quantity')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
        Status
    </label>
    <input
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        id="status" name="status" type="text" value="{{$received->status ?? ''}}" placeholder="Status">
    @error('status')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="received_time">
        Order Date
    </label>
    @php
        $currentDate = date('Y-m-d');
    @endphp
    <input
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        id="received_time" name="received_time" value="{{ ($received->received_time ?? old('received_time')) ? \Carbon\Carbon::parse($received->received_time)->format('Y-m-d') : now()->format('Y-m-d') }}" type="date" placeholder="Order Date">
    @error('received_time')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

