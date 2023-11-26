@extends ('layouts.tablePreview', [
'route' => route('color_types', $lng ?? 'el'),
'data' => $color_types,
'addButtonText' => 'Χρώμα'
])

@section('title')
    {{ __('Χρώματα') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Χρώμα') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($color_types as $index => $color_type)
    <tr id="index_{{ $color_type->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $color_type->id }}" /></td>
        <th>{{ ( ( $color_types->perPage() * $color_types->currentPage() )- $color_types->perPage() )+$index+1 }}</th>
        <td>
            {{ $color_type->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>
            <span class="car-color" style="--color-car:{{ $color_type->hex_code }}"></span>
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('color_types', $lng ?? 'el'),
            'data' => $color_type
            ])
        </td>
    </tr>
@endforeach
@endsection
