@extends ('layouts.tablePreview', [
'route' => route('brands',$lng ?? 'el'),
'data' => $brands,
'addButtonText' => 'Brand'
])

@section ('title')
    {{ __('Brands') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Εικονίδιο') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($brands as $index => $brand)
    <tr id="index_{{ $brand->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $brand->id }}" /></td>
        <th>{{ ( ( $brands->perPage() * $brands->currentPage() )- $brands->perPage() )+$index+1 }}</th>
        <td>{{ $brand->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $brand->slug }}</td>
        <th>
            @if ($brand->icon != NULL)
                <img class="img-thumbnail" src='{{ asset('storage/'.$brand->icon) }}'
                    width="40">
            @endif
        </th>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('brands',$lng ?? 'el'),
            'data' => $brand
            ])
        </td>
    </tr>
@endforeach
@endsection
