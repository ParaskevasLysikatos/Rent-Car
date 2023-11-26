@extends ('layouts.tablePreview', [
'route' => route('companies', $lng ?? 'el'),
'data' => $companies,
'addButtonText' => 'Εταιρίας'
])

@section ('title')
    {{ __('Εταιρίες') }}
@endsection

@section('filters')
    <div class="d-flex align-items-center ml-5">
        @php
            $foreign_afm = Request::has('foreign_afm') && !is_null(Request::get('foreign_afm')) ? Request::get('foreign_afm') : null;
        @endphp
        <label>Τύπος ΑΦΜ: </label>
        <select name="foreign_afm" id="foreign_afm">
            <option @if(is_null($foreign_afm)) selected @endif value="">Όλα</option>
            <option @if($foreign_afm === '0') selected @endif value="0">Ελληνικά</option>
            <option @if($foreign_afm === '1') selected @endif value="1">Ξένα</option>
        </select>
    </div>
@endsection

@push('scripts')
<script>
    new Selectr('#foreign_afm', {
        searchable: false
    });
</script>
@endpush

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th>{{ __('Επωνυμία') }}</th>
    <th>{{ __('Διακριτικός Τίτλος') }}</th>
    <th>{{ __('ΑΦΜ') }}</th>
    <th>{{ __('Τηλέφωνο') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($companies as $index => $company)
    <tr id="index_{{ $company->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $company->id }}" /></td>
        <th>{{ ( ( $companies->perPage() * $companies->currentPage() )- $companies->perPage() )+$index+1 }}</th>
        <td>{{ $company->name ?? __('-') }}</td>
        <td>{{ $company->title ?? __('-') }}</td>
        <td>{{ $company->afm ?? __('-') }}</td>
        <td>{{ $company->phone ?? __('-') }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('companies', $lng ?? 'el'),
            'data' => $company
            ])
        </td>
    </tr>
@endforeach
@endsection
