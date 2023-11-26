@extends ('layouts.tablePreview', [
'route' => route('documents', $lng ?? 'el'),
'data' => $documents,
'addButtonText' => 'Document'
])

@section ('title')
    {{ __('Documents') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('ID') }}</th>
    <th>{{ __('Type') }}</th>
    <th>{{ __('User') }}</th>
    <th>{{ __('Path') }}</th>
    <th>{{ __('Mime') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($documents as $index => $document)
    <tr id="index_{{ $document->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $document->id }}" /></td>
        <th>{{ ( ( $documents->perPage() * $documents->currentPage() )- $documents->perPage() )+$index+1 }}</th>
        <td>{{ $document->id }}</td>
        <td>
            @if ($document->type)
                {{ $document->type->getProfileByLanguageId($lng)->title??'' }}
            @else
                -
            @endif
        </td>
        <td>{{ $document->user->name ?? '-' }}</td>
        <td>{{ $document->path }}</td>
        <td>{{ $document->mime_type ?? __('Μη μεταφρασμένο') }}
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('documents', $lng ?? 'el'),
            'data' => $document
            ])
        </td>
    </tr>
@endforeach
@endsection
