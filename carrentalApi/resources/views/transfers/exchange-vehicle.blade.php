@extends('layouts.app')

@php
    $old_vehicle = isset($exchange) && $exchange->old_vehicle_id ? $exchange->old_vehicle : $rental->vehicle;
@endphp

@section('title')
    Αντικατάσταση Οχήματος - {{ $old_vehicle->licence_plate }}
@endsection

@section('content')
    @php
        $m = "mb-2";
    @endphp
    <div class="container">
        <h1>@yield('title')</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('message') }}
            </p>
        @endif
        <ul class="nav nav-tabs booking-navigation" id="myTab" role="tablist">
            <li class="nav-item @if(isset($exchange) && $exchange->type == \App\VehicleExchange::TYPE_OUTSIDE) d-none @endif">
                <a class="nav-link active" data-toggle="tab" href="#exchange-vehicle-inside"
                role="tab" aria-controls="basic_car_info"
                aria-selected="true">{{__('Αντικατάσταση στο γραφείο')}}</a>
            </li>
            <li class="nav-item @if(isset($exchange) && $exchange->type == \App\VehicleExchange::TYPE_OFFICE) d-none @endif">
                <a class="nav-link @if(isset($exchange) && $exchange->type == \App\VehicleExchange::TYPE_OUTSIDE) active @endif" data-toggle="tab" href="#exchange-vehicle-outside">{{__('Αντικατάσταση σε εξωτερικό χώρο')}}</a>
            </li>
        </ul>

        <div class="tab-content">

            <form class="tab-pane fade show active @if(isset($exchange) && $exchange->type == \App\VehicleExchange::TYPE_OUTSIDE) d-none @endif" id="exchange-vehicle-inside" action="{{route('exchange_car', ['locale' => $lng, 'rental_id' => $rental->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ isset($exchange) ? $exchange->id : '' }}" />
                <input hidden name="affectsVehicle" value="1" />
                <input hidden name="type" value="{{ \App\VehicleExchange::TYPE_OFFICE }}" />
                <div >

                </div>

                <div class="row row-eq-height justify-content-center transfer_info">

                    <div class="col-sm-12 mb-4">
                        <div class="card">
                            <div class="row m-0">
                                <div class="col-sm-12">
                                    <h4 class="p-1 m-1">{{__('Αντικατάσταση')}}</h4>
                                    <hr class="p-1 mb-2"/>
                                </div>
                                <div class="col-sm-12 m-0">
                                    <div class="form-inline {{$m}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3">{{ __('Μίσθωση') }}:</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            <a href="{{ route('create_rental_view', ['locale' => $lng, 'cat_id' => $rental->id]) }}">{{ $rental->sequence_number }}</a>
                                        </div>
                                    </div>
                                    <div class="form-inline {{$m}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                                :</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            @datetimepicker([
                                                'id' => 'datetime',
                                                'name' => 'datetime',
                                                'datetime' => isset($exchange) ? $exchange->datetime : now(),
                                                'required' => true
                                            ])
                                            @enddatetimepicker
                                        </div>
                                    </div>
                                    <div class="form-inline {{$m}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3">{{ __('Σταθμός') }}:</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            @stationSelector([
                                                'id' => 'station_id',
                                                'name' => 'station_id',
                                                'stations' => isset($exchange) ? [$exchange->station] : [$rental->checkout_station],
                                                'required' => true
                                            ])
                                            @endstationSelector
                                        </div>
                                    </div>
                                    <div class="form-inline {{$m}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3">{{ __('Τοποθεσία') }}:</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            @placesSelector([
                                                'name' => 'place',
                                                'option' => isset($exchange) && $exchange->place_id ? $exchange->place
                                                    : (isset($rental->checkout_place_id) ? $rental->checkout_place : null),
                                                'text' => isset($exchange) ? $exchange->place_text : $rental->checkout_place_text
                                            ])
                                            @endplacesSelector
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 row m-0">

                                    <div class="col-sm-6 card p-3">
                                        <h5 class="text-center">{{ __('Παράδοση Νέου Οχήματος') }}</h5>
                                        <hr />
                                        <div class="input-group {{ $m }}">
                                            <div class="transfer_info_title">
                                                <label class="float-right mr-3"
                                                    for="new_vehicle_type_id"><strong>&nbsp;</strong>{{ __('Group') }}
                                                    :</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                @groupSelector([
                                                    'id' => 'new_vehicle_type_id_inside',
                                                    'name' => 'new_vehicle_type_id',
                                                    'groups' => isset($exchange) && $exchange->new_vehicle_type ? [$exchange->new_vehicle_type] : []
                                                ])
                                                @endgroupSelector
                                            </div>
                                        </div>

                                        <div class="input-group {{$m}}">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3"><strong>&nbsp;</strong>{{ __('Πινακίδα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option input-group">
                                                <div id="vehicle_id_list w-50">
                                                    @vehicleSelector([
                                                        'id' => 'new_vehicle_id_inside',
                                                        'name' => 'new_vehicle_id',
                                                        'extra_fields' => ['km', 'fuel_level'],
                                                        'depends' => ['group' => 'new_vehicle_type_id_inside'],
                                                        'vehicles' => isset($exchange) && $exchange->new_vehicle ? [$exchange->new_vehicle] : [],
                                                        'query_fields' => [
                                                            'from' => $rental->checkout_datetime,
                                                            'to' => $rental->checkin_datetime
                                                        ],
                                                        'extra_fields' => [
                                                            'model',
                                                            'make',
                                                            'km',
                                                            'fuel_level'
                                                        ],
                                                        'required' => true
                                                    ])
                                                    @endvehicleSelector
                                                </div>
                                                <input id="vehicle-model_inside" class="form-control" readonly
                                                    @if(isset($exchange) && $exchange->new_vehicle)value="{{ $exchange->new_vehicle->whole_model }}"@endif />
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">

                                                <div class="input-group">
                                                    <input id="new_ci_km" name="new_ci_km" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->new_vehicle_rental_co_km }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">km</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input id="new_ci_fuel_level" name="new_ci_fuel_level" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->new_vehicle_rental_co_fuel_level }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">/ 8</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}}">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3"><strong>&nbsp;</strong>{{ __('Υπάλληλος') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div id="driver_id_list">
                                                    <div class="d-flex">
                                                        @driverSelector([
                                                            'name' => 'driver_id',
                                                            'drivers' => [Auth::user()->driver],
                                                            'query_fields' => ['role' => 'employee']
                                                        ])
                                                        @enddriverSelector
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 card p-3">
                                        <h5 class="text-center">{{ __('Παραλαβή Τρέχοντος Οχήματος') }}</h5>
                                        <hr />
                                        <div class="input-group {{$m}}">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Πινακίδα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option input-group">
                                                <input  class="form-control" readonly value="{{ $old_vehicle->licence_plate }}">
                                                <input class="form-control" readonly value="{{ $old_vehicle->make.' '.$old_vehicle->model }}" />
                                                <input name="replaced_vehicle_id" value="{{ $old_vehicle->id }}" hidden />
                                            </div>
                                        </div>

                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Check-out Km/Fuel') }}:</label>
                                            </div>
                                            <div class="transfer_info_option d-flex ">
                                                <input disabled class="form-control w-100" value="@if(isset($exchange)){{ $exchange->old_vehicle_rental_co_km }}@else{{ $rental->checkout_km }}@endif">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">km</div>
                                                </div>
                                                <input disabled class="form-control w-100"
                                                    value="@if(isset($exchange)){{ $exchange->old_vehicle_rental_co_fuel_level }}@else{{ $rental->checkout_fuel_level }}@endif">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">/ 8</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input name="replaced_km" class="form-control" @if(isset($exchange))value="{{ $exchange->old_vehicle_rental_ci_km }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">km</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input name="replaced_fuel_level" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->old_vehicle_rental_ci_fuel_level }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">/ 8</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-3">
                            <h4 class="p-1 m-1">{{__('Επισυναπτόμενα έγγραφα')}}</h4>
                            <hr class="p-1 mb-2"/>

                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" id="files" name="files[]" multiple>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-3">
                            <h4 class="p-1 m-1">{{__('Σημειώσεις αντικατάστασης')}}</h4>
                            <hr class="p-1 mb-2"/>
                            <textarea id="transfer_notes" name="transfer_notes" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-5">
                            <input class="btn btn-success" type="submit" value="Δημιουργία Αντικατάστασης">
                        </div>
                    </div>

                </div>
            </form>

            <form class="tab-pane fade @if(isset($exchange) && $exchange->type == \App\VehicleExchange::TYPE_OUTSIDE) show active @endif" id="exchange-vehicle-outside" action="{{route('exchange_car', ['locale' => $lng, 'rental_id' => $rental->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ isset($exchange) ? $exchange->id : '' }}" />
                <input hidden name="affectsVehicle" value="1" />
                <input hidden name="type" value="{{ \App\VehicleExchange::TYPE_OUTSIDE }}" />
                <div >

                </div>

                <div class="row row-eq-height justify-content-center transfer_info">
                    <div class="col-sm-6 mb-4">
                        <div class="card p-3">

                            <h4 class="p-1 m-1">1. {{__('Ραντεβού')}}</h4>
                            <hr class="p-1 mb-2"/>

                            <div class="form-inline {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3">{{ __('Μίσθωση') }}:</label>
                                </div>
                                <div class="transfer_info_option">
                                    <a href="{{ route('create_rental_view', ['locale' => $lng, 'cat_id' => $rental->id]) }}">{{ $rental->sequence_number }}</a>
                                </div>
                            </div>

                            <div class="form-inline {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @datetimepicker([
                                        'id' => 'proximate_datetime',
                                        'name' => 'proximate_datetime',
                                        'datetime' => isset($exchange) ? $exchange->proximate_datetime : null,
                                        'required' => true
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="form-inline {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3">{{ __('Τοποθεσία') }}:</label>
                                </div>
                                <div class="transfer_info_option">
                                    @placesSelector([
                                        'name' => 'place',
                                        'option' => isset($exchange) && $exchange->place_id ? $exchange->place
                                            : (isset($rental->checkout_place_id) ? $rental->checkout_place : null),
                                        'text' => isset($exchange) ? $exchange->place_text : $rental->checkout_place_text,
                                        'addBtn' => true
                                    ])
                                    @endplacesSelector
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-4">
                        <div class="card p-3">

                            <h4 class="p-1 m-1">2. {{__('Αναχώρηση Νέου Οχήματος')}}</h4>
                            <hr class="p-1 mb-2"/>

                            <div class="input-group {{ $m }}">
                                <div class="transfer_info_title">
                                    <label class="float-right mr-3"
                                        for="new_vehicle_type_id"><strong>&nbsp;</strong>{{ __('Group') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @groupSelector([
                                        'id' => 'new_vehicle_type_id',
                                        'name' => 'new_vehicle_type_id',
                                        'groups' => isset($exchange) && $exchange->new_vehicle_type ? [$exchange->new_vehicle_type] : []
                                    ])
                                    @endgroupSelector
                                </div>
                            </div>

                            <div class="input-group {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3"><strong>&nbsp;</strong>{{ __('Πινακίδα') }}:</label>
                                </div>
                                <div class="transfer_info_option input-group">
                                    <div id="vehicle_id_list w-50">
                                        @vehicleSelector([
                                            'id' => 'new_vehicle_id',
                                            'name' => 'new_vehicle_id',
                                            'extra_fields' => ['km', 'fuel_level'],
                                            'depends' => ['group' => 'new_vehicle_type_id'],
                                            'vehicles' => isset($exchange) && $exchange->new_vehicle ? [$exchange->new_vehicle] : [],
                                            'query_fields' => [
                                                'from' => $rental->checkout_datetime,
                                                'to' => $rental->checkin_datetime
                                            ],
                                            'extra_fields' => [
                                                'model',
                                                'make'
                                            ]
                                        ])
                                        @endvehicleSelector
                                    </div>
                                    <input id="vehicle-model" class="form-control" readonly
                                        @if(isset($exchange) && $exchange->new_vehicle)value="{{ $exchange->new_vehicle->whole_model }}"@endif />
                                </div>
                            </div>

                            <div class="form-inline {{$m}} flex-nowrap">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                                </div>
                                <div class="transfer_info_option">

                                    <div class="input-group">
                                        <input id="new_co_km" name="new_co_km" class="form-control"
                                            @if(isset($exchange) && $exchange->new_vehicle_transition)value="{{ $exchange->new_vehicle_transition->co_km }}"@endif>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">km</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-inline {{$m}} flex-nowrap">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                                </div>
                                <div class="transfer_info_option">
                                    <div class="input-group">
                                        <input id="new_co_fuel_level" name="new_co_fuel_level" class="form-control"
                                            @if(isset($exchange) && $exchange->new_vehicle_transition)value="{{ $exchange->new_vehicle_transition->co_fuel_level }}"@endif>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">/ 8</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-inline {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3"><strong>&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @datetimepicker([
                                        'id' => 'departure_datetime',
                                        'name' => 'departure_datetime',
                                        'datetime' => isset($exchange) && $exchange->new_vehicle_transition ?
                                            $exchange->new_vehicle_transition->co_datetime : '',
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="form-inline {{$m}}">
                                <div class="transfer_info_title text-right">
                                    <label class="float-right mr-3"><strong>&nbsp;</strong>{{ __('Υπάλληλος') }}:</label>
                                </div>
                                <div class="transfer_info_option">
                                    <div id="driver_id_list">
                                        <div class="d-flex">
                                            @driverSelector([
                                                'name' => 'driver_id',
                                                'drivers' => [Auth::user()->driver],
                                                'query_fields' => ['role' => 'employee']
                                            ])
                                            @enddriverSelector
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card">
                            <div class="row m-0">
                                <div class="col-sm-12">
                                    <h4 class="p-1 m-1">3. {{__('Αντικατάσταση')}}</h4>
                                    <hr class="p-1 mb-2"/>
                                </div>
                                <div class="col-sm-12 row m-0">
                                    <div class="form-inline {{$m}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                                :</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            @datetimepicker([
                                                'id' => 'datetime',
                                                'name' => 'datetime',
                                                'datetime' => isset($exchange) ? $exchange->datetime : null
                                            ])
                                            @enddatetimepicker
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 row m-0">
                                    <div class="col-sm-6 card p-3">
                                        <h5 class="text-center">{{ __('Παράδοση Νέου Οχήματος') }}</h5>
                                        <hr />
                                        <div class="input-group {{$m}}">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Πινακίδα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option input-group">
                                                <input id="new_vehicle_licence" class="form-control" readonly @if(isset($exchange) && $exchange->new_vehicle) value="{{ $exchange->new_vehicle->licence_plate }}" @endif>
                                                <input id="new_vehicle_model" class="form-control" readonly @if(isset($exchange) && $exchange->new_vehicle) value="{{ $exchange->new_vehicle->make.' '.$exchange->new_vehicle->model }}" @endif />
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">

                                                <div class="input-group">
                                                    <input id="new_ci_km" name="new_ci_km" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->new_vehicle_km }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">km</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input id="new_ci_fuel_level" name="new_ci_fuel_level" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->new_vehicle_fuel_level }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">/ 8</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 card p-3">
                                        <h5 class="text-center">{{ __('Παραλαβή Τρέχοντος Οχήματος') }}</h5>
                                        <hr />
                                        <div class="input-group {{$m}}">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Πινακίδα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option input-group">
                                                <input  class="form-control" readonly value="{{ $rental->vehicle->licence_plate }}">
                                                <input class="form-control" readonly value="{{ $rental->vehicle->make.' '.$rental->vehicle->model }}" />
                                                <input name="replaced_vehicle_id" value="{{ $rental->vehicle_id }}" hidden />
                                            </div>
                                        </div>

                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input name="replaced_km" class="form-control" @if(isset($exchange))value="{{ $exchange->old_vehicle_km }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">km</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-inline {{$m}} flex-nowrap">
                                            <div class="transfer_info_title text-right">
                                                <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                                            </div>
                                            <div class="transfer_info_option">
                                                <div class="input-group">
                                                    <input name="replaced_fuel_level" class="form-control"
                                                        @if(isset($exchange))value="{{ $exchange->old_vehicle_fuel_level }}"@endif>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">/ 8</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-3">
                            <h4 class="p-1 m-1">{{__('Επισυναπτόμενα έγγραφα')}}</h4>
                            <hr class="p-1 mb-2"/>

                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" id="files" name="files[]" multiple>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-3">
                            <h4 class="p-1 m-1">{{__('Σημειώσεις αντικατάστασης')}}</h4>
                            <hr class="p-1 mb-2"/>
                            <textarea id="transfer_notes" name="transfer_notes" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-4">
                        <div class="card p-5">
                            <input class="btn btn-success" type="submit" value="Δημιουργία Αντικατάστασης">
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- @if (!isset($transfer) || ($transfer->checkedIn() && $transfer->canAffectVehicle()))
        @push('modals')
            @affectsVehiclePopup(['form' => '#transfer_car'])
            @endaffectsVehiclePopup
        @endpush
    @else
        @push('modals')
            @higherKmConfirmation(['form' => '#transfer_car', 'vehicleKm' => $transfer->car->km, 'inputName' => 'transfer_ci_km'])
            @endhigherKmConfirmation
        @endpush
    @endif --}}

    <script>
        const model_inside = $('#vehicle-model_inside');
        const new_vehicle_licence_inside = $('#new_vehicle_licence_inside');
        const new_vehicle_model_inside = $('#new_vehicle_model_inside');

        const model = $('#vehicle-model');
        const new_vehicle_licence = $('#new_vehicle_licence');
        const new_vehicle_model = $('#new_vehicle_model');

        new_vehicle_id_inside.on('selectr.select', function (option) {
            option = $(option);
            const model_val = option.data('make') + ' ' + option.data('model');

            model_inside.val(model_val);
            new_vehicle_model_inside.val(model_val);
            new_vehicle_licence_inside.val(option.text());

            $('#new_ci_km').val(option.data('km'));
            $('#new_ci_fuel_level').val(option.data('fuel_level'));
        });

        new_vehicle_id_inside.on('selectr.deselect', function(option) {
            model_inside.val('');
            new_vehicle_model_inside.val('');
            new_vehicle_licence_inside.val('');
        });

        new_vehicle_id.on('selectr.select', function (option) {
            option = $(option);
            const model_val = option.data('make') + ' ' + option.data('model');

            model.val(model_val);
            new_vehicle_model.val(model_val);
            new_vehicle_licence.val(option.text());
        });

        new_vehicle_id.on('selectr.deselect', function(option) {
            model.val('');
            new_vehicle_model.val('');
            new_vehicle_licence.val('');
        });
    </script>
@endsection
