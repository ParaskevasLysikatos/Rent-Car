@extends ('layouts.tablePreview', [
'route' => route('booking_sources', $lng ?? 'el'),
'data' => $booking_sources,
'addButtonText' => 'Πηγής Κράτησης'
])

@section ('title')
    {{ __('Πηγές Κρατήσεων') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($booking_sources as $index => $place)
    <tr id="index_{{ $place->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $place->id }}" /></td>
        <th>{{ ( ( $booking_sources->perPage() * $booking_sources->currentPage() )- $booking_sources->perPage() )+$index+1 }}</th>
        <td>{{ $place->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $place->slug }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('booking_sources', $lng ?? 'el'),
            'data' => $place
            ])
        </td>
    </tr>
@endforeach
@endsection
