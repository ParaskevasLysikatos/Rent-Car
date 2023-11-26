@php $lng = Lang::locale(); @endphp
@extends ('layouts.tablePreview', [
'route' => route('contacts', $lng),
'data' => $contacts,
'addButtonText' => 'Επαφής'
])

@section ('title')
    {{ __('Επαφές') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Όνομα') }}</th>
    <th>{{ __('Επώνυμο') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($contacts as $index => $contact)
    <tr id="index_{{ $contact->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $contact->id }}" /></td>
        <th>{{ ( ( $contacts->perPage() * $contacts->currentPage() )- $contacts->perPage() )+$index+1 }}</th>
        <td>{{ $contact->firstname ?? __('-') }}</td>
        <td>{{ $contact->lastname ?? __('-') }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('contacts', $lng),
            'data' => $contact
            ])
        </td>
    </tr>
@endforeach
@endsection
