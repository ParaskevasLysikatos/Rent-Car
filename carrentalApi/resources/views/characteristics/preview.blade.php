@extends ('layouts.tablePreview', [
'route' => route('characteristics', $lang ?? 'el'),
'data' => $characteristics,
'addButtonText' => 'Χαρακτηριστικού'
])

@section ('title')
    {{ __('Χαρακτηριστικά') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Εικονίδιο') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')

@foreach ($characteristics as $index => $characteristic)
    <tr id="index_{{ $characteristic->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $characteristic->id }}" /></td>
        <th>{{ ( ( $characteristics->perPage() * $characteristics->currentPage() )- $characteristics->perPage() )+$index+1 }}</th>
        <td>{{ $characteristic->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $characteristic->slug }}</td>
        <th>
            @if ($characteristic->icon != NULL)
                <img class="img-thumbnail" src='{{ asset('storage/'.$characteristic->icon) }}'
                    width="40">
            @endif
        </th>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('characteristics', $lang ?? 'el'),
            'data' => $characteristic
            ])
        </td>
    </tr>
@endforeach
@endsection
