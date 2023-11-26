<div class="card-header">
    @yield('title')
    @yield('card-header')
</div>
@php $rand = rand(); @endphp
<div class="card-body pb-0">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#formData{{ $rand }}"
            data-second-tab="#main-fields-nav{{ $rand }}"
            role="tab" aria-controls="basic_car_info"
            aria-selected="true">{{__('Πληροφορίες')}}</a>
        </li>
        @yield('additional-tabs')
        <li class="nav-item">
            {{-- <a class="nav-link" data-toggle="tab" href="#financials{{ $rand }}">{{__('Συναλλαγές')}}</a> --}}
        </li>
    </ul>
    <div class="tab-content">
        <div id="formData{{ $rand }}" class="tab-pane fade show active">
            <form method="POST" action="{{ $formAction }}"
                enctype="multipart/form-data">
                @csrf
                <ul class="hide nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a id="main-fields-nav{{ $rand }}" data-toggle="tab" href="#main-fields{{ $rand }}"
                        role="tab" aria-controls="basic_car_info"
                        aria-selected="true">{{__('Πληροφορίες')}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="main-fields{{ $rand }}" class="tab-pane fade show active">
                        @yield('main-fields')
                    </div>
                </div>
                <div class="card-footer multilingual-card-footer">
                    <a href="{{ $formCancel }}"
                    class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                    <button type="submit" class="btn btn-success float-right">{{ $formSubmit }}</button>
                </div>
            </form>
        </div>
        @yield('additional-tabs-content')
        {{-- <div id="financials{{ $rand }}" class="tab-pane">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Ημερομηνία') }}</th>
                        <th>{{ __('Παραστατικό') }}</th>
                        <th>{{ __('Χρέωση') }}</th>
                        <th>{{ __('Πίστωση') }}</th>
                        <th>{{ __('Υπόλοιπο') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $balancing = 0; @endphp
                    @if ($balances)
                        @php $balances = $balances->get(); @endphp
                        @foreach ($balances as $balance)
                            <tr>
                                <td>{{ formatDatetime($balance->balance_datetime) }}</td>
                                <td>{{ $balance->transaction ? $balance->transaction->voucher : ($balance->payment ? 'PAY-'.$balance->payment->id : '') }}</td>
                                <td>{{ $balance->debit }}</td>
                                <td>{{ $balance->credit }}</td>
                                @php $balancing += $balance->debit - $balance->credit; @endphp
                                <td>{{ $balancing }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> --}}
    </div>
</div>
