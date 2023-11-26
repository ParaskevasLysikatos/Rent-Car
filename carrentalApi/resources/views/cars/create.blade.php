@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_car', $lng ?? 'el'),
    'formCancel' => route('cars', $lng ?? 'el'),
    'formSubmit' => (isset($car))? __('Ενημέρωση') : __('Προσθήκη')
])

@php
    $duplicate = isset($duplicate) ? $duplicate : false;
@endphp

@section('scripts')
<script>
    //Delete plate path
    var deletePlate = "{{route('delete_plate', $lng)}}";
    //Create-Update plate
    var createPlate = "{{ route('create_plate', $lng) }}";
    //Set Car ID
    var carID = "{{ $car->id ?? '' }}";
    //Delete image path
    var deleteImage = "{{ route('delete_car_image', $lng) }}";
    var updateFee = "{{route('update_fee', $lng)}}";
    var deleteFee = "{{ route('delete_fee', $lng) }}";
</script>
@endsection

@section('title')
    {!! (isset($_GET['cat_id']))?__('Επεξεργασία') .' - '.$car->licence_plate: __('Προσθήκη') !!}
@endsection

@section('card-header')
    @if(isset($car))
        <label class="p-1 text-white bg-warning float-right"> {{ __('Διαθεσιμότητα') }}:
        <strong>{{ $car->status ?? __('Διαθέσιμο') }}</strong></label>
    @endif
@endsection

@section('additional-tabs')
    <li class="nav-item" @if(!isset($car)){{'hidden'}}@endif>
        <a class="nav-link" id="licence-tab" data-toggle="tab" href="#license_plates_info"
        role="tab" aria-controls="license_plates_info"
        aria-selected="false">{{__('Πινακίδες')}}</a>
    </li>
    <li class="nav-item" @if(!isset($car)){{'hidden'}}@endif>
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#periodic_fee_info"
        role="tab" aria-controls="periodic_fee_info"
        aria-selected="false">{{__('Περιοδικά τέλη')}}</a>
    </li>
@endsection

