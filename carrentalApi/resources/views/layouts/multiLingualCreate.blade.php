@extends ('layouts.app')

@section ('content')
    @yield('scripts')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('message') }}
                    </p>
                @endif
                <div class="card">
                    @include('layouts.multiLingualForm')
                </div>
            </div>
        </div>
    </div>
@endsection
