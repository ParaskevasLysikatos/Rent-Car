@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('message') }}
                    </p>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
                    </div>
                    <form id="create_visit" method="POST" action="{{ route('create_visit', $lng ?? 'el') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @if(isset($visit))
                                @php
                                    $car = $visit->vehicle;
                                @endphp
                                <input type="hidden" name="id" value="{{$visit->id}}">
                            @endif
                                <div class="visit-head text-center">
                                    <div class="d-flex bd-highlight visit_details_panel">

                                        <div class="p-2 flex-fill bd-highlight">
                                            <h4>{{__('Ημερομηνία')}}</h4>
                                            @if(isset($visit))
                                                <input class="datepicker" name="date" type="text" value="{{ formatDate($visit->date_start) }}">
                                            @else
                                                <input class="datepicker" name="date" type="text" value="{{ formatDate(now()) }}">
                                            @endif
                                        </div>

                                        <div class="p-2 flex-fill bd-highlight">
                                            <h4>{{__('Αμάξι')}}</h4>
                                            <p>{{$car->make.' '.$car->model}} - #{{$car->id}}</p>
                                            <input type="hidden" name="car_id" value="{{$car->id}}">
                                        </div>

                                        <div class="p-2 flex-fill bd-highlight">
                                            <h4>{{__('Πινακίδα')}}</h4>
                                            <p>{{$car->getPlate()->licence_plate ?? '-'}}</p>
                                        </div>

                                        <div class="p-2 flex-fill bd-highlight">
                                            <h4>{{__('Στάθμη καυσίμου')}}</h4>
                                            @if(isset($visit))
                                                <input class="text-right" name="fuel_level" min="1" max="8" type="number" value="{{$visit->fuel_level}}" />
                                            @else
                                                @if(old('fuel_level'))
                                                    <input class="text-right" name="fuel_level" min="1" max="8" type="number" value="{{old('fuel_level')}}"  />
                                                @elseif(isset($car) && $car!==null)
                                                    <input class="text-right" name="fuel_level" min="1" max="8" type="number" value="{{$car->fuel_level}}"  />
                                                @else
                                                    <input class="text-right" name="fuel_level" min="1" max="8" type="number"  />
                                                @endif

                                            @endif/8
                                        </div>

                                        <div class="p-2 flex-fill bd-highlight">
                                            <h4>{{__('Χιλιόμετρα')}}</h4>
                                            @if(isset($visit))
                                                <input name="km" type="number" value="{{$visit->km}}" />
                                            @else
                                                @if(old('km'))
                                                    <input name="km" type="number" value="{{old('km')}}"  />
                                                @elseif(isset($car) && $car!==null)
                                                    <input name="km" type="number" value="{{$car->km}}"  />
                                                @else
                                                    <input name="km" type="number"  />
                                                @endif

                                            @endif
                                        </div>

                                    </div>
                                </div>
                            <hr/>
                            <div class="visit-info">
                                @php
                                    $currnet_category = null;
                                @endphp
                                <div class="row">
                                    @foreach($details as $detail)
                                        @if($detail->category != $currnet_category)
                                            @if ($loop->first)
                                                <div class="col-md-6 mb-5">
                                            @elseif($loop->last)
                                                </div>
                                            @else
                                                </div><div class="col-md-6 mb-5">
                                            @endif
                                            @php
                                                $currnet_category = $detail->category;
                                            @endphp
                                            <div class="d-flex bd-highlight title-content">
                                                <div class="p-2 flex-grow-1 bd-highlight"></div>
                                                @foreach($status as $s)
                                                    <div class="p-2 bd-highlight visit-status title">{{__($s->title)}}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="d-flex bd-highlight row-detail">
                                            <div class="p-2 flex-grow-1 bd-highlight visit_reason">{{$detail->title}}</div>
                                            @foreach($status as $s)
                                                <div class="p-2 bd-highlight visit-status">
                                                    <input name="checked[{{$detail->id}}][{{$s->id}}]"
                                                    @if((old('checked')!==null))
                                                        @if((is_array(old('checked'))))
                                                            @if(array_key_exists($detail->id, old('checked')))
                                                                @if(array_key_exists($s->id, old('checked.'.$detail->id)))
                                                                    checked
                                                                @endif
                                                            @endif
                                                         @endif
                                                    @elseif(isset($visit))
                                                        @php
                                                            $exist = $visit->visit_details
                                                                    ->where('service_details_id',$detail->id)
                                                                    ->where('service_status_id', $s->id)
                                                                    ->first();
                                                        @endphp
                                                        @if($exist !== null)
                                                            checked
                                                        @endif
                                                    @endif
                                                        type="checkbox" class="checkbox"/>
                                                </div>
                                            @endforeach
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                            <div class="card col-12">
                                <div class="card-header">
                                    <h3 class="m-0">{{ _('Σημειώσεις') }}</h3>
                                </div>
                                <div class="card-body">
                                    <textarea id="notes" name="comments" class="form-control">@if(isset($visit)){{$visit->comments}}@endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('visits', $lng ?? 'el')}}"
                               class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($visit))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (!isset($visit) || $visit->canAffectVehicle())
        @affectsVehiclePopup(['form' => '#create_visit'])
        @endaffectsVehiclePopup
    @else
        @higherKmConfirmation(['form' => '#create_visit', 'vehicleKm' => $visit->vehicle->km, 'inputName' => 'km'])
        @endhigherKmConfirmation
    @endif
@endsection
