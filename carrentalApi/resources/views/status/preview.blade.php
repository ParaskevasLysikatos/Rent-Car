@extends ('layouts.tablePreview', [
'route' => route('status',$lng ?? 'el'),
'data' => $statuses,
'addButtonText' => 'Κατάστασης Οχήματος'
])

@section ('title')
    {{ __('Καταστάσεις Οχήματος') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Όνομα') }}</th>
    <th>{{ __('Περιγραφή') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($statuses as $index => $sts)
    <tr id="index_{{ $sts->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $sts->id }}" /></td>
        <th>{{ ( ( $statuses->perPage() * $statuses->currentPage() )- $statuses->perPage() )+$index+1 }}</th>
        <td>
            @if(!is_null($sts->getProfileByLanguageId($lng)))
                {{$sts->getProfileByLanguageId($lng)->title}}
            @endif
        </td>
        <td>
            @if(!is_null($sts->getProfileByLanguageId($lng)))
                {{$sts->getProfileByLanguageId($lng)->description}}
            @endif
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('status',$lng ?? 'el'),
            'data' => $sts
            ])
        </td>
    </tr>
@endforeach
@endsection
