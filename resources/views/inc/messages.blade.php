@if($errors->any())
    <script type="module">
        let error = '<ul class="text-left mb-0">';
        @foreach($errors->all() as $error)
            error += '<li>{{ $error }}</li>';
        @endforeach
            error += '</ul>';

        Swal.fire({
            icon: 'error',
            title: 'Error...',
            html: error,
        });
    </script>
@endif

@if (session('status'))
    <script type="module">
        Swal.fire({
            icon: 'success',
            title: 'Success...',
            text: `{{ session('status') }}`,
        });
    </script>
@endif
