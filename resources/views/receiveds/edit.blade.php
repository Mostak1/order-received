@extends('main')
@section('styles')
@endsection
@section('content')
    <div class="container mx-auto mt-5">
        <div class="w-full max-w-screen-lg mx-auto">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-2xl font-bold mb-4">Edit Received</h1>
                <form action="{{ route('receiveds.update', $received->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                 @include('receiveds.form')
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Update Received
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
