@extends ('layouts.tablePreview', [
'route' => route('rate_codes', $lng ?? 'el'),
'data' => $rate_codes,
'addButtonText' => 'Rate Code'
])

@section('title')
    {{ __('Rate Codes') }}
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
@foreach ($rate_codes as $index => $rate_code)
    <tr id="index_{{ $rate_code->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $rate_code->id }}" /></td>
        <th>{{ ( ( $rate_codes->perPage() * $rate_codes->currentPage() )- $rate_codes->perPage() )+$index+1 }}</th>
        <td>{{ $rate_code->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $rate_code->slug }}</td>
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('rate_codes', $lng ?? 'el'),
            'data' => $rate_code
            ])
        </td>
    </tr>
@endforeach
@endsection
