@extends('main')
@section('styles')
    <style>
        @media print {
            .d-p-none {
                display: none;
            }
        }

        .custom-export-button {
            background-color: #4CAF50;
            /* Green background */
            color: white;
            /* White text */
            border: none;
            /* Remove border */
            padding: 10px 20px;
            /* Add some padding */
            text-align: center;
            /* Center the text */
            text-decoration: none;
            /* Remove underline */
            display: inline-block;
            /* Display as inline-block */
            font-size: 16px;
            /* Increase font size */
            margin: 4px 2px;
            /* Add some margin */
            cursor: pointer;
            /* Add pointer cursor on hover */
            border-radius: 8px;
            /* Rounded corners */
        }

        .custom-export-button:hover {
            background-color: #45a049;
            /* Darker green on hover */
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
            <button id="filterApply" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Apply Filter</button>
            
        </div>
        <div class="mb-4">
            <a href="{{ route('products.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Product</a>
        </div>
        <div class=" bg-white mx-auto">
            <table class="min-w-full bg-white" id="product_table">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class=" py-2">SL</th>
                        <th class="w-1/6 py-2">Name</th>
                        <th class=" py-2">Price</th>
                        <th class="w-2/6 py-2">Orders</th>
                        <th class="w-2/6 py-2">Received</th>
                        <th class=" py-2">Remaining Orders</th>
                        <th class="w-1/6 py-2 d-p-none">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class=" bg-white mx-auto">
            <table class="min-w-full bg-white dataTable" id="dataTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="tablebtn" colspan="7"></th>
                    </tr>
                    <tr>
                        <th class=" py-2">SL</th>
                        <th class="w-1/6 py-2">Name</th>
                        <th class=" py-2">Price</th>
                        <th class="w-2/6 py-2">Orders</th>
                        <th class="w-2/6 py-2">Received</th>
                        <th class=" py-2">Remaining Orders</th>
                        <th class="w-1/6 py-2 d-p-none">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- @foreach ($products as $product)
                        <tr>
                            <td class="border px-4 py-2">{{ $product->id }}</td>
                            <td class="border px-4 py-2">{{ $product->name }}</td>
                            <td class="border px-4 py-2">{{ $product->price }}</td>
                            <td class="border px-4 py-2">
                                @foreach ($product->orders as $item)
                                    <li class="flex mb-1">
                                        <span
                                            class="text-yellow-600">{{ date('Y-m-d', strtotime($item->orders_time)) }}</span>:
                                        <a href="{{ route('orders.show', $item->order_id) }}"> <span
                                                class="text-green-600">{{ $item->status }}</span></a>=
                                        <span> {{ $item->quantity }}</span>
                                    </li>
                                @endforeach
                                <p class="text-green-500">Total Ordered: {{ $product->total_ordered }}</p>
                            </td>
                            <td class="border px-4 py-2">
                                @foreach ($product->received as $item)
                                    <li class="flex mb-1">
                                        <span
                                            class="text-yellow-600">{{ date('Y-m-d', strtotime($item->received_time)) }}</span>:
                                        <a href="{{ route('receiveds.show', $item->received_id) }}"> <span
                                                class="text-green-600">{{ $item->status }}</span></a> =
                                        <span>{{ $item->quantity }}</span>
                                    </li>
                                @endforeach
                                <p class="text-green-500">Total Received: {{ $product->total_received }}</p>
                            </td>
                            <td class="border px-4 py-2">{{ $product->total_ordered - $product->total_received }} Pcs</td>
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
                    @endforeach --}}
                </tbody>
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
                scrollY: "75vh",
                scrollX: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('products.index') }}",
                    data: function(d) {
                        d.order_ids = $('#order_ids').val(); // Include selected order IDs in request
                        d.received_ids = $('#received_ids')
                    .val(); // Include selected received IDs in request
                    d.filterProduct = $('#filterProduct').is(':checked') ? 1 : 0; // Include selected
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
                        data: 'received_list',
                        name: 'received_list'
                    },
                    {
                        data: 'remaining_orders',
                        name: 'remaining_orders'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip', // Add this line to include the buttons
                lengthMenu: [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]
                ],
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'custom-export-button', // Add custom CSS class
                        exportOptions: {
                            columns: ':visible:not(.not-exported)' // Exclude columns with class 'not-exported' from print
                        },
                        action: function(e, dt, button, config) {
                            var originalLength = dt.settings()[0]._iDisplayLength;
                            dt.page.len(-1).draw(); // Fetch all data
                            $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button,
                                config);
                            dt.one('draw', function() {
                                dt.page.len(originalLength).draw(); // Reset page length
                            });
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        className: 'custom-export-button', // Add custom CSS class
                        exportOptions: {
                            columns: ':visible:not(.not-exported)' // Exclude columns with class 'not-exported' from export
                        },
                        action: function(e, dt, button, config) {
                            var originalLength = dt.settings()[0]._iDisplayLength;
                            dt.page.len(-1).draw(); // Fetch all data
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button,
                                config);
                            dt.one('draw', function() {
                                dt.page.len(originalLength).draw(); // Reset page length
                            });
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        className: 'custom-export-button', // Add custom CSS class
                        exportOptions: {
                            columns: ':visible:not(.not-exported)' // Exclude columns with class 'not-exported' from export
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader = {
                                color: 'white',
                                fillColor: '#4CAF50',
                                alignment: 'center'
                            };
                        },
                        action: function(e, dt, button, config) {
                            var originalLength = dt.settings()[0]._iDisplayLength;
                            dt.page.len(-1).draw(); // Fetch all data
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button,
                                config);
                            dt.one('draw', function() {
                                dt.page.len(originalLength).draw(); // Reset page length
                            });
                        }
                    }
                ],
                columnDefs: [{
                    targets: -1, // Last column (Action column)
                    className: 'not-exported' // Add this class to exclude from export
                }]
            });
            // $('#order_ids, #received_ids').on('change', function() {
            //     table.ajax.reload();
            // });
            $('#filterApply').click(function() {
                table.ajax.reload();
            })
            // Reset the length parameter after export to avoid loading all data again on normal requests
            // table.on('draw', function() {
            //     table.ajax.params().length = 10; // Set back to default page length
            // });
            // product_table.ajax.reload();
        });
    </script>
@endsection
