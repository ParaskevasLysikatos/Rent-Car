@extends ('layouts.app')

@section ('content')
<script>
    var delete_route =
        "{{ $route.'/delete' }}";

</script>
@yield ('additional_scripts')

<div class="row justify-content-center">
    @if (Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session::get('message') }}
        </p>
    @endif
    @yield('top-part')
    <div class="col-12">
        <h3>
            @if (request()->has('title'))
                {{ request()->get('title') }}
            @else
                @yield('title')
            @endif
            <small> - {{$data->total()}} {{'αποτελέσματα'}}</small>
        </h3>
        <div class="panel">
            @if ( Request::get('search') ?? false )
                <div class="row align-items-center">
                    <h4 class="p-3 m-0">
                        {{ __('Αποτελέσματα για:') }}
                        <b>{{ Request::get('search') }}</b></h4>
                    <a class="btn btn-outline-dark" href="{{ Request::url() }}">
                        <span class="fas fa-undo"></span> {{ __('Καθαρισμός Αναζήτησης') }}
                    </a>
                </div>
            @endif
            <div class=" mb-3">
                <form class="bd-highlight" action="{{ $route }}" method="GET">
                    {{-- @csrf --}}
                    @if ($orderBy = Request::get('orderBy'))
                        <input type="hidden" name="orderBy" value="{{ $orderBy }}" />
                    @endif
                    @if ($orderByType = Request::get('orderByType'))
                        <input type="hidden" name="orderByType" value="{{ $orderByType }}" />
                    @endif
                    <div class="d-flex flex-row">
                        <div class="d-flex p-2 bd-highlight">

                            <input class="form-control py-2" type="search"
                                placeholder="{{ __('Αναζήτηση') }}..."
                                id="search" name="search" value="{{ Request::get('search') }}">

                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root' || Auth::user()->role_id == 'editor')
                            @if (($addButtonText ?? false))
                                <div class="p-2 bd-highlight">
                                    <a href="{{ $route.'/create' }}" class="btn btn-primary">
                                        {{ __('Προσθήκη ' . $addButtonText) }}
                                    </a>
                                </div>
                            @endif
                            <div class="p-2 bd-highlight ml-auto">
                                @yield ('additional_buttons')
                                <input id="delete_multiple" type="button" class="btn btn-danger"
                                    value="{{ __('Διαγραφή') }}">
                            </div>
                        @endif
                    </div>
                    <div class="filters flex-wrap d-sm-block d-md-block d-lg-flex">
                        @yield('filters')
                        @if(View::hasSection('extra_filters'))
                            <div><button type="button" class="btn btn-primary extra-filters-btn"><i class="fas fa-filter"></i></button></div>
                        @endif
                        @php
                            $extra_filters_show = false;
                            if (isset($extra_filters)) {
                                foreach ($extra_filters as $extra_filter) {
                                    if (Request::has($extra_filter) && !is_null(Request::get($extra_filter))) {
                                        $extra_filters_show = true;
                                    }
                                }
                            }
                        @endphp
                        <div class="extra_filters @if($extra_filters_show) show @endif">
                            <button type="button" class="btn bg-danger close text-light rounded-circle py-1 px-2"><i class="fas fa-times"></i></button>
                            <h4>Επιπλέον Φίλτρα</h4>
                            @yield('extra_filters')
                        </div>
                        <div class="export_fields">
                            <button type="button" class="btn bg-danger close text-light rounded-circle py-1 px-2"><i class="fas fa-times"></i></button>
                            <h4>Εξαγωγή δεδομένων</h4>
                            @if(isset($export_fields))
                                @foreach ($export_fields as $field => $value)
                                    <div>
                                        <input checked type="checkbox" name="export-field[{{ $field }}][enabled]" id="export-field-{{ $field }}" />
                                        <input type="hidden" name="export-field[{{ $field }}][text]" value="{{ $value }}" id="export-field-{{ $field }}" />
                                        <label for="export-field-{{ $field }}">{{ $value }}</label>
                                    </div>
                                @endforeach
                            @endif
                            @yield('export_fields')
                            <input class="btn btn-primary" type="submit" name="export" value="{{ 'Εξαγωγή' }}" />
                        </div>
                    </div>
                    <div class="p-2 bd-highlight">
                        @if(View::hasSection('filters'))
                            <input class="btn btn-primary" type="submit" value="{{ 'Φιλτράρισμα' }}" />
                        @endif
                        @if(isset($export_fields))
                            <input class="btn btn-secondary export-fields-btn" type="button" value="{{ 'Εξαγωγή σε excel' }}" />
                        @endif
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-body card-table">
                    @if (!empty($data) && count($data)>0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped listing-table">
                                <thead>
                                    @yield ('thead')
                                </thead>
                                <tbody>
                                    @yield ('tbody')
                                </tbody>
                            </table>
                        </div>
                        @yield('content-footer')
                        @php
                            $pagination = $data->appends(Request::query());
                        @endphp
                        @include ('template-parts.pagination')
                    @else
                        <div class="alert alert-warning">
                            <p>{{ __('Δεν βρέθηκαν αποτελέσματα!') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@yield ('modals')

@endsection
