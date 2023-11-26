@extends ('layouts.tablePreview', [
'route' => route('options', ['locale' => $lng ?? 'el', 'option_type' => $option_type]),
'data' => $options,
'addButtonText' => $option_type
])

@section ('title')
    {{ __($option_type) }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Περιγραφή') }}</th>
    <th>{{ __('Μέγιστο κόστος') }}
    </th>
    <th>{{ __('Κόστος ανά ημέρα') }}
    </th>
    <th>{{ __('Μέγιστη ποσότητα') }}
    </th>
    <th>{{ __('Εικονίδιο') }}</th>
    <th>{{ __('Πάντα επιλεγμένο') }}
    </th>
    <th>{{ __('Σειρά') }}</th>

    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($options as $index => $option)
    <tr id="index_{{ $option->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $option->id }}" /></td>
        <td>{{ ( ( $options->perPage() * $options->currentPage() )- $options->perPage() )+$index+1 }}</td>
        <td>{{ $option->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $option->getProfileByLanguageId($lng)->description ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $option->cost_max??"-" }}</td>
        <td>
            {{ ($option->cost_daily) }}
            {{ ($option->active_daily_cost)? '('.__('ενεργό').')' :'' }}
        </td>
        <td>{{ ($option->items_max) }}</td>
        <td>
            @if ($option->icon != NULL)
                <img class="img-thumbnail" src='{{ asset('storage/'.$option->icon) }}'
                    width="40">
            @endif
        </td>
        <td>@if($option->default_on){{ __('Ναι') }}@else{{ __('Όχι') }}@endif
        </td>
        <td>{{ $option->order }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('options', ['locale' => $lng ?? 'el', 'option_type' => $option_type]),
            'data' => $option
            ])
        </td>
    </tr>
@endforeach
@endsection
