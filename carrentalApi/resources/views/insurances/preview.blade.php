@extends ('layouts.tablePreview', [
'route' => route('insurances', $lng ?? 'el'),
'data' => $insurances,
'addButtonText' => 'Ασφάλειας'
])

@section('title')
    {{ __('Ασφάλειες') }}
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
@foreach ($insurances as $index => $insurance)
    <tr id="index_{{ $insurance->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $insurance->id }}" /></td>
        <th>{{ ( ( $insurances->perPage() * $insurances->currentPage() )- $insurances->perPage() )+$index+1 }}</th>
        <td>{{ $insurance->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $insurance->slug }}</td>
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('insurances', $lng ?? 'el'),
            'data' => $insurance
            ])
        </td>
    </tr>
@endforeach
@endsection
