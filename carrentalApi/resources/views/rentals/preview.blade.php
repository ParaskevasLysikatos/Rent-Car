@extends ('layouts.tablePreview', [
'route' => route('rentals',$lng ?? 'el'),
'data' => $rentals,
'addButtonText' => 'Μίσθωσης',
'extra_filters' => [
    'agent_id',
    'source_id',
    'sub_account_id',
    'sub_account_type'
],
'export_fields' => [
    'sequence_number' => 'RNT',
    'vehicle.licence_plate' => 'Πινακίδα',
    'type.category.profile_title' => 'Group',
    'vehicle.whole_model' => 'Μοντέλο',
    'checkout_station.profile_title' => 'Σταθμός Παράδοσης',
    'checkout_place_title' => 'Τοποθεσία Παράδοσης',
    'checkout_datetime' => 'Ημερομηνία Παράδοσης',
    'duration' => 'Ημέρες Ενοικίασης',
    'customer.full_name' => 'Πελάτης',
    'phone' => 'Τηλέφωνο',
    'checkin_station.profile_title' => 'Σταθμός Παραλαβής',
    'checkin_place_title' => 'Τοποθεσία Παραλαβής',
    'total' => 'Τελικό Κόστος'
]
])

@section ('title')
{{ __('Μισθώσεις') }}
@endsection

@section('filters')
    @if(isset($_GET['shortlink']))
        <input type="hidden" name="shortlink" value="{{$_GET['shortlink']}}">
    @endif
    <div class="@if(isset($_GET['shortlink']) && $_GET['shortlink']==2){{'d-none'}}@endif">
        <div class="d-flex align-items-center">
        <strong>Παράδοση</strong>
        <label>Σταθμός: </label>
        @php
            $stations = [];
            if (Request::has('checkout_station_id') && Request::get('checkout_station_id')) {
                $stations = [\App\Station::find(Request::get('checkout_station_id'))];
            }
        @endphp
        @stationSelector([
            'name' => 'checkout_station_id',
            'stations' => $stations,
            'without_default' => true
        ])
        @endstationSelector
        </div>
        <div class="@if(isset($_GET['shortlink']) && $_GET['shortlink']==3){{'d-none'}}@else{{'d-flex'}}@endif align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[from]" id="checkout_datetime_from"
                    value="@if(Request::has('checkout_datetime.from')){{ formatDate(Request::get('checkout_datetime')['from']) }}@endif">
            </div>
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[to]" id="checkout_datetime_to"
                    value="@if(Request::has('checkout_datetime.to')){{ formatDate(Request::get('checkout_datetime')['to']) }}@endif">
            </div>
        </div>
        @if(isset($_GET['shortlink']) && $_GET['shortlink']==3)
            <div class="d-flex align-items-center">
                <label>Ημερομηνία:</label>
                <div>
                    <input type="text" class="datepicker form-control" name="checkout_datetime_single"
                           value="@if(Request::has('checkout_datetime.from')){{ formatDate(Request::get('checkout_datetime')['from']) }}@endif">
                </div>
            </div>
        @endif
    </div>
    <div class="@if(isset($_GET['shortlink']) && $_GET['shortlink']==3){{'d-none'}}@elseif(isset($_GET['shortlink']) && $_GET['shortlink']==2)@else{{''}}@endif">
        <div class="d-flex align-items-center">
            <strong>Παραλαβή</strong>
            <label>Σταθμός: </label>
            @php
                $stations = [];
                if (Request::has('checkin_station_id') && Request::get('checkin_station_id')) {
                    $stations = [\App\Station::find(Request::get('checkin_station_id'))];
                }
            @endphp
            @stationSelector([
                'name' => 'checkin_station_id',
                'stations' => $stations,
                'without_default' => true
            ])
            @endstationSelector
        </div>
        <div class="@if(isset($_GET['shortlink']) && $_GET['shortlink']==2){{'d-none'}}@else{{'d-flex'}}@endif align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkin_datetime[from]" id="checkin_datetime_from"
                    value="@if(Request::has('checkin_datetime.from')){{ formatDate(Request::get('checkin_datetime')['from']) }}@endif">
            </div>
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkin_datetime[to]"  id="checkin_datetime_to"
                    value="@if(Request::has('checkin_datetime.to')){{ formatDate(Request::get('checkin_datetime')['to']) }}@endif">
            </div>
        </div>
        @if(isset($_GET['shortlink']) && $_GET['shortlink']==2)
            <div class="d-flex align-items-center mb-2">
                <label>Ημερομηνία:</label>
                <div>
                    <input type="text" class="datepicker form-control" name="checkin_datetime_single"
                           value="@if(Request::has('checkin_datetime.from')){{ formatDate(Request::get('checkin_datetime')['from']) }}@endif">
                </div>
            </div>
        @endif
    </div>
    <div class="d-flex align-items-center">
        @php
            $statuses = Request::has('status') && !is_null(Request::get('status')) ? Request::get('status') : [];
        @endphp
        <strong>Κατάσταση</strong>
        <select multiple name="status[]" id="statuses">
            @foreach (\App\Rental::getValidStatuses() as $status)
                <option @if(in_array($status, $statuses)) selected @endif value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
    </div>
