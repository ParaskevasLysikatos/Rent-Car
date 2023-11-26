@extends ('layouts.tablePreview', [
'route' => route('users', $lng ?? 'el'),
'data' => $users,
'addButtonText' => 'Χρήστη'
])

@section ('title')
    {{ __('Χρήστες') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Όνομα') }}</th>
    <th>{{ __('E-mail') }}</th>
    <th>{{ __('Τηλέφωνο') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($users as $index => $user)
    <tr id="index_{{ $user->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $user->id }}" /></td>
        <th>{{ ( ( $users->perPage() * $users->currentPage() )- $users->perPage() )+$index+1 }}</th>
        <td>{{ $user->name ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('users', $lng ?? 'el'),
            'data' => $user
            ])
        </td>
    </tr>
@endforeach
@endsection
