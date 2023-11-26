@extends ('layouts.app')
@php $rand = rand(); @endphp
@section ('content')
    @yield('scripts')
    <ul class="nav nav-tabs booking-navigation" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#formData{{ $rand }}"
            role="tab" aria-controls="basic_car_info"
            aria-selected="true">{{__('Γενικά')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#payments{{ $rand }}">{{__('Εισπράξεις')}}</a>
        </li>
        @yield('extra_tabs')
        <li>
            <h1 class="mr-3">
                @yield('title') @yield('sub-title')
            </h1>
        </li>
    </ul>
    @yield('forms')
    <form id="booking-form" action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="create_next" name="create_next" />
        <div class="tab-content">
            <div id="formData{{ $rand }}" class="tab-pane fade show active">
                @yield('booking')
            </div>
            <div id="payments{{ $rand }}" class="tab-pane fade container">
                @php
                    $payments = [];
                    $payers = [];
                    if (isset($model) && !$duplicate) {
                        $payments = $model->payments ?? [];
                    }
                @endphp
                <table class="debts-table table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Χρέωση σε') }}:</th>
                            <th>{{ __('Πληρωτέο') }}</th>
                            <th>{{ __('Υπόλοιπο') }}</th>
                            <th>{{ __('Συνολικό') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr data-type="driver">
                            <td class="name"></td>
                            <td class="paid"></td>
                            <td class="rest"></td>
                            <td class="total"></td>
                    </tr>
                    <tr data-type="company">
                            <td class="name"></td>
                            <td class="paid"></td>
                            <td class="rest"></td>
                            <td class="total"></td>
                    </tr>
                    <tr data-type="agent">
                            <td class="name"></td>
                            <td class="paid"></td>
                            <td class="rest"></td>
                            <td class="total"></td>
                    </tr>
                    </tbody>
                </table>
                <div class="d-flex">
                    <h3>{{ __('Εισπράξεις') }}</h3>
                    <button type="button" data-type="{{ \App\Payment::PAYMENT_TYPE }}" class="btn btn-default btn-payment ml-3 mb-2 change-popup-title">{{ __('Προσθήκη Είσπραξης') }}</button>
                    <button type="button" data-type="{{ \App\Payment::REFUND_TYPE }}" class="btn btn-secondary btn-payment ml-3 mb-2 change-popup-title">{{ __('Επιστροφή Χρημάτων') }}</button>
                    <button type="button" data-type="{{ \App\Payment::PRE_AUTH_TYPE }}" class="btn btn-secondary btn-payment ml-3 mb-2 change-popup-title">{{ __('Προσθήκη Εγγύησης') }}</button>
                    <button type="button" data-type="{{ \App\Payment::REFUND_PRE_AUTH_TYPE }}" class="btn btn-secondary btn-payment ml-3 mb-2 change-popup-title">{{ __('Επιστροφή Χρημάτων Εγγύησης') }}</button>
                </div>
                <table class="payments-table table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Είσπραξη από') }}:</th>
                            <th>{{ __('Ποσό') }}</th>
                            <th>{{ __('Ημερομηνία') }}</th>
                            <th>{{ __('Τύπος Πληρωμής') }}</th>
                            <th>{{ __('Τρόπος Πληρωμής') }}</th>
                            <th>{{ __('Σειρά') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $paid = [];
                            $pre_auth = [];
                            $payment_fields = \Schema::getColumnListing('payments');
                            $payment_number = 0;
                        @endphp
                    @foreach ($payments as $payment)
                            @php
                                if ($payment->payment_type == \App\Payment::PRE_AUTH_TYPE
                                    || $payment->payment_type == \App\Payment::REFUND_PRE_AUTH_TYPE) {
                                    if (!isset($pre_auth[$payment->payer_type])) {
                                        $pre_auth[$payment->payer_type] = 0;
                                    }
                                    $pre_auth[$payment->payer_type] += $payment->amount;
                                } else {
                                    if (!isset($paid[$payment->payer_type])) {
                                        $paid[$payment->payer_type] = 0;
                                    }
                                    $paid[$payment->payer_type] += $payment->amount;
                                }
                            @endphp
                            <tr data-type="{{ $payment->payer_type }}" data-number="{{ $payment_number }}">
                                {{-- @foreach ($payment_fields as $field)
                                    <input data-name="{{ $field }}" type="hidden" name="payments[{{ $payment_number }}][{{ $field }}]" value="{{ $payment->{$field} }}" />
                                @endforeach --}}
                                <td>{{ $payment->payer->full_name ?? $payment->payer->name ?? $model->customer_text }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ formatDatetime($payment->payment_datetime) }}</td>
                                <td>
                                    @if($payment->payment_type == \App\Payment::PRE_AUTH_TYPE)
                                        {{ __('Εγγύηση') }}
                                    @elseif($payment->payment_type == \App\Payment::REFUND_TYPE)
                                        {{ __('Επιστροφή Χρημάτων') }}
                                    @elseif ($payment->payment_type == \App\Payment::REFUND_PRE_AUTH_TYPE)
                                        {{ __('Επιστροφή Χρημάτων Εγγύησης') }}
                                    @else
                                        {{ __('Είσπραξη') }}
                                    @endif
                                </td>
                                <td>{{ $payment->method }}</td>
                                <td>{{ $payment->sequence_number }}</td>
                                <td><a target="_blank" class="btn" href="{{ route('edit_payment_view', ['locale' => $lng, 'payment_type' => $payment->payment_type, 'cat_id' => $payment->id ]) }}"><i class="fas fa-eye"></i></a></td>
                                {{-- <td><button type="button" class="edit_payment btn btn-primary"><i class="fas fa-edit"></i></button></td> --}}
                            </tr>
                            @php $payment_number++; @endphp
                    @endforeach
                    @php unset($payment); @endphp
                    </tbody>
                </table>
            </div>
            @yield('extra_content')
        </div>
    </form>
    @php
        $driver_debt = 0;
        $agent_debt = 0;
        $company_debt = 0;
        if (isset($model) && $model->transactions) {
            $driver_debt = $model->transactions()->where('transactor_type', \App\Driver::class)->get()->sum('debit');
            $driver_debt *= $model->discount == 100 ? 0 : 100/(100 - $model->discount);
            $agent_debt = $model->transactions()->where('transactor_type', \App\Agent::class)->get()->sum('debit');
            $agent_debt *= $model->discount == 100 ? 0 : 100/(100 - $model->discount);
            $company_debt = $model->transactions()->where('transactor_type', \App\Company::class)->get()->sum('debit');
            $company_debt *= $model->discount == 100 ? 0 : 100/(100 - $model->discount);
        }
        $agent_paid = $paid['App\\Agent'] ?? 0;
        $agent_show_pay = 0;
        if (isset($model)) {
            if ($agent_paid > $model->voucher) {
                $agent_show_pay = $agent_paid - $model->voucher;
            }
        }
    @endphp
    @push('scripts')
        <script>
            payment_type = "{{ \App\Payment::PAYMENT_TYPE }}";
            refund_type = "{{ \App\Payment::REFUND_TYPE }}";
            pre_auth_type = "{{ \App\Payment::PRE_AUTH_TYPE }}";
            refund_pre_auth_type = "{{ \App\Payment::REFUND_PRE_AUTH_TYPE }}";
            driver.paid = {{ $paid['App\\Driver'] ?? 0 }};
            driver.pre_auth = {{ $pre_auth['App\\Driver'] ?? 0 }};
            company.paid = {{ $paid['App\\Company'] ?? 0 }};
            company.pre_auth = {{ $pre_auth['App\\Company'] ?? 0 }};
            agent.paid = {{ $paid['App\\Agent'] ?? 0 }};
            agent.pre_auth = {{ $pre_auth['App\\Agent'] ?? 0 }};
            payment_number = {{ $payment_number }};
            @if (isset($model) && $model->payments)
                $('#total_paid').val({{ $model->getTotalPaid() + $agent_show_pay }} - agent.paid);
            @endif
        </script>
    @endpush

    @push('modals')
        @modal(['name' => 'paymentModal'])
            @include('payments.form', ['manual' => true])
        @endmodal
    @endpush
@endsection
