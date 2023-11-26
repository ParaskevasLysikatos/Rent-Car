@php
    $addBtnText = 'Είσπραξης';
    $title = __('Εισπράξεις');
    switch ($payment_type) {
        case \App\Payment::REFUND_TYPE:
            $addBtnText = 'Επιστροφής Χρημάτων';
            $title = __('Επιστροφές Χρημάτων');
            break;
        case \App\Payment::PRE_AUTH_TYPE:
            $addBtnText = 'Εγγύησης';
            $title = __('Εγγυήσεις');
            break;
    }
@endphp

@extends ('layouts.tablePreview', [
'route' => route('payments', ['locale' => $lng ?? 'el', 'payment_type' => $payment_type]),
'data' => $payments,
'addButtonText' => $addBtnText
])

@section('top-part')
    <div class="row">
        <div class="col-12">
            <a class="btn btn-secondary @if($payment_type == \App\Payment::PAYMENT_TYPE) d-none @endif" href="{{ route('payments', ['locale' => $lng, 'payment_type' => \App\Payment::PAYMENT_TYPE]) }}">{{ __('Εισπράξεις') }}</a>
            <a class="btn btn-secondary @if($payment_type == \App\Payment::REFUND_TYPE) d-none @endif" href="{{ route('payments', ['locale' => $lng, 'payment_type' => \App\Payment::REFUND_TYPE]) }}">{{ __('Επιστροφές Χρημάτων') }}</a>
            <a class="btn btn-secondary @if($payment_type == \App\Payment::PRE_AUTH_TYPE) d-none @endif" href="{{ route('payments', ['locale' => $lng, 'payment_type' => \App\Payment::PRE_AUTH_TYPE]) }}">{{ __('Εγγυήσεις') }}</a>
            <a class="btn btn-secondary @if($payment_type == \App\Payment::REFUND_PRE_AUTH_TYPE) d-none @endif" href="{{ route('payments', ['locale' => $lng, 'payment_type' => \App\Payment::REFUND_PRE_AUTH_TYPE]) }}">{{ __('Επιστροφές Χρημάτων Εγγυήσεων') }}</a>
        </div>
    </div>
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
                <input type="text" class="datepicker form-control" name="payment_datetime[from]" id="payment_datetime_from"
                    value="@if(Request::has('payment_datetime.from')){{ formatDate(Request::get('payment_datetime')['from']) }}@endif">
            </div>
            <label>Έως: </label>
            <div>
                <input type="text" class="datepicker form-control" name="payment_datetime[to]" id="payment_datetime_to"
                    value="@if(Request::has('payment_datetime.to')){{ formatDate(Request::get('payment_datetime')['to']) }}@endif">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center">
        @php
            $payer_id = Request::has('payer_id') && !is_null(Request::get('payer_id')) ? Request::get('payer_id') : null;
            $payer_type = Request::has('payer_type') && !is_null(Request::get('payer_type')) ? Request::get('payer_type') : null;
            $transactors = [];
            if ($payer_id && $payer_type) {
                $payer = $payer_type::find($payer_id);
                $payer->transactor_id = $payer_id;
                $payer->transactor_type = $payer_type;
                $payer->name = $payer->name ?? $payer->full_name ?? null;
                $transactors[] = $payer;
            }
        @endphp
        <label>Πελάτης: </label>
        @transactorSelector([
            'id' => 'payer_id',
            'name' => 'payer_id',
            'transactors' => $transactors,
            'searchUrl' => 'searchTransactorFromRentalUrl',
            'html_class' => 'payer_selectr',
            'query_fields' => [
                'debit' => 0
            ]
        ])
        @endtransactorSelector
    </div>
    <div class="d-flex align-items-center">
        @php
            $methods = Request::has('method') && !is_null(Request::get('method')) ? Request::get('method') : [];
        @endphp
        <label>Τρόπος Πληρωμής: </label>
        <select multiple name="method[]" id="method">
            @foreach (\App\Payment::PAYMENTS_METHODS as $method)
                <option @if(in_array($method, $methods)) selected @endif value="{{ $method }}">{{ $method }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        @php
            $user_id = Request::has('user_id') && !is_null(Request::get('user_id')) ? Request::get('user_id') : [];
            $users = \App\User::whereIn('id', \App\Payment::select('user_id')->distinct()->get()->pluck('user_id'))->get();
        @endphp
        <label>Χρήστης: </label>
        <select name="user_id" id="user_id">
            <option value="">-</option>
            @foreach ($users as $user)
                <option @if($user->id == $user_id) selected @endif value="{{ $user->id }}">{{ $user->driver->full_name ?? '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        Συνολικό Ποσό: <span class="ml-2 font-weight-bold">{{ number_format($total, 2, ',', '.') }}€</span>
    </div>
@endsection

@push('scripts')
<script>
    new Selectr('#method');
</script>
@endpush

@section ('title')
    {{ $title }}
@endsection

@section ('thead')
<tr>
    <th class="text-center"><input type="checkbox" class="form-check-inline" id="select_all" /></th>
    <th>{{__('#')}}</th>
    <th class="res">{{ 'SEQ#' }}</th>
    <th>{{ __('Πελάτης') }}</th>
    <th>{{ __('Χρήστης') }}</th>
    <th>{{ __('Σύνολο') }}</th>
    <th>{{ __('Υποσύνολο') }}</th>
    <th>{{ __('Τρόπος Πληρωμής') }}</th>
    <th>{{ __('Ημερομηνία Πληρωμής') }}</th>
    <th class="text-right">{{ __('Ενέργειες') }}</th>
</tr>
@endsection

@section ('tbody')
@foreach ($payments as $index => $payment)
    <tr id="index_{{ $payment->id }}" class="paymentOrder" data-id="{{ $payment->id }}">
        <td class="text-center"><input type="checkbox" class="data_checkbox form-check-inline"
                data-id="{{ $payment->id }}" /></td>
        <td>{{ ( ( $payments->perPage() * $payments->currentPage() )- $payments->perPage() )+$index+1 }}</td>
        <td>{{ $payment->sequence_number }}</td>
        <td>{{ $payment->payer->name ?? $payment->payer->full_name ?? __('Άγνωστο') }}</td>
        <td>{{ $payment->user->name ?? __('Άγνωστο') }}</td>
        <td>{{ $payment->amount  ?? 0 }} &euro;</td>
        <td>{{ $payment->balance  ?? 0 }} &euro;</td>
        <th>{{ $payment->method }}</th>
        <td>{{ date('d-m-Y', strtotime($payment->payment_datetime)) }}</td>
        <td class="actions">
            @include ('template-parts.actions', [
            'route' => route('payments', ['locale' => $lng ?? 'el', 'payment_type' => $payment_type]),
            'data' => $payment
            ])
        </td>
    </tr>
@endforeach
@endsection
