@extends('main')
@section('styles')
    <style>
        @media print {
            .d-p-none {
                display: none;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Products</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <div class="my-4">
            <div class="text-l">Filter Product</div>
            <label for=""> Orders : </label>
            <select class="select2" multiple name="order_ids[]" id="order_ids">
                @foreach ($orders as $item)
                    <option value="{{ $item->id }}">{{ $item->status }}</option>
                @endforeach
            </select>
            <label for=""> Received : </label>
            <select class="select2" multiple name="received_ids[]" id="received_ids">
                @foreach ($receiveds as $item)
                    <option value="{{ $item->id }}">{{ $item->status }}</option>
                @endforeach
            </select>
            <input type="checkbox" name="filterProduct" id="filterProduct">
            <label for="filterProduct">Null Order & Received</label>
            <button id="filterApply" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Apply
                Filter</button>

        </div>
        <div class="mb-4">
            <a href="{{ route('products.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Product</a>
            <div class="my-3 text-xl">Total Order: <span id="orderTotal"></span></div>
            <div class="text-xl">Total Received: <span id="receivedTotal"></span></div>
        </div>
        <div class=" bg-white mx-auto">
            <table class="min-w-full bg-white" id="product_table">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="tablebtn" colspan="9"></th>
                    </tr>
                    <tr>
                        <th class=" py-2">SL</th>
                        <th class="w-1/6 py-2">Name</th>
                        <th class=" py-2">Price</th>
                        <th class="w-2/6 py-2">Orders</th>
                        <th class=" py-2">orderTotal</th>
                        <th class="w-2/6 py-2">Received</th>
                        <th class=" py-2">receivedTotal</th>
                        <th class=" py-2">Remaining Orders</th>
                        <th class="w-1/6 py-2">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#product_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'asc']
                ],
                scrollX: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('products.index') }}",
                    data: function(d) {
                        d.order_ids = $('#order_ids').val(); // Include selected order IDs in request
                        d.received_ids = $('#received_ids')
                            .val(); // Include selected received IDs in request
                        d.filterProduct = $('#filterProduct').is(':checked') ? 1 :
                            0; // Include selected
                    },
                    dataSrc: function(response) {
                        // Update the order total display
                        $('#orderTotal').text(response.orderTotal.toFixed(2));
                        $('#receivedTotal').text(response.receivedTotal.toFixed(2));
                        console.log(response.data.data);
                        return response.data.data; // Return the data portion for DataTables
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'orders_list',
                        name: 'orders_list'
                    },
                    {
                        data: 'orderTotal',
                        name: 'orderTotal'
                    },
                    {
                        data: 'received_list',
                        name: 'received_list'
                    },
                    {
                        data: 'receivedTotal',
                        name: 'receivedTotal'
                    },
                    {
                        data: 'remaining_orders',
                        name: 'remaining_orders'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'print:disabled',
                        orderable: false,
                        searchable: false
                    }
                ],
                // dom: 'Bfrtip', // Add this line to include the buttons
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                columnDefs: [{
                    targets: -1, // Last column (Action column)
                    className: 'print:disabled' // Add this class to exclude from export
                }]
            });
            $('#filterApply').click(function() {
                table.ajax.reload();
                
                
            })
            new $.fn.dataTable.Buttons(table, {
                buttons: [
                    'copy', 'excel', 'print', 'pdf'
                ]
            });
            table.buttons().container().appendTo($('.tablebtn', table.table().container()));
            $('.tablebtn .dt-buttons').removeClass('flex-wrap');
            $('.tablebtn .btn').removeClass('btn-secondary').addClass(
                'text-gray-900 bg-white border border-gray-600 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'
            );
        });
    </script>
@endsection
