@extends('layouts.app')

@section('content')
    @php
        $m = "mb-2";
    @endphp
    <div class="container">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('message') }}
            </p>
        @endif
        <form id="transfer_car" action="{{route('transfer_car', $lng)}}" method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($transfer))
            <input name="id" hidden value="{{$transfer->id}}">
            @endif
            <div class="row row-eq-height justify-content-center transfer_info">

                <div class="col-sm-6 mb-4">
                    <div class="card p-3">

                        <h4 class="p-1 m-1">{{__('Πληροφορίες μετακίνησης')}}</h4>
                        <hr class="p-1 mb-2"/>


                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">ID:</label>
                            </div>
                            <div class="transfer_info_option">
                                <input disabled readonly type="text" class="form-control" value="#@if(isset($transfer)){{$transfer->id}}@endif">
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title">
                                <label class="float-right mr-3"
                                       for="transferType"><strong>*&nbsp;</strong>{{__('Τύπος Μετακίνησης')}}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <select name="transferType" id="transferType" class="form-control" required>
                                    @foreach(App\TransitionType::all() as $tft)
                                        <option value="{{$tft->id}}" @if(isset($transfer) && $transfer->type_id==$tft->id){{'selected'}}@endif>{{ $tft->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php $driver_employee = false; @endphp
                        @if(isset($transfer) && $transfer->driver->role == 'employee'))
                            @php $driver_employee = true; @endphp
                        @endif

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Οδηγός') }}:</label>
                            </div>

                            <div class="transfer_info_option">
                                <div class="form-check-inline">
                                    <label class="form-check-label" for="driver_employee">
                                        <input required type="radio" class="form-check-input select-driver-type"
                                               id="driver_employee"
                                               name="driver"
                                               value="employee" @if($driver_employee) checked @endif>{{ __('Υπάλληλος') }}
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label" for="driver_external">
                                        <input required type="radio" class="form-check-input select-driver-type"
                                               id="driver_external"
                                               name="driver"
                                               value="external" @if(!$driver_employee){{'checked'}}@endif> {{__('Συνεργάτης')}}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Όνομα Οδηγού') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <div id="driver_employee_list" @if(!$driver_employee) hidden @endif>
                                    <div class="d-flex">
                                        @driverSelector([
                                            'name' => 'driver_employee_id',
                                            'drivers' => isset($transfer) ? [$transfer->driver] : [],
                                            'addBtn' => false,
                                            'query_fields' => ['role' => 'employee']
                                        ])
                                        @enddriverSelector
                                    </div>
                                </div>
                                <div id="driver_id_list" @if($driver_employee) hidden @endif>
                                    <div class="d-flex">
                                        @driverSelector([
                                            'name' => 'driver_id',
                                            'drivers' => isset($transfer) ? [$transfer->driver] : [],
                                            'addBtn' => true
                                        ])
                                        @enddriverSelector
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Αυτοκίνητο') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <div id="vehicle_id_list">
                                    @vehicleSelector([
                                        'id' => 'transfer_vehicle',
                                        'name' => 'vehicle_id',
                                        'vehicles' => isset($transfer) ? [$transfer->vehicle] : []
                                    ])
                                    @endvehicleSelector
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mb-4">
                    <div class="card p-3">

                        <h4 class="p-1 m-1">{{__('Γενικές Πληροφορίες')}}</h4>
                        <hr class="p-1 mb-2"/>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Κατάσταση') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <input type="text" class="form-control text-right" disabled
                                       value="@if(isset($transfer)) {{$transfer->vehicle->status}}@elseif(isset($car)) {{$car->status}} @endif">
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Απόσταση') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                @if(isset($transfer) && !is_null($transfer->ci_km))
                                    <div class="input-group">
                                        <input type="number" class="form-control text-right" disabled
                                               value="{{ $transfer->ci_km - $transfer->co_km  }}">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> km</div>
                                        </div>
                                    </div>
                                @else
                                    <input type="text" class="form-control" disabled
                                           value="">
                                @endif
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Αρχεία') }}:</label>
                            </div>
                            <div class="transfer_info_option text-center">
                                @if(isset($transfer))
                                    @foreach($transfer->documents as $file)
                                        <a href="{{url('storage')}}/{{$file->path}}" target="_blank" class='btn btn-sm btn-success mb-1'>
                                            {{ __('Αρχείο') }} #{{$file->id}}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-6 mb-4">
                    <div class="card p-3">

                        <h4 class="p-1 m-1">{{__('Πληροφορίες Αναχώρησης')}}</h4>
                        <hr class="p-1 mb-2"/>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                    :</label>
                            </div>
                            <div class="transfer_info_option">
                                @datetimepicker([
                                    'id' => 'transfer_co_datetime',
                                    'name' => 'transfer_co_datetime',
                                    'datetime' => isset($transfer) ? $transfer->co_datetime : now(),
                                    'required' => true
                                ])
                                @enddatetimepicker
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                @stationSelector([
                                    'id' => 'transfer_co_station',
                                    'name' => 'transfer_co_station',
                                    'stations' => isset($transfer) ? [$transfer->co_station] : []
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
                                    'name' => 'transfer_co_place',
                                    'option' => isset($transfer) && isset($transfer->co_place) ? $transfer->co_place : null,
                                    'text' => isset($transfer) && $transfer->co_place_text ? $transfer->co_place_text : null,
                                    'addBtn' => true
                                ])
                                @endplacesSelector
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Χιλιόμετρα') }}:</label>
                            </div>
                            <div class="transfer_info_option">

                                <div class="input-group">
                                    <input required id="transfer_co_km" name="transfer_co_km" type="number" class="form-control"
                                           value="@if(isset($transfer)){{ $transfer->co_km }}@elseif(isset($car)){{ $car->km }}@endif">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">km</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Στάθμη καυσίμου') }}
                                    :</label>
                            </div>
                            <div class="transfer_info_option">
                                <div class="input-group">
                                    <input required id="transfer_co_fuel" name="transfer_co_fuel" type="number"
                                           class="form-control"
                                           value="@if(isset($transfer)){{ $transfer->co_fuel_level }}@elseif(isset($car)){{ $car->fuel_level }}@endif">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">/ 8</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Χρήστης') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <select class="selectpicker form-control" id="transfer_co_user_id"
                                        name="transfer_co_user_id" readonly>
                                    @if(isset($transfer))
                                        <option value="{{  $transfer->co_user->id }}">{{  $transfer->co_user->name }}</option>
                                    @else
                                        <option value="{{  Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Σημειώσεις') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <textarea name="transfer_co_notes" id="transfer_co_notes"
                                          class="form-control">@if(isset($transfer)){{$transfer->co_notes}}@endif</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-6 mb-4">
                    <div class="card p-3">

                        <h4 class="p-1 m-1">{{__('Πληροφορίες Άφιξης')}}</h4>
                        <hr class="p-1 mb-2"/>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                    :</label>
                            </div>
                            <div class="transfer_info_option">
                                @datetimepicker([
                                    'id' => 'transfer_ci_datetime',
                                    'name' => 'transfer_ci_datetime',
                                    'datetime' => isset($transfer) ? $transfer->ci_datetime : now(),
                                    'required' => true
                                ])
                                @enddatetimepicker
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                @stationSelector([
                                    'name' => 'transfer_ci_station',
                                    'stations' => isset($transfer) && $transfer->ci_station ? [$transfer->ci_station] : []
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
                                    'name' => 'transfer_ci_place',
                                    'option' => isset($transfer) && isset($transfer->ci_place) ? $transfer->ci_place : null,
                                    'text' => isset($transfer) && $transfer->ci_place_text ? $transfer->ci_place_text : null,
                                    'addBtn' => true
                                ])
                                @endplacesSelector
                            </div>
                        </div>

                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Χιλιόμετρα') }}:</label>
                            </div>
                            <div class="transfer_info_option">

                                <div class="input-group">
                                    <input id="transfer_ci_km" name="transfer_ci_km" type="number" class="form-control"
                                           value="@if(isset($transfer)){{$transfer->ci_km}}@endif">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">km</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Στάθμη καυσίμου') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <div class="input-group">
                                    <input id="transfer_ci_fuel" name="transfer_ci_fuel" type="number"
                                           class="form-control"
                                           value="@if(isset($transfer)){{$transfer->ci_fuel_level}}@endif">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">/ 8</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Χρήστης') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <select disabled class="selectpicker form-control" id="transfer_ci_user_id"
                                        name="transfer_ci_user_id" >
                                    @if(isset($transfer))
                                        <option value="{{ $transfer->co_user->id }}">{{  $transfer->co_user->name }}</option>
                                    @else
                                        <option disabled>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-inline {{$m}}">
                            <div class="transfer_info_title text-right">
                                <label class="float-right mr-3">{{ __('Σημειώσεις') }}:</label>
                            </div>
                            <div class="transfer_info_option">
                                <textarea name="transfer_ci_notes" id="transfer_ci_notes"
                                          class="form-control">@if(isset($transfer)){{$transfer->ci_notes}}@endif</textarea>
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
                        <h4 class="p-1 m-1">{{__('Σημειώσεις μετακίνησης')}}</h4>
                        <hr class="p-1 mb-2"/>
                        <textarea id="transfer_notes" name="transfer_notes" class="form-control">@if(isset($transfer)){{$transfer->notes}}@endif</textarea>
                    </div>
                </div>

                <div class="col-sm-12 mb-4">
                    <div class="card p-5">
                        <input class="btn btn-success" type="submit" value="Μετακίνηση">
                    </div>
                </div>

            </div>
        </form>
    </div>

    @if (!isset($transfer) || ($transfer->checkedIn() && $transfer->canAffectVehicle()))
        @push('modals')
            @affectsVehiclePopup(['form' => '#transfer_car'])
            @endaffectsVehiclePopup
        @endpush
    @else
        @push('modals')
            @higherKmConfirmation(['form' => '#transfer_car', 'vehicleKm' => $transfer->car->km, 'inputName' => 'transfer_ci_km'])
            @endhigherKmConfirmation
        @endpush
    @endif

    <script>
        var getVehicleData = "{{route('get_vehicle_data_ajax', $lng)}}";
        transfer_vehicle.on('selectr.select', function() {
            car = transfer_vehicle.getValue();
            if (car) {
                $.post(getVehicleData, {
                    car: car
                }, function (vehicle) {
                    $('#transfer_co_km').val(vehicle.km);
                    $('#transfer_co_fuel').val(vehicle.fuel);
                    if(vehicle.station_id) {
                        transfer_co_station.setValue(vehicle.station_id);
                    }
                    // $('#transfer_co_station').val(vehicle.station_id).change();
                });
            }
        })
    </script>
@endsection
