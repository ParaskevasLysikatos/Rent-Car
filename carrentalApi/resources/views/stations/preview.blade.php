@extends ('layouts.tablePreview', [
'route' => route('stations', $lng ?? 'el'),
'data' => $stations,
'addButtonText' => 'Σταθμού'
])

@section('title')
    {{ __('Σταθμοί') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Γεωγραφικό Διαμέρισμα') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($stations as $index => $station)
    <tr id="index_{{ $station->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $station->id }}" /></td>
        <th>{{ ( ( $stations->perPage() * $stations->currentPage() )- $stations->perPage() )+$index+1 }}</th>
        <td>{{ $station->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $station->slug }}</td>
        <td>{{ $station->location->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('stations', $lng ?? 'el'),
            'data' => $station
            ])
        </td>
    </tr>
@endforeach
@endsection
