@extends('layouts.app')
@section('content')
    <script>
        var search_route = "{{route('search_for_company', $lng)}}";
        var addCompany_route = "{{ route('driver_add_company', $lng) }}";
        var deleteCompany_route = "{{ route('driver_delete_company', $lng) }}";
        var driver = "{{ $driver->id ?? '' }}";
    </script>
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
                    @include('drivers.form')
                </div>
            </div>
        </div>
    </div>
@endsection
