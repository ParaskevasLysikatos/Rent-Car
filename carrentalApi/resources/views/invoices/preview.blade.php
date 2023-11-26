@extends ('layouts.tablePreview', [
'route' => route('invoices', $lng ?? 'el'),
'data' => $invoices,
// 'addButtonText' => 'Παραστατικού',
'export_fields' => [
    'sequence_number' => 'Αριθμός Παραστατικού',
    'date' => 'Ημερομηνία Έκδοσης',
    'instance.name' => 'Όνομα Πελάτη',
    'instance.afm' => 'ΑΦΜ',
    'instance.licence_plate' => 'Πινακίδα',
    'instance.checkout_datetime' => 'Ημερομηνία Έναρξης',
    'instance.checkin_datetime' => 'Ημερομηνία Λήξης',
    'sub_discount_total' => 'Καθαρή Αξία',
    'final_fpa' => 'ΦΠΑ',
    'instance.rental_sequence_number' => 'RNT',
]
])


@section ('title')
    {{ __('Παραστατικά') }}
@endsection

@section('filters')
    <div>
        <div class="d-flex align-items-center">
        <label>Σταθμός: </label>
        @php
            $stations = [];
            if (Request::has('station_id') && Request::get('station_id')) {
                $stations = [\App\Station::find(Request::get('station_id'))];
            }
        @endphp
        @stationSelector([
            'name' => 'station_id',
            'stations' => $stations,
            'without_default' => true
        ])
        @endstationSelector
        </div>
        <div class="d-flex align-items-center">
            <label>Από:</label>
            <div>
                <input type="text" class="datepicker form-control" name="date[from]" id="date_from"
                    value="@if(Request::has('date.from')){{ formatDate(Request::get('date')['from']) }}@endif">
            </div>
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="date[to]" id="date_to"
                    value="@if(Request::has('date.to')){{ formatDate(Request::get('date')['to']) }}@endif">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center">
        @php
            $invoicee_id = Request::has('invoicee_id') && !is_null(Request::get('invoicee_id')) ? Request::get('invoicee_id') : null;
            $invoicee_type = Request::has('invoicee_type') && !is_null(Request::get('invoicee_type')) ? Request::get('invoicee_type') : null;
            $transactors = [];
            if ($invoicee_id && $invoicee_type) {
                $invoicee = $invoicee_type::find($invoicee_id);
                $invoicee->transactor_id = $invoicee_id;
                $invoicee->transactor_type = $invoicee_type;
                $invoicee->name = $invoicee->name ?? $invoicee->full_name ?? null;
                $transactors[] = $invoicee;
            }
        @endphp
        <label>Πελάτης: </label>
        @transactorSelector([
            'id' => 'invoicee_id',
            'name' => 'invoicee_id',
            'transactors' => $transactors,
            'searchUrl' => 'searchTransactorFromRentalUrl',
            'html_class' => 'invoicee_selectr',
            'query_fields' => [
                'invoice' => true
            ]
        ])
        @endtransactorSelector
    </div>
    <div class="d-flex align-items-center">
        @php
            $types = Request::has('type') && !is_null(Request::get('type')) ? Request::get('type') : null;
        @endphp
        <label>Τύπος: </label>
        <select name="type" id="types">
            <option @if($types == null) selected @endif value="">Όλα</option>
            @foreach (\App\Invoice::TYPES as $type)
                <option @if($type == $types) selected @endif value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        @php
            $sent_to_aade = Request::has('sent_to_aade') && !is_null(Request::get('sent_to_aade')) ? Request::get('sent_to_aade') : null;
        @endphp
        <label>ΑΑΔΕ: </label>
        <select name="sent_to_aade" id="send_to_aade">
            <option @if($sent_to_aade === null) selected @endif value="">Συγχρονισμένα και μη</option>
            <option @if($sent_to_aade == 1) selected @endif value="1">Συγχρονισμένα</option>
            <option @if($sent_to_aade === '0') selected @endif value="0">Μη συγχρονισμένα</option>
        </select>
    </div>
    <div class="d-flex align-items-center">
        Συνολικό Ποσό: <span class="ml-2 font-weight-bold">{{ number_format($total, 2, ',', '.') }}€</span>
    </div>
@endsection

@push('scripts')
<script>
    new Selectr('#types', {allowDeselect: true, renderSelection: deselectSelectr});
</script>
@endpush

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th {{ set_th_data('id') }}>{{ 'Σειρά' }}</th>
    <th>{{ __('Πελάτης') }}</th>
    <th>{{ __('Μισθωτήριο') }}</th>
    <th>{{ __('Τύπος') }}</th>
    <th {{ set_th_data('date') }}>{{ __('Ημερομηνία') }}</th>
    <th>{{ __('Ποσό') }}</th>
    <th>{{ __('Τρόποι Πλήρωμης') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($invoices as $index => $invoice)
    <tr id="index_{{ $invoice->id }}" class="invoiceOrder" data-id="{{ $invoice->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $invoice->id }}" /></td>
        <th>{{ ( ( $invoices->perPage() * $invoices->currentPage() )- $invoices->perPage() )+$index+1 }}</th>
        <td>{{ $invoice->sequence_number }} @if($invoice->sent_to_aade) <div class="d-inline bagde badge-pill badge-primary">MD</div> @endif</td>
        <td>{{ $invoice->invoicee->name ?? $invoice->invoicee->full_name ?? __('Άγνωστο') }}</td>
        <td><a href="{{ route('edit_rental_view', ['cat_id' => $invoice->rental_id,'locale' => $lng ?? 'el']) }}" target="_blank">{{ $invoice->rental->sequence_number ?? '' }}</a></td>
        <td>{{ $invoice->type ?? __('Άγνωστο') }}</td>
        <td>{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
        <td>{{ $invoice->final_total }}</td>
        <td>
            @php
                $methods = [];
                foreach ($invoice->collections as $payment) {
                    $methods[] = $payment->method_title;
                }
                $methods = array_unique($methods);
            @endphp
            {{ implode(', ', $methods) }}
        </td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('invoices', $lng ?? 'el'),
            'data' => $invoice
            ])
        </td>
    </tr>
@endforeach
@endsection
