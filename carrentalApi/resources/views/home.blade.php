@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(Auth::user()->role->id == "service")
                <div class="col-4">
                    <a href="{{ route('cars', $lng ?? 'el') }}">
                        <div class="mobile_app mb-4 bg-white shadow p-3 rounded d-flex justify-content-center align-items-center">
                            <h3 class="fa fa-car text-success text-center"> <strong class="d-none d-sm-block">{{ __('Λίστα Οχημάτων') }}</strong></h3>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('visits', $lng ?? 'el') }}">
                        <div class="mobile_app mb-4 bg-white shadow p-3 rounded d-flex justify-content-center align-items-center">
                            <h3 class="fa fa-wrench text-success text-center"> <strong class="d-none d-sm-block">{{ __('Επισκέψεις') }}</strong></h3>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{route('scanner', $lng?? 'el')}}">
                        <div class="mobile_app mb-4 bg-white shadow p-3 rounded d-flex justify-content-center align-items-center">
                            <h3 class="fas fa-qrcode text-success text-center"> <strong class="d-none d-sm-block">QR Code Scanner</strong></h3>
                        </div>
                    </a>
                </div>
            @else
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    {{ Session::get('message') }}
                                </p>
                            @endif
                            <div class="card p-3">
                                <h3>{{ __('Απαραίτητα Δεδομένα') }}</h3>
                                <ul>
                                    <li><a href="{{ route('create_brand_view', ['locale' => $lng]) }}">{{ __('Μία Πηγή') }}</a> <span class="fas @if ($brand) fa-check text-success @else fa-times text-danger @endif"></span></li>
                                    <li><a href="{{ route('create_location_view', ['locale' => $lng]) }}">{{ __('Μία Περιοχή') }}</a> <span class="fas @if ($location) fa-check text-success @else fa-times text-danger @endif"></span></li>
                                    <li><a href="{{ route('create_station_view', ['locale' => $lng]) }}">{{ __('Ένας Σταθμός') }}</a> <span class="fas @if ($station) fa-check text-success @else fa-times text-danger @endif"></span></li>
                                    <li><a href="{{ route('create_place_view', ['locale' => $lng]) }}">{{ __('Μία Τοποθεσία') }}</a> <span class="fas @if ($place) fa-check text-success @else fa-times text-danger @endif"></span></li>
                                    <li><a href="{{ route('company_preferences', ['locale' => $lng]) }}">{{ __('Η Εταιρεία μου') }}</a> <span class="fas @if ($company) fa-check text-success @else fa-times text-danger @endif"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
