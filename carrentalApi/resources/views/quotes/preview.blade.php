@extends ('layouts.tablePreview', [
'route' => route('quotes',$lng ?? 'el'),
'data' => $quotes,
'addButtonText' => 'Προσφοράς'
])

@section ('title')
{{ __('Προσφορές') }}
@endsection

@section('filters')
    <div class="">
        <strong>Παράδοση</strong>
        <div class="d-flex align-items-center">
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
        <div class="d-flex align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[from]"
                    value="@if(Request::has('checkout_datetime.from')){{ formatDate(Request::get('checkout_datetime')['from']) }}@endif">
            </div>
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkout_datetime[to]"
                    value="@if(Request::has('checkout_datetime.to')){{ formatDate(Request::get('checkout_datetime')['to']) }}@endif">
            </div>
        </div>
    </div>
    <div class="">
        <strong>Παραλαβή</strong>
        <div class="d-flex align-items-center">
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
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="checkin_datetime[to]"
                    value="@if(Request::has('checkin_datetime.to')){{ formatDate(Request::get('checkin_datetime')['to']) }}@endif">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center">
        @php
            $statuses = Request::has('status') && !is_null(Request::get('status')) ? Request::get('status') : [];
        @endphp
        <label>Κατάσταση: </label>
        <select multiple name="status[]" id="statuses">
            @foreach (\App\Quote::getValidStatuses() as $status)
                <option @if(in_array($status, $statuses)) selected @endif value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
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
    <th {{ set_th_data('sequence_number') }} ><span class="res">{{ config('preferences.quote_prefix').'#' }}</span></th>
    <th {{ set_th_data('type.category.current_profile.title') }} >{{ __('Group') }}</th>
    <th {{ set_th_data('checkout_datetime') }} >{{ __('Ημερομηνία Παραλαβής') }}</th>
    <th {{ set_th_data('checkout_place_text') }} >{{ __('Τοποθεσία Παραλαβής') }}</th>
    <th {{ set_th_data('checkout_station.current_profile.title') }} >{{ __('Σταθμός Παραλαβής') }}</th>
    <th {{ set_th_data('duration') }} >{{ __('Ημέρες Ενοικίασης') }}</th>
    <th {{ set_th_data('customer_text') }} >{{ __('Πελάτης') }}</th>
    <th {{ set_th_data('phone') }} >{{ __('Τηλέφωνο') }}</th>
    <th {{ set_th_data('source.current_profile.title') }} >{{ __('Πηγή - Συνεργάτης') }}</th>
    <th {{ set_th_data('status') }} data-orderBy="status"                           >{{ __('Κατάσταση') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($quotes as $index => $quote)
    <tr id="index_{{ $quote->id }}">
        <td class="text-center">
            <div class="d-flex">
                <input type="checkbox" class="data_checkbox form-check-inline"
                    data-id="{{ $quote->id }}" />
                    {{ ( ( $quotes->perPage() * $quotes->currentPage() )- $quotes->perPage() )+$index+1 }}
            </div>
        </td>
        <td>{{ $quote->sequence_number }}</td>
        <td>{{ $quote->type->category->profile_title ?? '' }}</td>
        <td>{{ formatDateTime($quote->checkout_datetime) ?? '' }}</td>
        <td>{{ $quote->checkout_place_text ?? '' }}</td>
        <td>{{ $quote->checkout_station->profile_title ?? '' }}</td>
        <td>{{ $quote->duration ?? '' }}</td>
        <td>{{ $quote->customer_text ?? '' }}</td>
        <td>{{ $quote->phone ?? ($quote->customer->phone ?? '') }}</td>
        <td>{{ $quote->source->profile_title }} @if($quote->agent_id){{ ' - '.$quote->agent->name }}@endif</td>
        <td>{{ $quote->status }}</td>
        <td class="actions">
            <div class="inner">
                <a href="{{ route('quotes',$lng ?? 'el') }}/edit?cat_id={{ $quote->id }}&duplicate=1"
                    class="duplicate-btn edit_car btn btn-sm btn-light">
                    <i class="far fa-clone"></i>
                </a>
                @include ('template-parts.actions', [
                'route' => route('quotes',$lng ?? 'el'),
                'data' => $quote
                ])
            </div>
        </td>
    </tr>
@endforeach
@endsection
