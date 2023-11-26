@extends ('layouts.tablePreview', [
'route' => route('cars', $lng ?? 'el'),
'data' => $cars,
'addButtonText' => 'Οχήματος',
'export_fields' => [
    'licence_plate' => 'Πινακίδα',
    'whole_model' => 'Μοντέλο',
    'km' => 'Χιλιόμετρα',
    'type.international_title' => 'Group',
    'station.profile_title' => 'Σταθμός',
    'place_text' => 'Τοποθεσία',
    'insurance.date_expiration' => 'Λήξη Ασφάλειας',
    'kteo.date_expiration' => 'Λήξη Κτέο',
    'vin' => 'VIN',
    '' => 'Next Service',
    'vehicle_status.profile_title' => 'Κατάσταση Οχήματος'
]
])

@section ('additional_scripts')
<script>
    var chooseLocation = "{{ route('choose_location', $lng) }}";
    var transferCars = "{{ route('transfer_cars', $lng) }}";
    var displayMaintenances = "{{ route('display_maintenances', $lng) }}";
    var updateMaintenances = "{{ route('update_maintenances', $lng) }}";

</script>
@endsection

@section ('title')
    {{ __('Λίστα Οχημάτων') }}
@endsection


@section ('additional_buttons')
    @if(Auth::user()->role->id != "service")
        <button class="btn btn-secondary" data-toggle="modal" data-target="#transferModal">
            {{ __('Μεταφορά') }}
        </button>
    @endif
@endsection

