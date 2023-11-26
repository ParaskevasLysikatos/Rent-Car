@extends ('layouts.tablePreview', [
    'route' => route('types', $lng ?? 'el'),
    'data' => $types,
    'addButtonText' => 'Τύπου'
])

@section('title')
    {{ __('Τύποι') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Κατηγορία') }}</th>
    <th>{{ __('Χαρακτηριστικά') }}
    </th>
    <th>{{ __('Παροχές') }}</th>
    <th>{{ __('Εικόνα') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($types as $index => $type)
    <tr id="index_{{ $type->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $type->id }}" /></td>
        <th>{{ ( ( $types->perPage() * $types->currentPage() )- $types->perPage() )+$index+1 }}</th>
        <td>{{ $type->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $type->slug }}</td>
        <td>{{ $type->category->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $type->characteristics->count() }}</td>
        <td>{{ $type->options->count() }}</td>
        <th>{{ $type->images->count() }}</th>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('types', $lng ?? 'el'),
            'data' => $type
            ])
        </td>
    </tr>
@endforeach
@endsection
