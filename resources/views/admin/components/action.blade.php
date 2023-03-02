@isset($show)
    <a class="btn btn-primary btn-sm" href="{{ route($route . '.show', $show) }}" target="_blank">
        <i class="far fa-eye"></i>
    </a>
@endisset
@isset($can)
    @can($can . '_edit')
        <a class="btn btn-warning btn-sm" href="{{ route('admin.' . $route . '.edit', $id) }}">
            <i class="fas fa-pencil-alt"></i>
        </a>
    @endcan
    @can($can . '_delete')
        <form class="d-inline" action="{{ route('admin.'  . $route . '.destroy', $id) }}" onsubmit="return confirm('Вы действительно хотите удалить?');" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endcan
@endisset
