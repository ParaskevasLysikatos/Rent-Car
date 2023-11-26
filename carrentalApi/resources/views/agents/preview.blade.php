@extends ('layouts.tablePreview', [
'route' => route('agents',$lng ?? 'el'),
'data' => $agents,
'addButtonText' => 'Affiliate'
])

@section ('title')
    {{ __('Affiliates') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Όνομα') }}</th>
    <th>{{ __('Προμήθεια') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($agents as $index => $agent)
    <tr id="index_{{ $agent->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $agent->id }}" /></td>
        <th>{{ ( ( $agents->perPage() * $agents->currentPage() )- $agents->perPage() )+$index+1 }}</th>
        <td>{{ $agent->name ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $agent->commission }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('agents',$lng ?? 'el'),
            'data' => $agent
            ])
        </td>
    </tr>
@endforeach
@endsection
