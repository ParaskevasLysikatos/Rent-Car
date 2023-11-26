@extends ('layouts.tablePreview', [
'route' => route('bookings',$lng ?? 'el'),
'data' => $bookings,
'addButtonText' => 'Κράτησης',
'extra_filters' => [
    'agent_id',
    'source_id',
    'sub_account_id',
    'sub_account_type'
],
'export_fields' => [
    'sequence_number' => 'Αριθμός Κράτησης',
    'created_at' => 'Ημ. Εισαγωγής',
    'vehicle.licence_plate' => 'Πινακίδα',
    'type.category.profile_title' => 'Group',
    'vehicle.whole_model' => 'Μοντέλο',
    'checkout_station.profile_title' => 'Σταθμός Παράδοσης',
    'checkout_place_text' => 'Τοποθεσία Παράδοσης',
    'checkout_datetime' => 'Ημερομηνία Παράδοσης',
    'duration' => 'Ημέρες Ενοικίασης',
    'customer_text' => 'Πελάτης',
    'phone' => 'Τηλέφωνο',
    'checkin_datetime' => 'Ημερομηνία Παραλαβής',
    'checkin_station.profile_title' => 'Σταθμός Παραλαβής',
    'checkin_place_text' => 'Τοποθεσία Παραλαβής',
    'total' => 'Τελικό Κόστος',
    'booking_source.profile_title' => 'Πηγή',
    'agent.name' => 'Συνεργάτης',
    'subaccount_normalized.name' => 'Πωλητής',
    'program.profile_title' => 'Πρόγραμμα',
    'confirmation_number' => 'Conf#',
    'agent_voucher' => 'Voucher'
]
])

@section ('title')
{{ __('Κρατήσεις') }}
@endsection

@section('filters')
    @if(isset($_GET['shortlink']))
        <input type="hidden" name="shortlink" value="{{$_GET['shortlink']}}">
    @endif
    <div class="filters-block-1 ">
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
        <div class="@if(isset($_GET['shortlink']) && $_GET['shortlink']==1){{'d-none'}}@else{{'d-flex'}}@endif align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[from]" id="checkout_datetime_from"
                    value="@if(Request::has('checkout_datetime.from')){{ formatDate(Request::get('checkout_datetime')['from']) }}@endif">
            </div>
            <label class="mr-2">Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[to]" id="checkout_datetime_to"
                    value="@if(Request::has('checkout_datetime.to')){{ formatDate(Request::get('checkout_datetime')['to']) }}@endif">
            </div>
        </div>
        @if(isset($_GET['shortlink']) && $_GET['shortlink']==1)
        <div class="d-flex align-items-center">
            <label>Ημερομηνία:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime_single"
                       value="@if(Request::has('checkout_datetime.from')){{ formatDate(Request::get('checkout_datetime')['from']) }}@endif">
            </div>
        </div>
        @endif
    </div>
    <div class="ml-5 filters-block-2 @if(isset($_GET['shortlink']) && $_GET['shortlink']==1){{'d-none'}}@endif ">
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
        <div class="d-flex align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkin_datetime[from]"
                    value="@if(Request::has('checkin_datetime.from')){{ formatDate(Request::get('checkin_datetime')['from']) }}@endif">
            </div>
            <label class="mr-2">Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkin_datetime[to]"
                    value="@if(Request::has('checkin_datetime.to')){{ formatDate(Request::get('checkin_datetime')['to']) }}@endif">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center filters-block-2">
        @php
            $statuses = Request::has('status') && !is_null(Request::get('status')) ? Request::get('status') : [];
        @endphp
        <label>Κατάσταση: </label>
        <select multiple name="status[]" id="statuses">
            @foreach (\App\Booking::getValidStatuses() as $status)
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
    <th {{ set_th_data('sequence_number') }}><span class="res">{{ config('preferences.booking_prefix').'#' }}</span></th>
    <th {{ set_th_data('type.category.current_profile.title') }}>{{ __('Group') }}</th>
    <th {{ set_th_data('checkout_station.current_profile.title') }}>{{ __('Σταθμός Παράδοσης') }}</th>
    <th {{ set_th_data('checkin_station.current_profile.title') }}>{{ __('Τοποθεσία Παράδοσης') }}</th>
    <th {{ set_th_data('checkout_datetime') }}>{{ __('Ημερομηνία Παράδοσης') }}</th>
    <th {{ set_th_data('duration') }}>{{ __('Ημέρες Ενοικίασης') }}</th>
    <th {{ set_th_data('customer_text') }}>{{ __('Πελάτης') }}</th>
    <th {{ set_th_data('phone') }}>{{ __('Τηλέφωνο') }}</th>
    <th {{ set_th_data('checkin_station.current_profile.title') }}>{{ __('Σταθμός Παραλαβής') }}</th>
    <th {{ set_th_data('checkin_place_text') }}>{{ __('Τοποθεσία Παραλαβής') }}</th>
    <th {{ set_th_data('checkin_datetime') }}>{{ __('Ημερομηνία Παραλαβής') }}</th>
    <th >{{ __('Πηγή - Συνεργάτης') }}</th>
    <th {{ set_th_data('status') }}>{{ __('Κατάσταση') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($bookings as $index => $booking)
    @php
        $agent = $booking->booking_source->profile_title ?? '';
        if ($booking->agent) {
            $agent .= ' - '.$booking->agent->name;
        }
    @endphp
    <tr id="index_{{ $booking->id }}">
        <td class="text-center">
            <div class="d-flex">
                <input type="checkbox" class="data_checkbox form-check-inline"
                    data-id="{{ $booking->id }}" />
                    {{ ( ( $bookings->perPage() * $bookings->currentPage() )- $bookings->perPage() )+$index+1 }}</td>
            </div>
        </td>
        <td>{{ $booking->sequence_number }}</td>
        <td>{{ $booking->type->category->profile_title ?? '' }}</td>
        <td>{{ $booking->checkout_station->profile_title ?? '' }}</td>
        <td>{{ $booking->checkout_place_text ?? '' }}</td>
        <td>{{ formatDateTime($booking->checkout_datetime) ?? '' }}</td>
        <td>{{ $booking->duration }}</td>
        <td>{{ $booking->customer_text ?? '' }}</td>
        <td>{{ $booking->phone ?? ($booking->customer->phone ?? '') }}</td>
        <td>{{ $booking->checkin_station->profile_title ?? '' }}</td>
        <td>{{ $booking->checkin_place_text ?? '' }}</td>
        <td>{{ formatDateTime($booking->checkin_datetime) ?? '' }}</td>
        <td>{{ $agent }}</td>
        <td>{{ $booking->status }}</td>
        <td class="actions">
            <div class="inner">
                <a href="{{ route('bookings',$lng ?? 'el') }}/edit?cat_id={{ $booking->id }}&duplicate=1"
                    class="duplicate-btn edit_car btn btn-sm btn-light">
                    <i class="far fa-clone"></i>
                </a>
                @include ('template-parts.actions', [
                    'route' => route('bookings',$lng ?? 'el'),
                    'data' => $booking
                ])
            </div>
        </td>
    </tr>
@endforeach
@endsection
