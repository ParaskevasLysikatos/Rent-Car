@php
    $mb='mb-2';
@endphp
<div class="card-header text-center">
    <h1 id="custom-rental-popup-title">
        @if (isset($payment_type))
            {{ \App\Payment::getTypeTitle($payment_type) }}@if (isset($payment)) - {{ $payment->sequence_number }} @endif
        @else
            -
        @endif
    </h1>
    @if(isset($payment))
        <button type="submit" id="print_files" class="print_file float-left btn-sm btn-info fa fa-print">{{__('Εκτύπωση')}}</button>
        @php
            $transactor_id = $payment->payer_id;
            $transactor_type = $payment->payer_type;
        @endphp
    @endif
</div>
<form id="payment" method="POST" action="{{ route('create_payment', $lng ?? 'el') }}"
        enctype="multipart/form-data">
    @csrf
    <input id="payment_type" name="payment_type" type="hidden" @if(isset($payment_type)) value="{{ $payment_type }}" @endif />
    @if(isset($rental_id))
        <input type="hidden" name="rental_id" value="{{ $rental_id }}" />
    @endif
    <div class="card-body">
        @if(isset($payment))
            <input type="hidden" name="id" value="{{$payment->id}}">
        @endif
        <div class="row">
            <div class="col-sm-6">
                <div class="{{$mb}}">
                    <label for="payment_datetime">* {{__('Ημερομηνία Είσπραξης')}}:</label>
                    @datetimepicker([
                        'id' => 'payment_datetime',
                        'name' => 'payment_datetime',
                        'datetime' => isset($payment) ? $payment->payment_datetime : now(),
                        'required' => true
                    ])
                    @enddatetimepicker
                </div>
                <div id="drivers_block" class="{{$mb}}">
                    <label for="driver_id">{{__('Πελάτης')}}:</label>
                    @if (isset($manual) && $manual == true)
                        <select id="payer_id" name="payer_id"></select>
                    @elseif (!isset($payment) || !$payment->invoice())
                        @php
                            $transactor_params = [
                                'id' => 'payer_id',
                                'name' => 'payer_id',
                                'html_class' => 'payer_selectr',
                                'transactors' =>  [],
                            ];
                            if (isset($rental_id)) {
                                $transactor_params['query_fields'] = ['rental_id' => $rental_id];
                            }
                            if (isset($payment) && $payment->payer_id) {
                                $payer = $payment->payer;
                                $payer->transactor_id = $payment->payer_id;
                                $payer->transactor_type = $payment->payer_type;
                                $payer->name = $payer->name ?? $payer->full_name ?? null;
                                $transactors[] = $payer;
                                $transactor_params['transactors'] = $transactors;
                                if ($payment->rental()) {
                                    $transactor_params['query_fields'] = ['rental_id' => $payment->rental()->id];
                                }
                            }
                        @endphp
                        @transactorSelector($transactor_params)
                        @endtransactorSelector
                    @else
                        <input disabled class="form-control" type="text" value="{{ $payment->payer->name ?? $payment->payer->full_name ?? $payment->booking()->customer_text }}" />
                        <input class="form-control" type="hidden" name="payer_id" value="{{ $payment->payer->id ?? '' }}" />
                    @endif
                    <input type="hidden" id="payer_type" name="payer_type" @if (isset($payment))value="{{ $payment->payer_type }}"@endif />
                </div>
                <div class="{{$mb}}">
                    <label for="balance">{{__('Υπόλοιπο')}}:</label>
                    <div class="input-group">
                        <input disabled id="balance" name="balance" type="text"
                                class="form-control float-input"
                                value="">
                        <div class="input-group-append">
                            <label class="form-control">€</label>
                        </div>
                    </div>
                </div>
                <div class="{{$mb}}">
                    <label for="amount">* {{__('Ποσό Είσπραξης')}}:</label>
                    <div class="input-group">
                        <input required id="amount" name="amount" type="text"
                                class="form-control float-input"
                                value="@if(isset($payment)){{$payment->amount}}@else{{old('amount')}}@endif">
                        <div class="input-group-append">
                            <label class="form-control">€</label>
                        </div>
                    </div>
                </div>
                @if (!isset($manual) || $manual === false)
                    <div class="{{$mb}}">
                        <label for="rental_id">* {{__('Μίσθωση')}}:</label>
                        <div class="input-group">
                            @selector([
                                'id' => 'rental_id',
                                'name' => 'rental_id',
                                'data' => [],
                                'value' => 'id',
                                'text' => 'sequence_number',
                                'searchUrl' => 'searchRentalUrl',
                                'class' => 'App\Rental',
                                'link' => route('edit_rental_view', ['locale' => $lng]).'?cat_id=',
                                'depends' => [
                                    'transactor' => 'payer_id'
                                ],
                                'extra_fields' => ['brand_id', 'checkout_station_id', 'checkout_place_id', 'checkout_place_text']
                            ])
                            @endselector
                        </div>
                    </div>
                @endif
                <div id="reference_block" class="{{$mb}} @if(!isset($payment) || (isset($payment) && $payment->method=='cash')){{'d-none'}}@endif">
                    <label for="reference">{{__('Αρ. Αναφοράς')}}:</label>
                    <input id="reference" name="reference" type="text"
                            class="form-control"
                            value="@if(isset($payment)){{$payment->reference}}@else{{old('reference')}}@endif">
                </div>
            </div>
            <div class="col-sm-6">
                <div id="brand" class="{{$mb}}">
                    <label for="brand_id">* {{__('Επωνυμία')}}:</label>
                    <select name="brand_id" id="brand_id" class="form-control">
                        @foreach(App\Brand::all() as $brand)
                            <option
                                value="{{$brand->id}}" @if((isset($rental) && $brand->id == $rental->brand_id)){{'selected'}}@endif>
                                @if(!is_null($brand->getProfileByLanguageId($lng)))
                                    {{$brand->getProfileByLanguageId($lng)->title}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="create_by" class="{{$mb}}">
                    <label for="user_id">* {{__('Χρήστης')}}:</label>
                    @userSelector([
                        'name' => 'user_id',
                        'users' => isset($payment) ? [$payment->user] : [Auth::user()],
                        'required' => true
                    ])
                    @enduserSelector
                </div>
                <div class="{{$mb}}">
                    <label for="station">* {{__('Σταθμός')}}:</label>
                    @stationSelector([
                        'id' => 'station_id',
                        'name' => 'station',
                        'stations' => isset($payment) ? [$payment->station] : []
                    ])
                    @endstationSelector
                </div>
                <div class="{{$mb}}">
                    <label for="place">{{__('Περιοχή')}}:</label>
                    @placesSelector([
                        'id' => 'place',
                        'name' => 'place',
                        'option' => isset($payment) ? $payment->place : null,
                        'text' => isset($payment) && $payment->place_text ? $payment->place_text : null,
                        'depends' => ['stations' => 'station_id']
                    ])
                    @endplacesSelector
                </div>
                <div class="{{$mb}}">
                    <label for="payment_method">{{__('Τρόπος Είσπραξης')}}:</label>
                    <select name="payment_method" id="payment_method" class="form-control">
                        @foreach(config('ea.payment_methods') as $tft => $val)
                            <option
                                value="{{$val}}" @if(isset($payment) && $payment->method == $val){{'selected'}}@endif >{{__($tft)}}</option>
                        @endforeach
                    </select>
                </div>

                <div id="credit_card" class="payment_method" @if(isset($payment) && $payment->method!='credit_card')style="display:none"@endif >
                    <div class="{{$mb}}">
                        <select required id="card_type" name="card_type" class="form-control">
                            @foreach (\App\Payment::CARD_TYPES as $card_type => $value)
                                <option @if(isset($payment) && $payment->card_type == $card_type) selected @endif value="{{ $card_type }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="{{$mb}}">
                        <input required id="credit_card_number" name="credit_card_number"
                                type="text"
                                class="form-control" placeholder="{{__('Αριθμός κάρτας')}}:"
                                value="@if(isset($payment)){{$payment->credit_card_number}}@else{{old('credit_card_number')}}@endif">
                    </div>
                    <div class="{{$mb}}">
                        <div class="form-group form-inline justify-content-end">
                            <label for="credit_card_month" class="mr-3">{{__('Ημερομηνία λήξης')}}
                                (MMYY): </label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <select name="credit_card_month" id="credit_card_month"
                                            class="form-control">
                                        @foreach(config('ea.months') as $tft => $val)
                                            <option
                                                value="{{$val}}" @if(isset($payment) && $payment->credit_card_month == $val){{'selected'}}@endif >{{__($tft)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <select name="credit_card_year" id="credit_card_year"
                                            class="form-control">
                                        @foreach(range(date("Y")+10, date("Y")-10) as $year)
                                            <option
                                                value="{{$year}}" @if(isset($payment) && $payment->credit_card_year == $year){{'selected'}}@endif >{{__($year)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="cheque" class="payment_method" @if(isset($payment) && $payment->method!='cheque')style="display:none"@endif >
                    <div class="{{$mb}}">
                        <input required id="cheque_number" name="cheque_number" type="number"
                                class="form-control" placeholder="{{__('Αριθμός επιταγής')}}:"
                                value="@if(isset($payment)){{$payment->cheque_number}}@else{{old('cheque_number')}}@endif">
                    </div>
                    <div class="{{$mb}}">
                        <div class="form-group form-inline justify-content-end">
                            <label for="cheque_due_date" class="mr-3">{{__('Ημερομηνία λήξης')}}
                                : </label>
                            <input required id="cheque_due_date" name="cheque_due_date" type="text"
                                    class="datepicker form-control float-right"
                                    value="@if(isset($payment)){{ formatDate($payment->cheque_due_date) }}@else{{ formatDate(now()) }}@endif">
                        </div>
                    </div>
                </div>

                <div id="bank_transfer" class="payment_method" @if(isset($payment) && $payment->method!='bank_transfer')style="display:none"@endif>
                    <div class="{{$mb}}">
                        <input id="bank_transfer_account" name="bank_transfer_account"
                                type="text"
                                class="form-control" placeholder="{{__('IBAN')}}:"
                                value="@if(isset($payment)){{$payment->bank_transfer_account}}@else{{old('bank_transfer_account')}}@endif">
                    </div>
                    <div class="{{$mb}}">
                        <label for="foreigner">Τράπεζα Εξωτερικού</label>
                        <input type="checkbox" name="foreigner" id="foreigner" value="1" @if(isset($payment) && $payment->foreigner) checked @endif />
                    </div>
                </div>

            </div>
            <div class="col-sm-12">
                <div class="{{$mb}}">
                    <label for="comments" class="mr-3">{{__('Σημειώσεις')}}: </label>
                    <textarea id="comments" name="comments"
                                class="form-control">@if(isset($payment)){{$payment->comments}}@else{{old('comments')}}@endif</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="{{$mb}}">
                    <div class="{{$mb}}">
                        <label for="files" class="mr-3">{{ _('Επισυναπτόμενα έγγραφα') }}: </label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple>
                    </div>
                    <div class="form-inline {{$mb}}">
                        <div class="transfer_info_title text-right">
                            <label class="float-right mr-3">{{ __('Αρχεία') }}:</label>
                        </div>
                        <div class="transfer_info_option text-center">
                            @if(isset($payment))
                                @foreach($payment->documents as $file)
                                    @document(['file' => $file, 'document_link_id' => $payment->id, 'document_link_type' => get_class($payment) ])
                                    @enddocument
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($payment))
                <div class="col-sm-12">
                    <div class="{{$mb}}">
                        Συνδέσεις:
                    </div>
                    <div class="{{$mb}}">
                        @foreach ($payment->links as $link)
                            @php
                                $route;
                                $link_id = $link->payment_link_id;
                                switch($link->payment_link_type) {
                                    case \App\Rental::class:
                                        $route = route('create_rental_view', ['locale' => $lng ?? 'el', 'cat_id' => $link_id ]);
                                        break;
                                    case \App\Booking::class:
                                        $route = route('create_booking_view', ['locale' => $lng ?? 'el', 'cat_id' => $link_id ]);
                                        break;
                                    case \App\Invoice::class:
                                        $route = route('create_invoice_view', ['locale' => $lng ?? 'el', 'cat_id' => $link_id ]);
                                        break;
                                }
                            @endphp
                            <a href="{{ $route }}">{{ $link->payment_link->sequence_number ?? '' }}</a>
                            <br/>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a @if(isset($payment_type))href="{{route('payments', ['locale' => $lng ?? 'el', 'payment_type' => $payment_type])}}"@endif
            class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
        <button type="submit"
                class="btn btn-success float-right">{{ (isset($payment))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
    </div>
</form>


<script>
    @if(isset($payment))
        print_mail_url = "{{route('mail_payment_pdf', ['id'=>$payment->id, 'locale'=>$lng])}}";
    @endif

    var typingTimer;                //timer identifier
    var doneTypingInterval = 700;  //time in ms, 5 second for example

    @if (!isset($payment) && (!isset($manual) || $manual != true))
        payer_id.on('selectr.select', function(option) {
            option = $(option);
            const type = option.data('transactor_type');
            const balance = parseFloat(option.data('balance'));
            $('#payment').find('#payer_type').val(type);
            $('#payment').find('#balance').val(balance);
            $('#payment').find('#balance').trigger('input');
            $('#payment').find('#amount').val(balance);
            $('#payment').find('#amount').trigger('input');
        });

        rental_id.on('selectr.select', function(option) {
            option = $(option);
            $('#brand_id').val(option.data('brand_id'));
            const station = station_id.getValue();
            if (station != option.data('checkout_station_id')) {
                station_id.setValue(option.data('checkout_station_id'));
            }
            setTimeout(function () {
                $('#place').find('.option_name').val(option.data('checkout_place_text'));
                $('#place').find('.option_id').val(option.data('checkout_place_id'));
            }, 1000);
        });

        $('#rental_id').on('ajax_search_query_fields_args', function (e) {
            e.detail.query_fields.transactor_type = $('#payer_type').val();
        });
    @endif

    $(document).ready(function () {
        @if(! isset($payment))
        $('.payment_method').hide();
        @endif
        $('.payment_method input, .payment_method select').each(function () {
            $(this).prop("disabled", true);
        });

        $(document).on('change', '#payment_method', function (e) {
            $('.payment_method').hide();
            $('.payment_method input, .payment_method select').each(function () {
                $(this).prop("disabled", true);
            });
            $('#' + $(this).val()).show(500);
            $('#' + $(this).val() + ' input, ' + '#' + $(this).val() + ' select').each(function () {
                $(this).prop("disabled", false);
            });
        });

    });
</script>

@include('template-parts.printer')
