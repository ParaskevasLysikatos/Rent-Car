@extends ('layouts.tablePreview', [
    'route' => route('roles', $lng ?? 'el'),
    'data' => $roles,
    'addButtonText' => 'Ρόλου'
])

@section('title')
    {{ __('Ρόλοι') }}
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
@foreach ($roles as $index => $role)
    <tr id="index_{{ $role->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $role->id }}" /></td>
        <th>{{ ( ( $roles->perPage() * $roles->currentPage() )- $roles->perPage() )+$index+1 }}</th>
        <td>{{ $role->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $role->description }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('roles', $lng ?? 'el'),
            'data' => $role
            ])
        </td>
    </tr>
@endforeach
@endsection
