@extends('main')
@section('styles')
@endsection
@section('content')
    <!-- resources/views/orders/create.blade.php -->


    <div class="container mx-auto mt-5">
        <div class="w-3/4 bg-white shadow-md rounded mx-auto px-8 pt-6 pb-8 mb-4">
            <h1 class="text-2xl font-bold mb-4">Create Order</h1>
            @csrf
            {{-- @include('orders.form') --}}
            <input type="text" hidden id="orderId" value="{{ $received->id }}">
            <div class="flex">
                <div class="w-2/3 me-3">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="product_id">
                            Product
                        </label>
                        <select name="product_id" id="product_id"
                            class="select2 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @foreach ($products as $product)
                                <option data-pname="{{ $product->name }}" data-id="{{ $product->id }}"
                                    value="{{ $product->id }}"
                                    {{ ($received->product_id ?? old('product_id')) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <div class="flex">
                            <div class="w-2/3 name">Product Name</div>
                            <div class="w-1/3">Quantity</div>
                        </div>
                        <hr>
                        <div id="selectedProducts" class="mt-2">
                            @foreach ($details as $detail)
                                <div class="flex mb-2 order-item" data-id="{{ $detail->product_id }}">
                                    <div class="w-2/3 name">{{ $detail->product->name }}<input type="number" class="pid"
                                            style="width:50px" hidden value="${productId}" min="1"></div>
                                    <div class="w-1/3"><input type="number" class="pquantity" style="width:100px"
                                            value="{{ $detail->quantity }}" min="1"></div>
                                    <button type="button"
                                        class="inline-block px-2 py-1 text-sm font-semibold leading-none text-white bg-red-500 rounded hover:bg-red-600 remove-product">Remove</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Received Order Name
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="status" name="status" type="text" value="{{ $received->status ?? '' }}"
                            placeholder="received Name">
                        @error('status')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="orders_time">
                            Received Order Date
                        </label>
                        @php
                            $currentDate = date('Y-m-d');
                        @endphp
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="orders_time" name="orders_time"
                            value="{{ $received->receiveds_time ?? old('receiveds_time') ? \Carbon\Carbon::parse($received->receiveds_time)->format('Y-m-d') : now()->format('Y-m-d') }}"
                            type="date" placeholder="Quantity">
                        @error('quantity')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button id="submitOrder"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Update Received
                </button>
            </div>

        </div>
    </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(document).on('click', '.remove-product', function() {
                $(this).closest('.order-item').remove();
            });
            $('#product_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var productId = selectedOption.val();
                var productName = selectedOption.text();
                // Check if an item with the same id already exists
                var existingItem = $('#selectedProducts').find(`.order-item[data-id="${productId}"]`);
                if (existingItem.length > 0) {
                    var quantity = parseInt(existingItem.find('.pquantity').val());
                    quantity++;
                    existingItem.find('.pquantity').val(quantity);
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Added',
                        text: `The ${productName} Already Added.`,
                    });
                } else {
                    var html = `
                <div class="flex mb-2 order-item" data-id="${productId}">
                                <div class="w-2/3 name">${productName}  <input type="number" class="pid" style="width:50px" hidden value="${productId}" min="1"></div>
                                <div class="w-1/3"><input type="number" class="pquantity" style="width:100px"  value="1" min="1"></div>
                                <button type="button" class="inline-block px-2 py-1 text-sm font-semibold leading-none text-white bg-red-500 rounded hover:bg-red-600 remove-product">Remove</button>

                            </div>
                `;
                    $('#selectedProducts').append(html);
                }
            });
            $('#submitOrder').click(function() {
                var id = $('#orderId').val();
                var status = $('#status').val();
                var date = $('#orders_time').val();
                var items = [];
                var totalItems = 0;
                $('#selectedProducts .order-item').each(function() {
                    var id = $(this).data('id');
                    var quantity = $(this).find('.pquantity').val();
                    totalItems += parseInt(quantity);
                    items.push({
                        pid: id,
                        quantity: quantity,
                    });
                });
                console.log(items);
                console.log(totalItems);
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                $.ajax({
                    url: '{{ url('receiveds') }}/' + id,
                    type: 'PUT',
                    data: {

                        items: items,
                        status: status,
                        date: date,
                        totalItems: totalItems,
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response.data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Received Order Updated Successfully.',
                            });
                        }
                    },
                    error: function(error) {
                        // console.log(error.message);
                        var response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error || 'An unexpected error occurred.',
                        });
                    }
                });
            });
        });
    </script>
@endsection
