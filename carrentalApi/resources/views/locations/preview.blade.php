@extends ('layouts.tablePreview', [
'route' => route('locations', $lng ?? 'el'),
'data' => $locations,
'addButtonText' => 'Περιοχής'
])

@section ('title')
    {{ __('Περιοχές') }}
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
@foreach ($locations as $index => $location)
    <tr id="index_{{ $location->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $location->id }}" /></td>
        <th>{{ ( ( $locations->perPage() * $locations->currentPage() )- $locations->perPage() )+$index+1 }}</th>
        <td>{{ $location->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $location->slug }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('locations', $lng ?? 'el'),
            'data' => $location
            ])
        </td>
    </tr>
@endforeach
@endsection
