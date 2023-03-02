<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ config('app.name') }}</a></li>
                    @isset($items)
                        @foreach($items as $item)
                            <li class="breadcrumb-item"><a href="{{ $item['href'] }}">{{ $item['name'] }}</a></li>
                        @endforeach
                    @endisset
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
