@extends ('layouts.tablePreview', [
'route' => route('languages', $lng ?? 'el'),
'data' => $languages,
'addButtonText' => 'Γλώσσας'
])

@section ('additional_scripts')
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script>
    var languageOrder = "{{ route('languages_order', $lng) }}";

</script>
@endsection

@section ('title')
    {{ __('Γλώσσες') }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Τίτλος') }}</th>
    <th>{{ __('Σύνδεσμος') }}</th>
    <th>{{ __('Κατάταξη') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($languages as $index => $language)
    <tr id="index_{{ $language->id }}" class="languageOrder" data-id="{{ $language->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $language->id }}" /></td>
        <th>{{ ( ( $languages->perPage() * $languages->currentPage() )- $languages->perPage() )+$index+1 }}</th>
        <td>{{ $language->title_international ?? $language->title ?? __('Μη μεταφρασμένο') }}
        </td>
        <td>{{ $language->id }}</td>
        <th>{{ $language->order }}</th>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('languages', $lng ?? 'el'),
            'data' => $language
            ])
        </td>
    </tr>
@endforeach
@endsection

@section ('content-footer')
<input type="button" class="btn btn-info" id="orderItems"
    value="{{ 'Ενημέρωση κατάταξης' }}" />
<script type="text/javascript">
    $('tbody').sortable();

</script>
@endsection
