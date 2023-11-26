@extends ('layouts.tablePreview', [
'route' => route('documentTypes', $lng ?? 'el'),
'data' => $documentTypes,
'addButtonText' => 'Document Type'
])

@section ('title')
    {{ __('Document Types') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Περιγραφή') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($documentTypes as $index => $documentType)
    <tr id="index_{{ $documentType->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $documentType->id }}" /></td>
        <th>{{ ( ( $documentTypes->perPage() * $documentTypes->currentPage() )- $documentTypes->perPage() )+$index+1 }}</th>
        <td>{{ $documentType->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $documentType->getProfileByLanguageId($lng)->description ?? '-' }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('documentTypes', $lng ?? 'el'),
            'data' => $documentType
            ])
        </td>
    </tr>
@endforeach
@endsection
