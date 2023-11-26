@extends ('layouts.tablePreview', [
'route' => route('categories', $lng ?? 'el'),
'data' => $categories,
'addButtonText' => 'Κατηγορίας'
])

@section ('title')
    {{ __('Κατηγορίες') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" />
    </th>
    <th>{{__('#')}}</th>
    <th>{{ __('Κατηγορία') }}</th>
    <th>{{ __('Σύνδεσμος') }}
    </th>
    <th class="text-right">
        {{ __('Ενέργειες') }}
    </th>
</tr>
@endsection

@section ('tbody')
@foreach ($categories as $index => $category)
    <tr id="index_{{ $category->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $category->id }}" />
        </td>
        <th>{{ ( ( $categories->perPage() * $categories->currentPage() )- $categories->perPage() )+$index+1 }}</th>
        <td>{{ $category->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $category->slug }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('categories', $lng ?? 'el'),
            'data' => $category
            ])
        </td>
    </tr>
@endforeach
@endsection
