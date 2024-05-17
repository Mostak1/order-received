@extends('main')
@section('styles')
@endsection
@section('content')
    <!-- resources/views/orders/create.blade.php -->


    <div class="container mx-auto mt-5">
        <div class="w-1/2 bg-white shadow-md rounded mx-auto px-8 pt-6 pb-8 mb-4">
            <h1 class="text-2xl font-bold mb-4">Create Order</h1>
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
               @include('orders.form')
                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