@section('main-fields')
    <div class="current-lang col-12">
        @php $currentLang = \App\Language::find($lng); @endphp
        <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Τίτλος')}}</span>
            </div>
            @if(isset($car))
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                        name="title[{{$currentLang->id}}]"
                        value="{{ $car->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
            @else
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                        name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
            @endif

        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Περιγραφή')}}</span>
            </div>
            @if(isset($car))
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                            name="description[{{$currentLang->id}}]">{{ $car->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
            @else
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                            name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
            @endif
        </div>

        <hr/>
    </div>

    <div class="information ">
        @if(isset($car) && !$duplicate)
            <input type="hidden" name="id" value="{{$car->id}}">
            <div class="input-group mb-3">
                <input disabled id="first_licence_plate_edit"
                    name="first_licence_plate_edit" type="text" class="form-control"
                    placeholder=""
                    value="@if(!is_null($car->getPlate())){{$car->getPlate()->licence_plate}}@endif">
                <div class="input-group-append">
                    <input disabled id="first_licence_plate_date_edit"
                        name="first_licence_plate_date_edit" type="text"
                        class="form-control"
                        value="@if(!is_null($car->getPlate())){{ formatDate($car->getPlate()->registration_date) }}@endif">
                </div>
                <div class="input-group-append">
                    <label class="form-control fa fa-pencil-alt btn btn-warning"
                        id="redirect_edit_licence_plate"></label>
                </div>
            </div>
        @else
            <label for="first_licence_plate">* {{__('Πινακίδα')}}</label>
            <div class="input-group mb-3">
                <input id="first_licence_plate" name="first_licence_plate" type="text"
                    class="form-control" placeholder=""
                    value="@if(old('first_licence_plate')){{old('first_licence_plate')}}@endif" required>
                <div class="input-group-append">
                    <input id="first_licence_plate_date" name="first_licence_plate_date"
                        type="text" class="datepicker form-control"
                        value="@if(old('first_licence_plate_date')){{ formatDate(old('first_licence_plate_date')) }}@else{{ formatDate(now()) }}@endif" required>
                </div>
            </div>
        @endif

        <label for="type_id">* {{__('Τύπος')}}</label>
        <div class="input-group mb-3">
            <select required name="type_id" id="type_id" class="form-control">
                <option selected disabled>{{__('Επιλέξτε')}}...</option>
                @foreach(App\Type::all() as $type)
                    @if( (isset($car) && $car->type_id == $type->id) || (old('type_id') ==  $type->id))
                        )
                        <option selected
                                value="{{$type->id}}">
                            @if(!is_null($type->category))
                                @if(!is_null($type->category->getProfileByLanguageId($lng ?? 'el')))
                                    {{$type->category->getProfileByLanguageId($lng ?? 'el')->title}}
                                @endif
                            @endif
                            - {{$type->getProfileByLanguageId($lng ?? 'el')->title}}</option>
                    @else
                        <option
                            value="{{$type->id}}">
                            @if(!is_null($type->category))
                                @if(!is_null($type->category->getProfileByLanguageId($lng ?? 'el')))
                                    {{$type->category->getProfileByLanguageId($lng ?? 'el')->title}}
                                @endif
                            @endif
                            - {{$type->getProfileByLanguageId($lng ?? 'el')['title']}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <label for="images">{{__('Εικόνες')}}</label>
        <div class="input-group mb-3">
            <input type="file" class="form-control" id="images" name="images[]" multiple>
        </div>
        @if(isset($car) && count($car->images)>0)
            <div class="row">
                @foreach($car->images as $file)
                    @image(['file' => $file, 'image_link_id' => $car->id, 'image_link_type' => get_class($car)])
                    @endimage
                @endforeach
            </div>
        @endif

        <label for="vin">* {{__('Αριθμός Πλαισίου')}} (VIN)</label>
        <div class="input-group mb-3">
            @if(isset($car) && !$duplicate)
                <input required type="text" class="form-control" id="vin" name="vin"
                    value="{{ $car->vin }}">
            @else
                <input required type="text" class="form-control" id="vin" name="vin"
                    value="{{ old('vin') }}">
            @endif
        </div>

        <label for="make">* {{__('Αντιπροσωπία αυτοκινήτου')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input required type="text" class="form-control" id="make" name="make"
                    value="{{ $car->make }}">
            @else
                <input required type="text" class="form-control" id="make" name="make"
                    value="{{ old('make') }}">
            @endif
        </div>

        <label for="model">* {{__('Μοντέλο αυτοκινήτου')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input required type="text" class="form-control" id="model" name="model"
                    value="{{ $car->model }}">
            @else
                <input required type="text" class="form-control" id="model" name="model"
                    value="{{ old('model') }}">
            @endif
        </div>

        <label for="engine">* {{__('Κυβισμός cm3')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input required type="text" class="form-control" id="engine" name="engine"
                    value="{{ $car->engine }}">
            @else
                <input required type="text" class="form-control" id="engine" name="engine"
                    value="{{ old('engine') }}">
            @endif
        </div>

        <label for="power">{{__('Φορολογήσιμη ισχύς')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input type="text" class="form-control" id="power" name="power"
                    value="{{ $car->power }}">
            @else
                <input type="text" class="form-control" id="power" name="power"
                    value="{{ old('power') }}">
            @endif
        </div>

        <label for="hp">{{__('Ίπποι')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input type="text" class="form-control" id="hp" name="hp"
                    value="{{ $car->hp }}">
            @else
                <input type="text" class="form-control" id="hp" name="hp"
                    value="{{ old('hp') }}">
            @endif
        </div>


        <label for="drive_type">{{__('Σύστημα κίνησης τροχών')}}</label>
        <div class="input-group mb-3">
            <select required name="drive_type" id="type_id" class="form-control">
                <option selected disabled>{{__('Επιλέξτε')}}...</option>
                @foreach(App\DriveTypes::all() as $type)
                    @if( (isset($car) && $car->drive_type_id == $type->id) || (old('drive_type') ==  $type->id))
                        )
                        <option selected
                                value="{{$type->id}}">{{$type->title}} @if($type->short_description!=null)
                                ({{$type->short_description}})@endif</option>
                    @else
                        <option
                            value="{{$type->id}}">{{$type->title}} @if($type->short_description!=null)
                                ({{$type->short_description}})@endif</option>
                    @endif
                @endforeach
            </select>
        </div>

        <label for="transmission">* {{__('Σασμάν')}}</label>
        <div class="input-group mb-3">
            <select required name="transmission" id="transmission" class="form-control">
                <option selected disabled>{{__('Επιλέξτε')}}...</option>
                @foreach(App\TransmissionTypes::all() as $type)
                    @if( (isset($car) && $car->transmission_type_id == $type->id) || (old('transmission') ==  $type->id))
                        )
                        <option selected
                                value="{{$type->id}}">{{$type->title}} @if($type->international_title !=null)
                                ({{$type->international_title}})@endif</option>
                    @else
                        <option
                            value="{{$type->id}}">{{$type->title}} @if($type->international_title!=null)
                                ({{$type->international_title}})@endif</option>
                    @endif
                @endforeach
            </select>
        </div>

        @if(!isset($car) || $duplicate)
            <label for="station_id">* {{__('Σταθμός')}}</label>
            <div class="input-group mb-3">
                @stationSelector([
                    'id' => 'station_id',
                    'name' => 'station_id',
                    'stations' => []
                ])
                @endstationSelector
            </div>

            <label>* {{__('Χιλιόμετρα')}}</label>
            <div class="input-group mb-3">
                <input type="number" name="km" class="form-control" value="{{ old('km') }}"/>
            </div>

            <label>* {{__('Στάθμη καυσίμου')}}</label>
            <div class="input-group mb-3">
                <input type="number" name="fuel_level" class="form-control" min="0" max="8" value="{{ old('fuel_level') ?? 8 }}"/>
            </div>
        @endif

        <label for="station_id">* {{__('Κατάσταση οχήματος')}}</label>
        <div class="input-group mb-3">
            <select name="status_id" id="status_id" class="form-control">
                @foreach(App\Status::all() as $status)
                    <option
                        value="{{$status->id}}" @if(isset($car) && $car->status_id == $status->id) selected @endif>{{ $status->getProfileByLanguageId( $lng ?? 'el' )->title ?? 'Άγνωστο' }}</option>
                @endforeach
            </select>
        </div>

        <label>{{__('Κλειδιά')}}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" for="key_code">{{__('Κωδικός')}}:</span>
            </div>
            <input type="text" id="key_code" name="key_code" class="form-control"
                value="@if(isset($car)){{$car->key_code}}@else{{old('key_code')}}@endif">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" for="">{{__('Ποσότητα')}}:</span>
            </div>
            <input type="text" id="keys_quantity" name="keys_quantity" class="form-control"
                value="@if(isset($car)){{$car->keys_quantity}}@else{{old('keys_quantity')}}@endif">
        </div>

        <label for="doors">{{__('Doors')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input type="text" class="form-control" id="doors" name="doors"
                    value="{{ $car->doors }}">
            @else
                <input type="text" class="form-control" id="doors" name="doors"
                    value="{{ old('doors') }}">
            @endif
        </div>

        <label for="seats">{{__('Seats')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input type="text" class="form-control" id="seats" name="seats"
                    value="{{ $car->seats }}">
            @else
                <input type="text" class="form-control" id="seats" name="seats"
                    value="{{ old('seats') }}">
            @endif
        </div>

        <label for="euroclass">{{__('Euroclass')}}</label>
        <div class="input-group mb-3">
            @if(isset($car))
                <input type="text" class="form-control" id="euroclass" name="euroclass"
                    value="{{ $car->euroclass }}">
            @else
                <input type="text" class="form-control" id="euroclass" name="euroclass"
                    value="{{ old('euroclass') }}">
            @endif
        </div>

    </div>

    <div class="additional-info" id="additional-info">

        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapseOne">
                    {{ __('Περισσότερες πληροφορίες') }}
                </a>
            </div>
            <div id="collapseOne" class="collapse" data-parent="#additional-info">
                <div class="card-body">
                    {{-- Start Card--}}

                    <label for="fuel_type">* {{__('Τύπος καυσίμου')}}</label>
                    <div class="input-group mb-3">
                        <select required name="fuel_type" id="fuel_type"
                                class="form-control">
                            <option selected disabled>{{__('Επιλέξτε')}}...</option>
                            @foreach(App\FuelTypes::all() as $type)
                                @if( (isset($car) && $car->fuel_type_id == $type->id) || (old('fuel_type') ==  $type->id))
                                    )
                                    <option selected
                                            value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @else
                                    <option
                                        value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- <label for="international_code">* {{__('Διεθνής κωδικός')}}
                        (ACRISS)</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input required type="text" class="form-control"
                                id="international_code" name="international_code"
                                value="{{ $car->international_code }}">
                        @else
                            <input required type="text" class="form-control"
                                id="international_code" name="international_code"
                                value="{{ old('international_code') }}">
                        @endif
                        <button type="button" class="btn btn-primary"
                                data-toggle="modal" data-target="#acress_modal">
                            {{__('Δημιουργία')}}
                        </button>
                    </div> --}}
                    <label for="color_title">{{__('Χρώμα')}}</label>
                    <div class="input-group mb-3">
                        <select name="color_title" id="color_title"
                                class="form-control">

                        </select>
                    </div>
                    <label
                        for="warranty_expiration">{{__('Ημερομηνία λήξης της εγγύησης αυτοκινήτου')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control"
                                id="warranty_expiration" name="warranty_expiration"
                                value="{{ formatDate($car->warranty_expiration) }}">
                        @else
                            <input type="text" class="datepicker form-control"
                                id="warranty_expiration" name="warranty_expiration"
                                value="{{ formatDate(old('warranty_expiration')) }}">
                        @endif
                    </div>
                    <label
                        for="engine_number">{{__('Αριθμός μηχανής/κινητήρα')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="form-control" id="engine_number"
                                name="engine_number"
                                value="{{ $car->engine_number }}">
                        @else
                            <input type="text" class="form-control" id="engine_number"
                                name="engine_number"
                                value="{{ old('engine_number') }}">
                        @endif
                    </div>
                    <label for="tank">* {{__('Ντεπόζιτο')}} (lt)</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input required type="text" class="form-control" id="tank"
                                name="tank"
                                value="{{ $car->tank }}">
                        @else
                            <input required type="text" class="form-control" id="tank"
                                name="tank"
                                value="{{ old('tank') }}">
                        @endif
                    </div>
                    <label for="pollution">{{__('Ρύποι')}} CO2</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="form-control" id="pollution"
                                name="pollution"
                                value="{{ $car->pollution }}">
                        @else
                            <input type="text" class="form-control" id="pollution"
                                name="pollution"
                                value="{{ old('pollution') }}">
                        @endif
                    </div>
                    <label for="manufactured_year">{{__('Έτος κατασκευής')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input min="1970" max="9999" type="number"
                                class="form-control" id="manufactured_year"
                                name="manufactured_year"
                                value="{{ $car->manufactured_year }}">
                        @else
                            <input min="1970" max="9999" type="number"
                                class="form-control" id="manufactured_year"
                                name="manufactured_year"
                                value="{{ old('manufactured_year') }}">
                        @endif
                    </div>
                    <label for="radio_code">{{__('Radio Code')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="form-control" id="radio_code"
                                name="radio_code"
                                value="{{ $car->radio_code }}">
                        @else
                            <input type="text" class="form-control" id="radio_code"
                                name="radio_code"
                                value="{{ old('radio_code') }}">
                        @endif
                    </div>
                    <label for="purchase_date">{{__('Ημερομηνία αγοράς')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control" id="purchase_date"
                                name="purchase_date"
                                value="{{ formatDate($car->purchase_date) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="purchase_date"
                                name="purchase_date"
                                value="{{ formatDate(old('purchase_date')) }}">
                        @endif
                    </div>
                    <label for="purchase_amount">{{__('Ποσό αγοράς')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="number" step="0.01" class="form-control"
                                id="purchase_amount" name="purchase_amount"
                                value="{{ $car->purchase_amount }}">
                        @else
                            <input type="number" step="0.01" class="form-control"
                                id="purchase_amount" name="purchase_amount"
                                value="{{ old('purchase_amount') }}">
                        @endif
                    </div>

                    <label for="depreciation_rate">{{__('Ποσό Αποσβέσεων')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="number" step="0.01" class="form-control"
                                id="depreciation_rate" name="depreciation_rate"
                                value="{{ $car->depreciation_rate }}">
                        @else
                            <input type="number" step="0.01" class="form-control"
                                id="depreciation_rate" name="depreciation_rate"
                                value="{{ old('depreciation_rate') }}">
                        @endif
                    </div>

                    <label
                        for="depreciation_rate_year">{{__('Ποσό Αποσβέσεων Μείωση/Έτος')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="number" step="0.01" class="form-control"
                                id="depreciation_rate_year"
                                name="depreciation_rate_year"
                                value="{{ $car->depreciation_rate_year }}">
                        @else
                            <input type="number" step="0.01" class="form-control"
                                id="depreciation_rate_year"
                                name="depreciation_rate_year"
                                value="{{ old('depreciation_rate_year') }}">
                        @endif
                    </div>

                    <label for="sale_amount">{{__('Ποσό Πώλησης')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="number" step="0.01" class="form-control"
                                id="sale_amount" name="sale_amount"
                                value="{{ $car->sale_amount }}">
                        @else
                            <input type="number" step="0.01" class="form-control"
                                id="sale_amount" name="sale_amount"
                                value="{{ old('sale_amount') }}">
                        @endif
                    </div>

                    <label for="sale_date">{{__('Ημερομηνία Πώλησης')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control" id="sale_date"
                                name="sale_date"
                                value="{{ formatDate($car->sale_date) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="sale_date"
                                name="sale_date"
                                value="{{ formatDate(old('sale_date')) }}">
                        @endif
                    </div>

                    <label
                        for="start_stop">{{__('Σύστημα λειτουργίας Start-Stop (μπαταρία)')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="checkbox" class="form-control" id="start_stop"
                                name="start_stop" @if(!is_null($car->start_stop)){{"checked"}}@endif>
                        @else
                            <input type="checkbox" class="form-control" id="start_stop"
                                name="start_stop" @if(!is_null(old('start_stop'))){{"checked"}}@endif>
                        @endif
                    </div>

                    <label for="buy_back">{{__('Ημερομηνία Buy Back')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control" id="buy_back"
                                name="buy_back"
                                value="{{ formatDate($car->buy_back) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="buy_back"
                                name="buy_back"
                                value="{{ formatDate(old('buy_back')) }}">
                        @endif
                    </div>
                    <label
                        for="first_date_marketing_authorisation">{{__('Ημερομηνία έκδοσης της πρώτης άδειας κυκλοφορίας')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control"
                                id="first_date_marketing_authorisation"
                                name="first_date_marketing_authorisation"
                                value="{{ formatDate($car->first_date_marketing_authorisation) }}">
                        @else
                            <input type="text" class="datepicker form-control"
                                id="first_date_marketing_authorisation"
                                name="first_date_marketing_authorisation"
                                value="{{ formatDate(old('first_date_marketing_authorisation')) }}">
                        @endif
                    </div>
                    <label
                        for="first_date_marketing_authorisation_gr">{{__('Ημερομηνία πρώτης αδείας στην Ελλάδα')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control"
                                id="first_date_marketing_authorisation_gr"
                                name="first_date_marketing_authorisation_gr"
                                value="{{ formatDate($car->first_date_marketing_authorisation_gr) }}">
                        @else
                            <input type="text" class="datepicker form-control"
                                id="first_date_marketing_authorisation_gr"
                                name="first_date_marketing_authorisation_gr"
                                value="{{ formatDate(old('first_date_marketing_authorisation_gr')) }}">
                        @endif
                    </div>
                    <label
                        for="import_to_system">* {{__('Ημερομηνία εισαγωγής στον στόλο')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input required type="text" class="datepicker form-control"
                                id="import_to_system" name="import_to_system"
                                value="{{ formatDate($car->import_to_system) }}">
                        @else
                            <input required type="text" class="datepicker form-control"
                                id="import_to_system" name="import_to_system"
                                value="{{ formatDate(old('import_to_system')) }}">
                        @endif
                    </div>
                    <label
                        for="export_from_system">{{__('Ημερομηνία εξαγωγής από τον στόλο')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control"
                                id="export_from_system" name="export_from_system"
                                value="{{ formatDate($car->export_from_system) }}">
                        @else
                            <input type="text" class="datepicker form-control"
                                id="export_from_system" name="export_from_system"
                                value="{{ formatDate(old('export_from_system')) }}">
                        @endif
                    </div>
                    <label
                        for="forecast_export_from_system">{{__('Ημερομηνία εξαγωγής από τον στόλο (πρόβλεψη)')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($car))
                            <input type="text" class="datepicker form-control"
                                id="forecast_export_from_system"
                                name="forecast_export_from_system"
                                value="{{ formatDate($car->forecast_export_from_system) }}">
                        @else
                            <input type="text" class="datepicker form-control"
                                id="forecast_export_from_system"
                                name="forecast_export_from_system"
                                value="{{ formatDate(old('forecast_export_from_system')) }}">
                        @endif
                    </div>
                    <label for="ownership">* {{__('Ιδιοκτησία')}}</label>
                    <div class="input-group mb-3">
                        <select required name="ownership" id="ownership"
                                class="form-control">
                            <option selected disabled>{{__('Επιλέξτε')}}...</option>
                            @foreach(App\OwnershipTypes::all() as $type)
                                @if( (isset($car) && $car->ownership_type_id == $type->id) || (old('ownership') ==  $type->id))
                                    )
                                    <option selected
                                            value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @else
                                    <option
                                        value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <label for="use">* {{__('Χρήση')}}</label>
                    <div class="input-group mb-3">
                        <select required name="use" id="use" class="form-control">
                            <option selected disabled>{{__('Επιλέξτε')}}...</option>
                            @foreach(App\UseTypes::all() as $type)
                                {{$type}}
                                @if( (isset($car) && $car->use_type_id == $type->id) || (old('use') ==  $type->id))
                                    )
                                    <option selected
                                            value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @else
                                    <option
                                        value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <label for="class">* {{__('Κλάση - Ομάδα')}}</label>
                    <div class="input-group mb-3">
                        <select required name="class" id="class" class="form-control">
                            <option selected disabled>{{__('Επιλέξτε')}}...</option>
                            @foreach(App\ClassTypes::all() as $type)
                                @if( (isset($car) && $car->class_type_id == $type->id) || (old('class') ==  $type->id))
                                    )
                                    <option selected
                                            value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @else
                                    <option
                                        value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{--End Card--}}
                </div>
            </div>
        </div>

    </div>

    @documents([
        'model' => isset($car) && !$duplicate ? $car : null,
        'title' => 'Λοιπά Έγγραφα'
    ])
    @enddocuments
@endsection

@section('multilingual-fields')
    @foreach($lang as $lg)
        @if ($lg->id != $lng)
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($car))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                            name="title[{{$lg->id}}]"
                            value="{{ $car->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                            name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($car))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                name="description[{{$lg->id}}]">{{ $car->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@endsection

@section('additional-tabs-content')
    <div class="tab-pane fade" id="license_plates_info" role="tabpanel"
            aria-labelledby="license_plates_info-tab">
        <div class="alert alert-warning mt-1 mb-1">
            <p>{{__('Η πινακίδα με την πιο πρόσφατη ημερομηνία θα είναι η ενεργή πινακίδα')}}</p>
        </div>
        <div class="license_plates mt-1 mb-1">
            @if(isset($car) && !$duplicate && $car->license_plates)
                @foreach($car->license_plates as $plate)
                    <div class="input-group mb-3 single_plate" id="plate{{$loop->index}}"
                            data-id="{{ $plate->id }}">
                        <div class="input-group-prepend form-control">
                            <p class="plate_number">{{$plate->licence_plate}}</p>
                        </div>
                        <div class="input-group-prepend form-control">
                            <p name="plate_date">
                                {{ formatDate($plate->registration_date) }}</p>
                        </div>
                        @if(Auth::user()->role->id != "service")
                            <div class="input-group-prepend edit-group">
                                <button type="button" class="btn btn-secondary" data-plate='@php echo json_encode($plate); @endphp' data-toggle="modal"
                                    data-target="#edit_plate" @if(isset($car))data-car="{{ $car->id }}"@endif
                                    data-documents = '
                                        @foreach($plate->documents as $file)
                                            @document(['file' => $file, 'document_link_id' => $plate->id, 'document_link_type' => get_class($plate) ])
                                            @enddocument
                                        @endforeach
                                    '>
                                    <span class="fas fa-edit"></span>
                                </button>
                            </div>
                        @endif
                        @if(Auth::user()->role->id != "service")
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger delete_plate" data-id="{{ $plate->id }}"
                                    data-index="{{$loop->index}}">
                                    <span class="fas fa-trash"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
        @if(Auth::user()->role->id != "service")
            <input id="create_plate_field" class="btn btn-default" data-toggle="modal"
                data-target="#edit_plate" @if(isset($car))data-car="{{ $car->id }}"@endif value="{{__('Προσθήκη')}}"
                    type="button">
        @endif
    </div>
    <div class="tab-pane fade " id="periodic_fee_info" role="tabpanel"
            aria-labelledby="basic_car_info-tab">
        <div>
            @if(Auth::user()->role->id != "service")
                <a class="btn btn-info btn-sm form-control mt-2" data-toggle="collapse"
                    href="#collapseCreate" role="button" aria-expanded="false"
                    aria-controls="collapseCreate">
                    {{__('Προσθήκη')}}
                </a>
            @endif
            <div class="collapse" id="collapseCreate">
                <div class="card card-body">
                    <form action="{{route('create_fee', $lng)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="hidden" hidden value="{{$car->id ?? ''}}"
                                name="car_id" id="car_id">
                        <div class="form-group">
                            <label for="type">{{__('Είδος')}}</label>
                            <select class="form-control" id="type" name="type">
                                @foreach($fee_types as $fee)
                                    <option value="{{$fee->id}}">{{$fee->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">{{__('Προμηθευτής')}}</label>
                            <input required type="text" class="form-control" id="title"
                                    name="title"/>
                        </div>
                        <div class="form-group">
                            <label for="description">{{__('Περιγραφή')}}</label>
                            <textarea class="form-control" id="description"
                                        name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fee">{{__('Κόστος')}}</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">€</span>
                                </div>
                                <input required type="number" class="form-control" id="fee"
                                        name="fee" step="0.01"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date_start">{{__('Ημερομηνία έναρξης')}}</label>
                            <input required type="text" class="datepicker form-control" id="date_start"
                                    name="date_start"/>
                        </div>
                        <div class="form-group">
                            <label for="date_expiration">{{__('Ημερομηνία λήξης')}}</label>
                            <input required type="text" class="datepicker form-control"
                                    id="date_expiration"
                                    name="date_expiration"/>
                        </div>
                        <div class="form-group">
                            <label for="date_payed">{{__('Ημερομηνία Είσπραξης')}}</label>
                            <input type="text" class="datepicker form-control" id="date_payed"
                                    name="date_payed"/>
                        </div>
                        <div class="form-group">
                            <label for="files" class="mr-3">{{ _('Επισυναπτόμενα έγγραφα') }}: </label>
                            <input type="file" class="form-control" id="files" name="files[]" multiple>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" id="fee_submit"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-3">
            @if(isset($car) && !$duplicate && $car->fees)

                @php
                    $current_category = null;
                    $exist_categories = [];
                @endphp

                <ul class="nav nav-tabs" role="tablist">
                    @foreach($car->fees as $fee)
                        @if($current_category!=$fee->periodic_fee_type_id)
                            <li class="nav-item">
                                <a class="nav-link @if($loop->first){{'active'}}@endif" data-toggle="tab"
                                    href="#tab{{ $fee->periodic_fee_type_id }}">@if(!is_null($fee->fee_type)){{$fee->fee_type->title}}@endif</a>
                            </li>
                            @php
                                $current_category = $fee->periodic_fee_type_id;
                                $exist_categories[] = $fee->periodic_fee_type_id;
                            @endphp
                        @endif
                    @endforeach
                </ul>

                <div class="tab-content">
                    @php $current_category = null; @endphp
                    @foreach($exist_categories as $unique_category)
                        <div id="tab{{ $unique_category }}" class="container tab-pane @if($loop->first){{'active'}}@else{{'fade'}}@endif">
                        @foreach($car->fees->where('periodic_fee_type_id', $unique_category) as $fee)
                            <div class="input-group mb-1 single_fee" id="fee{{$loop->index}}"
                                    data-id="{{ $fee->id }}">
                                <div class="input-group-prepend">
                                    @if(Auth::user()->role->id != "service")
                                        <input type="button" class="btn btn-danger delete_fee"
                                                value="-" data-id="{{ $fee->id }}"
                                                data-index="{{$loop->index}}"/>
                                    @endif
                                </div>

                                <input type="text" class="form-control"
                                        value="{{$fee->title}} / {{ formatDate($fee->date_expiration) }}"
                                        disabled/>
                                <div class="input-group-prepend">
                                    <button class="btn btn-success preview_fee fas fa-eye"
                                            data-id="{{$fee->id}}"
                                            data-type="{{$fee->periodic_fee_type_id}}"
                                            data-title="{{$fee->title}}"
                                            data-description="{{$fee->description}}"
                                            data-fee="{{$fee->fee}}"
                                            data-start="{{ formatDate($fee->date_start) }}"
                                            data-end="{{ formatDate($fee->date_expiration) }}"
                                            data-payed="{{ formatDate($fee->date_payed) }}"
                                            data-documents = '
                                            @foreach($fee->documents as $file)
                                                @document(['file' => $file, 'document_link_id' => $fee->id, 'document_link_type' => get_class($fee) ])
                                                @enddocument
                                            @endforeach
                                            '
                                            data-toggle="modal" data-target="#editFee"
                                    >
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
            <div id="editFee" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('Επεξεργασία')}}</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;
                            </button>

                        </div>
                        <div class="modal-body" id="editFeeBody">
                            <input type="hidden" class="hidden" hidden
                                    value="{{$car->id ?? ''}}"
                                    name="edit_id" id="edit_id">
                            <div class="form-group">
                                <label for="edit_type">{{__('Τύπος')}}</label>
                                <select class="form-control" id="edit_type" name="edit_type">
                                    @foreach($fee_types as $fee)
                                        <option value="{{$fee->id}}">{{$fee->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_title">{{__('Τίτλος')}}</label>
                                <input type="text" class="form-control" id="edit_title"
                                        name="edit_title"/>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">{{__('Περιγραφή')}}</label>
                                <textarea class="form-control" id="edit_description"
                                            name="edit_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_fee">{{__('Κόστος')}}</label>
                                <input type="number" class="form-control" id="edit_fee"
                                        name="edit_fee"/>
                            </div>
                            <div class="form-group">
                                <label
                                    for="edit_date_start">{{__('Ημερομηνία έναρξης')}}</label>
                                <input type="text" class="datepicker form-control" id="edit_date_start"
                                        name="edit_date_start"/>
                            </div>
                            <div class="form-group">
                                <label
                                    for="edit_date_expiration">{{__('Ημερομηνία λήξης')}}</label>
                                <input type="text" class="datepicker form-control"
                                        id="edit_date_expiration"
                                        name="edit_date_expiration"/>
                            </div>
                            <div class="form-group">
                                <label
                                    for="edit_date_payed">{{__('Ημερομηνία Είσπραξης')}}</label>
                                <input type="text" class="datepicker form-control" id="edit_date_payed"
                                        name="edit_date_payed"/>
                            </div>
                            <div class="form-group">
                                <label for="edit_files" class="mr-3">{{ _('Επισυναπτόμενα έγγραφα') }}: </label>
                                <input type="file" class="form-control" id="edit_files" name="edit_files[]" multiple>
                            </div>
                            <fieldset>
                                <div class="form-inline">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3">{{ __('Αρχεία') }}:</label>
                                    </div>
                                    <div id="documents" class="transfer_info_option text-center">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label id="notification"></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Close
                            </button>
                            @if(Auth::user()->role->id != "service")
                                <button type="button" class="btn btn-success" id="update_fee">
                                    {{__('Ενημέρωση')}}
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @modal(['name' => 'edit_plate'])
        <div class="card-header">
            {{ __('Επεξεργασία πινακίδας') }}
        </div>
        <form method="POST" action="{{ route('create_plate', ['locale' => $lng]) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" />
                <input type="hidden" name="car_id" />
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{__('Πινακίδα')}}</span>
                    </div>
                    <input type="text" class="form-control" name="number"/>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{__('Ημερομηνία')}}</span>
                    </div>
                    <input type="text" class="datepicker form-control" name="date"/>
                </div>
                <div class="form-group">
                    <label for="comments" class="mr-3">{{ _('Επισυναπτόμενα έγγραφα') }}: </label>
                    <input type="file" class="form-control" name="files[]" multiple />
                    <div class="docs mt-2">

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                <button type="submit" class="btn btn-success float-right">{{ __('Αποθήκευση') }}</button>
            </div>
        </form>
    @endmodal
@endpush

@push('scripts')
    <script>
        var edit_plate = $('#edit_plate');
        var edit_plate_id = edit_plate.find('input[name="id"]');
        var edit_plate_car_id = edit_plate.find('input[name="car_id"]');
        var edit_plate_number = edit_plate.find('input[name="number"]');
        var edit_plate_docs = edit_plate.find('.docs');
        // var edit_plate_date = edit_plate.find('input[name=""]');
        var datepicker = edit_plate.find('.datepicker');
        edit_plate.on('shown.bs.modal', function(e) {
            const edit_plate_btn = $(e.relatedTarget);
            edit_plate_car_id.val(edit_plate_btn.data('car'));
            if (edit_plate_btn.attr('id') == 'create_plate_field') {
                edit_plate.find('form')[0].reset();
            } else {
                const data = edit_plate_btn.data('plate');
                edit_plate_id.val(data.id);
                edit_plate_number.val(data.licence_plate);
                const date = moment(data.registration_date, defaultFormat).format(momentFormat);
                datepicker.val(date);
                datepicker.trigger('change');
                edit_plate_docs.html(edit_plate_btn.data('documents'));
            }
        });

        var color_title = new Selectr('#color_title', {
            data: [
                {
                    value: '-',
                    text: '{{ __("Επιλέξτε")."..." }}',
                    disabled: true
                }
                @php
                    $selected = null;
                    foreach (App\ColorTypes::all() as $type) {
                        $title = $lng !='el' && $type->international_title!=null ? $type->international_title : $type->title;
                        echo ",{";
                        echo "value: ".$type->id.",";
                        echo "text: \"".$title." <span class='car-color' style='--color-car:". ($type->hex_code ?? '') ."'></span>\"";
                        if((isset($car) && $car->color_type_id == $type->id) || (old('color_title') ==  $type->id)) {
                            $selected = $type->id;
                        }
                        echo "}";
                    }
                @endphp
            ]
        });
        @if ($selected)
            color_title.setValue("{{ $selected }}");
        @endif
    </script>
@endpush