@endsection

@section('extra_filters')
    <div class="d-flex align-items-center">
        <label>Πηγή</label>
        @php
            $source_id = Request::get('source_id');
        @endphp
        @sourceSelector([
            'id' => 'booking_source',
            'name' => 'source_id',
            'sources' => Request::has('source_id') && !is_null($source_id) && \App\BookingSource::whereIn('id', $source_id)->exists()
                ? \App\BookingSource::whereIn('id', $source_id)->get() : [],
            'multiple' => true
        ])
        @endsourceSelector
    </div>
    <div class="d-flex align-items-center">
        <label>Συνεργάτης</label>
        @php
            $agent_id = Request::get('agent_id');
        @endphp
        @agentSelector([
            'id' => 'agent_id',
            'name' => 'agent_id',
            'agents' => Request::has('agent_id') && !is_null($agent_id) && \App\Agent::whereIn('id', $agent_id)->exists()
                ? \App\Agent::whereIn('id', $agent_id)->get() : [],
            'multiple' => true
        ])
        @endagentSelector
    </div>
    <div class="d-flex align-items-center">
        <label>Πωλητής</label>
        @php
            $sub_account_id = Request::get('sub_account_id');
            $sub_account_type = Request::get('sub_account_type');
            $subaccounts = [];
            if (!is_null($sub_account_id) && !is_null($sub_account_type)) {
                $subaccount = $sub_account_type::find($sub_account_id);
                $subaccount->name = $subaccount->name ?? $subaccount->full_name;
                $subaccount->account_id = $subaccount->id;
                $subaccount->model = $sub_account_type;
                $subaccounts[] = $subaccount;
            }
        @endphp
        @subaccountSelector([
            'id' => 'sub_account_id',
            'name' => 'sub_account_id',
            'sub_accounts' => $subaccounts,
            'searchUrl' => 'searchSubAccountWithAgentUrl'
        ])
        @endsubaccountSelector
    </div>
@endsection

@push('scripts')
<script>
    new Selectr('#statuses');
</script>
@endpush

