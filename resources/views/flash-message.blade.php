@foreach (['danger', 'warning', 'success', 'info'] as $key)
    @if(Session::has($key))
        <div class="alert alert-{{ $key }} alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get($key) }}
        </div>
    @endif
@endforeach
