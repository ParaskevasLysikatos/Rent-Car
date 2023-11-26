@php
    $url = $route.'/create';
    $url = str_replace(env('APP_URL'), '', $url);

    $route_instance = collect(\Route::getRoutes())->first(function($route_instance) use($url){
        return $route_instance->matches(request()->create($url));
    });

    // $middlewares = $route_instance->middleware();
    $middlewares = [];
    if ($route_instance) {
        $middlewares = $route_instance->middleware();
    }

    $can = true;
    foreach ($middlewares as $middleware) {
        if (strpos($middleware, 'permissions') !== false) {
            $role = Auth::user()->role_id;
            $allowed = explode(':', $middleware)[1] ?? 'root';
            if ($role != $allowed && $role != 'administrator' && $role != 'root') {
                $can = false;
            }
        }
    }
@endphp

@if ($can)
    <a href="{{ $route.'/edit' }}?cat_id={{ $data->id }}"
        class="edit-btn edit_car btn btn-sm btn-secondary text-white">
        <i class="fas fa-pen"></i>
    </a>
@else
    <a href="{{ $route.'/edit' }}?cat_id={{ $data->id }}&view=1"
        class="edit-btn edit_car btn btn-sm btn-secondary text-white">
        <i class="fas fa-eye"></i>
    </a>
@endif
@if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
<span class="delete-btn delete_single btn btn-sm btn-danger"
    data-id="{{ $data->id }}">
    <i class="fas fa-trash-alt"></i>
</span>
@endif
