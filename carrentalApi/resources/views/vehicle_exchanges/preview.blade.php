@extends ('layouts.tablePreview', [
'route' => route('exchanges',$lng ?? 'el'),
'data' => $vehicle_exchanges
])

@section ('title')
    {{ __('Αντικαταστάσεις Οχημάτων') }}
@endsection

@section('filters')

@endsection

@section ('thead')
<tr>
    <th class="text-center">
        <div class="d-flex">
            <input type="checkbox" class="form-check-inline" id="select_all" />A/A
        </div>
    </th>
    <th>{{ __('Όχημα που αντικαταστάθηκε') }}</th>
    <th>{{ __('Διανυθέντα Χιλιόμετρα') }}</th>
    <th>{{ __('Νέο όχημα') }}</th>
    <th>{{ __('Διανυθέντα Χιλιόμετρα') }}</th>
    <th>{{ __('Ραντεβού') }}</th>
    <th>{{ __('Ημ. Αντικατάστασης') }}</th>
    <th>{{ __('RNT') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($vehicle_exchanges as $index => $vehicle_exchange)
    @php
        if($agent = $vehicle_exchange->booking_source)
            $agent = $vehicle_exchange->booking_source->profile_title;
        if ($vehicle_exchange->agent) {
            $agent .= ' - '.$vehicle_exchange->agent->name;
        }
    @endphp
    <tr id="index_{{ $vehicle_exchange->id }}">
        <td class="text-center">
            <div class="d-flex">
                <input type="checkbox" class="data_checkbox form-check-inline"
                    data-id="{{ $vehicle_exchange->id }}" />
                    {{ ( ( $vehicle_exchanges->perPage() * $vehicle_exchanges->currentPage() )- $vehicle_exchanges->perPage() )+$index+1 }}</td>
            </div>
        </td>
        <td>{{ $vehicle_exchange->old_vehicle->licence_plate ?? '' }}</td>
        <td>{{ $vehicle_exchange->old_vehicle_km }}</td>
        <td>{{ $vehicle_exchange->new_vehicle->licence_plate ?? '' }}</td>
        <td>{{ $vehicle_exchange->new_vehicle_km }}</td>
        <td>{{ formatDateTime($vehicle_exchange->proximate_datetime) }}</td>
        <td>{{ formatDateTime($vehicle_exchange->datetime) }}</td>
        <td><a href="{{ route('edit_rental_view', ['locale' => $lng, 'cat_id' => $vehicle_exchange->rental_id]) }}">{{ $vehicle_exchange->rental->sequence_number ?? '' }}</a></td>
        <td class="actions">
            <div class="inner">
                @include ('template-parts.actions', [
                    'route' => route('exchanges',$lng ?? 'el'),
                    'data' => $vehicle_exchange
                ])
            </div>
        </td>
    </tr>
@endforeach
@endsection
