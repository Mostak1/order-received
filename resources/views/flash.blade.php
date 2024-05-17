<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($message = Session::get('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ $message }}',
        });
    </script>
@endif


@if ($message = Session::get('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Opps! !',
        text: '{{ $message }}',
    });
</script>
   
@endif
@if ($message = Session::get('warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Warning!',
        text: '{{ $message }}',
    });
</script>
   
@endif
@if ($message = Session::get('info'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Please Wait!',
        text: '{{ $message }}',
    });
</script>
   
@endif
{{-- For Laravel Controller Validation --}}
@if ($errors->any())
    <script>
        Swal.fire({
            title: 'Oops!',
            text: 'There were some errors with your form.',
            html: '@foreach ($errors->all() as $error) <div class="alert alert-danger">{{ $error }}</div>@endforeach',
        });
    </script>
@endif