@section('filters')
    <div class="d-flex align-items-center">
        @php
            $statuses = Request::has('vehicle_status') && !is_null(Request::get('vehicle_status')) ? Request::get('vehicle_status') : [];
        @endphp
        <strong>Group</strong>

        @php
            $types = [];
            if (Request::has('type_id') && Request::get('type_id')) {
                $types = \App\Type::whereIn('id', Request::get('type_id'))->get();
            }
        @endphp
        @groupSelector([
            'id' => 'group_id',
            'name' => 'type_id',
            'groups' => $types,
            'multiple' => true
        ])
        @endgroupSelector
    </div>
    <div class="d-flex align-items-center">
        @php
            $station = Request::get('station_id');
        @endphp
        <strong>Σταθμός</strong>

        @stationSelector([
            'name' => 'station_id',
            'stations' => $station ? [\App\Station::find($station)] : [],
            'without_default' => true
        ])
        @endstationSelector
    </div>
    <div class="d-flex align-items-center">
        @php
            $place = Request::get('place_text');
        @endphp
        <strong>Τοποθεσία</strong>

        @placesSelector([
            'id' => 'place',
            'name' => 'place',
            'option' => null,
            'text' => $place,
            'depends' => ['stations' => 'station_id'],
            'without_default' => true
        ])
        @endplacesSelector
    </div>
    <div>
        <div class="d-flex align-items-center">
            <strong>Διαθεσιμότητα</strong>
        </div>
        <div class="d-flex align-items-center">
            @php
                $datetime = Request::has('availability') && !is_null(Request::get('availability')) ? urldecode(Request::get('availability')) : now();
            @endphp
            @datetimepicker([
                'id' => 'availability',
                'name' => 'availability',
                'datetime' => $datetime
            ])
            @enddatetimepicker
        </div>
    </div>
    <div class="d-flex align-items-center">
        @php
            $status = Request::get('status');
        @endphp
        <strong>Κατάσταση Διαθεσιμότητας</strong>
        <select id="status" name="status">
            <option value="">Όλα</option>
            <option value="active" @if($status=='active') selected @endif>Διαθέσιμα</option>
            <option value="rental" @if($status=='rental') selected @endif>Rental</option>
        </select>
    </div>
    <div class="d-flex align-items-center">
        @php
            $statuses = Request::has('status_id') && !is_null(Request::get('status_id')) ? Request::get('status_id') : [];
        @endphp
        <strong>Κατάσταση</strong>
        <select multiple name="status_id[]" id="vehicle_statuses">
            @foreach (\App\Status::get() as $status)
                <option @if(in_array($status->id, $statuses)) selected @endif value="{{ $status->id }}">{{ $status->profile_title }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        @php
            $ownerships = Request::has('ownership_type_id') && !is_null(Request::get('ownership_type_id')) ? Request::get('ownership_type_id') : [];
        @endphp
        <strong>Ιδιοκτησία</strong>
        <select name="ownership_type_id[]" id="ownership" multiple>
            @foreach(App\OwnershipTypes::all() as $type)
                <option @if(in_array($type->id, $ownerships)) selected @endif value="{{$type->id}}">@if($lng !='el' && $type->international_title!=null) {{$type->international_title}} @else {{$type->title}} @endif</option>
            @endforeach
        </select>
    </div>
@endsection

@push('scripts')
<script>
    new Selectr('#vehicle_statuses');
    new Selectr('#ownership');
    new Selectr('#status', {
        allowDeselect: true,
        renderSelection: deselectSelectr
    });
</script>
@endpush

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th {{ set_th_data('licence_plate') }} >{{ __('Πινακίδα') }}</th>
    <th>{{ __('Μοντέλο') }}</th>
    <th>{{ __('Χιλιόμετρα') }}</th>
    <th>{{ __('Fuel') }}</th>
    <th {{ set_th_data('type.category.current_profile.title') }}>{{ __('Group') }}</th>
    <th>{{ __('Σταθμός') }}</th>
    <th>{{ __('Τοποθεσία') }}</th>
    <th>{{ __('Λήξη Ασφάλειας') }}</th>
    <th>{{ __('Λήξη ΚΤΕΟ') }}</th>
    <th>{{ __('Next Service') }}</th>
    <th>{{ __('Διαθεσιμότητα') }}
    <th>{{ __('Κατάσταση Οχήματος') }}
    </th>
    <th class="text-right">
        {{ __('Ενέργειες') }}
    </th>
</tr>
@endsection
@section ('tbody')
@foreach ($cars as $index =>$car)
    <tr id="index_{{ $car->id }}">
        <td class="text-center">
            <input type="checkbox" class="data_checkbox form-check-inline" data-id="{{ $car->id }}" />
        </td>
        <td>{{ ( ( $cars->perPage() * $cars->currentPage() )- $cars->perPage() )+$index+1 }}</td>
        <td class="license">
            @php
                $many = '';
                if (count($car->license_plates) > 1) {
                $many = 'many';
                }
            @endphp
            <ul class="{{ $many }} plates">
                @foreach ($car->license_plates as $plate)
                    <li class="plate">{{ $plate->licence_plate }}</li>
                @endforeach
            </ul>
        </td>
        <td class="text-nowrap">
            {{ $car->make." ".$car->model }}
            <span class="car-color @if(!($car->color_type->hex_code ?? '')) no-color @endif" style="--color-car:{{ $car->color_type->hex_code ?? '' }}"></span>
        </td>
        <td>
            {{ $car->km }}
        </td>
        <td>
            {{ $car->fuel_level }} / 8
        </td>
        <td class="text-nowrap">
            @if(!is_null($car->type))
                @if(!is_null($car->type->category))
                    @if($car->type->category->profile_title)
                        {{ $car->type->international_title }}
                    @endif
                @endif
            @endif
        </td>
        <td>{{$car->station->profile_title ?? ''}}</td>
        <td>{{$car->place->profile_title ?? $car->place_text ?? ''}}</td>
        <td>
            {{ formatDate($car->insurance->date_expiration ?? '') }}
        </td>
        <td>
            {{ formatDate($car->kteo->date_expiration ?? '') }}
        </td>

        <td></td>

        @php
            $date = now()->toDate();
            if (Request::has('availability') && !is_null(Request::get('availability'))) {
                $date = \Carbon\Carbon::parse(Request::get('availability'))->toDate();
            }
        @endphp
        <td><span class="car-status" data-status="{{ $car->get_vehicle_status($date) ?? 'available' }}">{{ $car->get_vehicle_status($date) ?? __('Διαθέσιμο') }}</span>
            {{-- <td><span class="car-status" data-status="{{ $car->vehicle_status->getProfileByLanguageId($lng ?? 'el')->title }}">{{ $car->vehicle_status->getProfileByLanguageId($lng ?? 'el')->title ?? __('Διαθέσιμο') }}</span> --}}
        </td>
        @if ($car->vehicle_status)
            <td><span class="car-status" data-status="{{ $car->vehicle_status->getProfileByLanguageId($lng ?? 'el')->title }}">{{ $car->vehicle_status->getProfileByLanguageId($lng ?? 'el')->title ?? __('Διαθέσιμο') }}</span>
        @else
            <td><span class="car-status" data-status="{{ 'available' }}">{{ __('Ενεργό') }}</span>
        @endif
        {{-- <td><span class="car-status" data-status="{{ $car->status ?? 'available' }}">{{ $car->status ?? __('Διαθέσιμο') }}</span>
        </td> --}}
        <td class="actions">
            <div class="inner">
                @if(Auth::user()->role->id == "service")
                    <a href="{{ route('view_car', ['locale' => $lng ?? 'el', 'id' => $car->id]) }}" class="btn btn-sm btn-success text-white">
                        {{__('Προβολή')}}
                    </a>
                @endif
                <a href="{{route('create_visit_view', $lng)}}?car={{$car->id}}" class="btn btn-sm btn-info text-white">
                    {{__('Service')}}
                </a>
                @if(Auth::user()->role->id != "service")
                <a href="{{route('qrcode_genaerate', ['locale'=>$lng, 'id'=>$car->id])}}" class="btn btn-sm btn-info" target="_blank">
                    <i class="fas fa-qrcode"></i>
                </a>
                <button class="btn btn-sm btn-primary maintenancesVehicle" data-toggle="modal"
                    data-target="#maintenanceModal" data-id="{{ $car->id }}">
                    <i class="fas fa-wrench"></i>
                </button>
                <a href="{{ route('cars',$lng ?? 'el') }}/edit?cat_id={{ $car->id }}&duplicate=1"
                    class="duplicate-btn edit_car btn btn-sm btn-light">
                    <i class="far fa-clone"></i>
                </a>
                @include ('template-parts.actions', [
                    'route' => route('cars', $lng ?? 'el'),
                    'data' => $car
                ])
                @endif
            </div>
        </td>
    </tr>
@endforeach

@section ('modals')
@include ('template-parts.maintenanceModal')
@include ('template-parts.transferModal')
@endsection

@endsection
