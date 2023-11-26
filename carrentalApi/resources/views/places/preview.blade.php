@extends ('layouts.tablePreview', [
'route' => route('places', $lng ?? 'el'),
'data' => $places,
'addButtonText' => 'Τοποθεσίας'
])

@section ('title')
    {{ __('Τοποθεσίες') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τοποθεσία') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Σταθμοί') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($places as $index => $place)
    <tr id="index_{{ $place->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $place->id }}" /></td>
        <th>{{ ( ( $places->perPage() * $places->currentPage() )- $places->perPage() )+$index+1 }}</th>
        <td>{{ $place->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $place->slug }}</td>
        <td>
            @php $stationStr = ''; $i=0; @endphp
            @foreach ($place->stations as $station)
                @php if ($i != 0){$stationStr .= ', ';} $i++;
                    $stationStr .= $station->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο')
                @endphp
            @endforeach
            {{ $stationStr }}
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('places', $lng ?? 'el'),
            'data' => $place
            ])
        </td>
    </tr>
@endforeach
@endsection
