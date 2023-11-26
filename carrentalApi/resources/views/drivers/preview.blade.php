@extends ('layouts.tablePreview', [
'route' => route('drivers', $lng ?? 'el'),
'data' => $drivers,
'addButtonText' => 'Οδηγού'
])

@section ('title')
    {{ __('Οδηγοί') }}
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
@foreach ($drivers as $index => $driver)
    <tr id="index_{{ $driver->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $driver->id }}" /></td>
        <th>{{ ( ( $drivers->perPage() * $drivers->currentPage() )- $drivers->perPage() )+$index+1 }}</th>
        <td>{{ $driver->firstname ?? __('-') }}</td>
        <td>{{ $driver->lastname ?? __('-') }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('drivers', $lng ?? 'el'),
            'data' => $driver
            ])
        </td>
    </tr>
@endforeach
@endsection
