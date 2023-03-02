@if($errors->any())
    @foreach($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

@if (session('status'))
    <script>
        toastr.success('{{ session('status') }}');
    </script>
@endif