@section ('thead')
<tr>
    <th class="text-center">
        <div class="d-flex">
            <input type="checkbox" class="form-check-inline" id="select_all" />A/A
        </div>
    </th>
    <th {{ set_th_data('sequence_number') }}><span class="res">{{ config('preferences.rental_prefix').'#' }}</span></th>
    <th >{{ __('Πινακίδα') }}</th>
    <th {{ set_th_data('type.category.current_profile.title') }}>{{ __('Group') }}</th>
    <th >{{ __('Μοντέλο') }}</th>
    <th {{ set_th_data('checkout_station.current_profile.title') }}>{{ __('Σταθμός Παράδοσης') }}</th>
    <th {{ set_th_data('checkout_place_text') }}>{{ __('Τοποθεσία Παράδοσης') }}</th>
    <th {{ set_th_data('checkout_datetime') }}>{{ __('Ημερομηνία Παράδοσης') }}</th>
    <th {{ set_th_data('duration') }}>{{ __('Ημέρες Ενοικίασης') }}</th>
    <th {{ set_th_data('driver.contact.lastname') }}>{{ __('Πελάτης') }}</th>
    <th>{{ __('Τηλέφωνο') }}</th>
    <th {{ set_th_data('checkin_station.current_profile.title') }}>{{ __('Σταθμός Παραλαβής') }}</th>
    <th {{ set_th_data('checkin_place_text') }}>{{ __('Τοποθεσία Παραλαβής') }}</th>
    <th {{ set_th_data('checkin_datetime') }}>{{ __('Ημερομηνία Παραλαβής') }}</th>
    <th>{{ __('Συνεργάτης') }}</th>
    <th>{{ __('Voucher') }}</th>
    <th>{{ __('Κατάσταση') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($rentals as $index => $rental)
    @php
        if($agent = $rental->booking_source)
            $agent = $rental->booking_source->profile_title;
        if ($rental->agent) {
            $agent .= ' - '.$rental->agent->name;
        }
    @endphp
    <tr id="index_{{ $rental->id }}">
        <td class="text-center">
            <div class="d-flex">
                <input type="checkbox" class="data_checkbox form-check-inline"
                    data-id="{{ $rental->id }}" />
                    {{ ( ( $rentals->perPage() * $rentals->currentPage() )- $rentals->perPage() )+$index+1 }}</td>
            </div>
        </td>
        <td>{{ $rental->sequence_number }}</td>
        <td>
            <span
                @if ($rental->exchanges->count() > 1 ||
                    ($rental->exchanges->count() > 0 && $rental->exchanges[0]->status == \App\VehicleExchange::STATUS_COMPLETED))
                        class="exchanged licence"
                @endif>
                {{ $rental->vehicle->licence_plate ?? '' }}
            </span>
            @foreach ($rental->exchanges as $exchange)
                @if ($exchange->status == \App\VehicleExchange::STATUS_COMPLETED)
                    <span class="strikethrough">{{ $exchange->old_vehicle->licence_plate }}</span>
                @endif
            @endforeach
        </td>
        <td>{{ $rental->type->category->profile_title ?? '' }}</td>
        <td>{{ $rental->vehicle ? $rental->vehicle->make." ".$rental->vehicle->model : '' }}</td>
        <td>{{ $rental->checkout_station->profile_title ?? '' }}</td>
        <td>{{ $rental->checkout_place->profile_title ?? $rental->checkout_place_text }}</td>
        <td>{{ formatDateTime($rental->checkout_datetime) ?? '' }}</td>
        <td>{{ $rental->duration ?? '' }}</td>
        <td>{{ $rental->customer->full_name ?? '' }}</td>
        <td>{{ $rental->phone ?? ($rental->customer->phone ?? '') }}</td>
        <td>{{ $rental->checkin_station->profile_title ?? '' }}</td>
        <td>{{ $rental->checkin_place->profile_title ?? $rental->checkin_place_text }}</td>
        <td>{{ formatDateTime($rental->checkin_datetime) ?? '' }}</td>
        <td>{{ $agent }}</td>
        <td>{{ $rental->agent_voucher ?? '' }}</td>
        <td>{{ $rental->status }}</td>
        <td class="actions">
            <div class="inner">
                <a href="{{ route('rentals',$lng ?? 'el') }}/edit?cat_id={{ $rental->id }}&duplicate=1"
                    class="duplicate-btn edit_car btn btn-sm btn-light">
                    <i class="far fa-clone"></i>
                </a>
                @include ('template-parts.actions', [
                'route' => route('rentals',$lng ?? 'el'),
                'data' => $rental
                ])
            </div>
        </td>
    </tr>
@endforeach
@endsection
