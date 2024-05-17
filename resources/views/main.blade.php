<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- title -->
    <title>@yield('title', 'AWC-Payment')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    {{-- fontawsome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- DataTable CSS -->
    <link
        href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">
    <!-- DataTable CSS -->

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-a4e625d3.css') }}"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="{{asset('build/assets/app-a724cf2c.css')}}"> --}}
    <!-- Styles -->
    @yield('styles')
    <style>
        .awcpay-header {
            color: #f8f9f9;
            background-color: #199B94;
        }
    </style>
</head>

<body class="">
    @include('layouts.navigation')
    @yield('content')
    @include('flash')
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    {{-- dataTable --}}
    {{-- DataTable --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sl-1.7.0/datatables.min.js">
    </script>

    {{-- DataTable --}}
    <!-- DataTable JS -->
    <script>
        $(document).ready(function() {

            var table = $('.dataTable').DataTable();

            new $.fn.dataTable.Buttons(table, {
                buttons: [
                    'copy', 'excel', 'print'
                ]
            });
            table.buttons().container().appendTo($('.tablebtn', table.table().container()));
            $('.tablebtn .dt-buttons').removeClass('flex-wrap');
            $('.tablebtn .btn').removeClass('btn-secondary').addClass(
                'text-gray-900 bg-white border border-gray-600 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'
                );

        });
    </script>
    @yield('scripts')


</body>

</html>
