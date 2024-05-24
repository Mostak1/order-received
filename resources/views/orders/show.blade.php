@extends('main')
@section('styles')
<style>
    @media print {
#printArea{
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    border: none;
    background-color: #fff;
    box-shadow: none;
    border-radius: 0;
    font-family: inherit;
    font-size: inherit;
    font-weight: inherit;
    line-height: inherit;
    resize: none;
    overflow: auto;
}
    }
</style>
@endsection
@section('content')
    <!-- resources/views/orders/create.blade.php -->


    <div class="container mx-auto mt-5"  >
        <div class="w-3/4 bg-white shadow-md rounded mx-auto px-8 pt-6 pb-8 mb-4" id="printArea">
            <span class="text-2xl font-bold mb-4">Order Details</span>
            <a href="{{ route('orders.edit', $order->id) }}"
                class="bg-yellow-500 print:hidden hover:bg-yellow-700 mx-1 text-white font-bold py-1 px-2 rounded"><i class="fa-regular fa-pen-to-square"></i></a> <span class="bg-yellow-500 hover:bg-yellow-700 mx-1 text-white font-bold py-1 px-2 print:hidden rounded cursor-pointer" id="print">Print</span>
            <input type="text" id="orderId" hidden value="{{ $order->id }}">
            <div class="flex">
                <div class="w-2/3 me-3">
                    <div class="mb-4">
                        <div class="flex">
                            <div class="w-2/6 name">Product Name</div>
                            <div class="w-1/6">Quantity</div>
                            <div class="w-1/6">Price</div>
                            <div class="w-1/6">Total</div>
                        </div>
                        <hr>
                        <div id="selectedProducts" class="mt-2">
                            @foreach ($details as $detail)
                            <div class="flex mb-2 order-item" data-id="{{ $detail->product_id }}">
                                <div class="w-2/6 name">{{ $detail->product->name }}<input type="number" class="pid"
                                        style="width:50px" hidden value="{{ $detail->product_id }}" min="1"></div>
                                <div class="w-1/6">{{ $detail->quantity }}</div>
                                <div class="w-1/6"><span class="price"
                                        data-sprice="{{ $detail->price }}">{{ $detail->price }}</span></div>
                                <div class="w-1/6"><span class="total">{{ $detail->total }}</span></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-l font-bold mb-2" for="status">
                            Order Total : <span id="orderTotal">{{ $order->total ?? '' }}</span>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-l font-bold mb-2" for="status">
                            Order Name
                        </label>
                        <p class=" italic">{{ $order->status ?? '' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-l font-bold mb-2" for="orders_time">
                            Order Date
                        </label>
                        <p class=" italic">{{ $order->orders_time }}</p>
                    </div>
                </div>
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
                    url: '{{ url('orders') }}/' + id,
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
                                text: 'Order Updated Successfully.',
                            });
                        }
                    },
                    error: function(error) {
                        // console.log(error.message);
                    }
                });
            });
             // print section
             $('#print').click(function(){
                 $("#printArea").removeClass("w-3/4");
                 var printContents = $('#printArea').html();
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    location.reload();
             });
        });
    </script>
@endsection
