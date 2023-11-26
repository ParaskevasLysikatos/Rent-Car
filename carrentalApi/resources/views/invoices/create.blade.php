@extends('layouts.app')
@php
    $mb='mb-2';

    if(isset($client) && $client->count()>0)
        $hasClient = true;
    else
        $hasClient = false;

@endphp
@section('title')
    {{ isset($invoice) ? __('Επεξεργασία Παραστατικού') : __('Προσθήκη Παραστατικού') }}
@endsection
@section('content')
    <div class="row justify-content-center invoice mb-5">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            @if(isset($invoice))
                <button type="submit" id="print_files" class="print_file float-left btn-sm btn-info fa fa-print">{{__('Εκτύπωση')}}</button>
            @endif
        </div>
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <form action="{{ isset($invoice) ? route('update_invoice', ['locale' => $lng, 'cat_id' => $invoice->id]) : route('create_invoice', $lng) }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header p-3">
                        <div class="row">
                            <div class="col-md-7">
                                <img style="background-color: darkblue" class="invoice-logo mb-2"
                                     src="https://www.carrentalthessaloniki.com/images/logo_blue_rent_a_car.png">
                            </div>
                            <div class="col-md-5 justify-content-center align-self-center">
                                <div class="mb-0 text-right">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">Ημερομηνία</span>
                                        </div>
                                            <input id="date" type="text" name="date" class="datepicker form-control text-center"
                                                @if(isset($invoice))value="{{ formatDate($invoice->date) }}" disabled
                                                @else value="{{ formatDate(now()) }}" @endif min="{{ $minDate ?? ''}}">
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">Σειρά</span>
                                        </div>
                                        @if(isset($invoice))
                                            <input disabled type="text" class="form-control" name="range" id="range" value="{{$invoice->sequence_number}}">
                                            @if ($invoice->sent_to_aade)
                                                <div class="d-flex align-items-center bagde badge-pill badge-primary"><span>MD</span></div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-0">
                            {{-- <div class="col-sm-6">
                                <label for="invoice_type">{{__('Τύπος')}}</label>
                                @if (!isset($invoice))
                                    <select class="form-control mb-3" name="invoice_type" id="invoice_type">
                                        <option value="invoice" @if(isset($invoice) && $invoice->type=='invoice'){{'selected'}}@endif >{{__('Τιμολόγιο')}}</option>
                                        <option value="evidence" @if(isset($invoice) && $invoice->type=='evidence'){{'selected'}}@endif >{{__('Απόδειξη')}}</option>
                                    </select>
                                @else
                                    <input class="form-control" type="text" disabled @if($invoice->type)value="{{ $invoice->type == 'invoice' ? 'Τιμολόγιο' : 'Απόδειξη' }}"@endif />
                                @endif
                            </div> --}}

                            @if(!isset($invoice))
                                <div class="col-sm-6">
                                    <label for="driver_id">{{__('Μίσθωση')}}</label>
                                    <div id="drivers_block">
                                        @selector([
                                            'id' => 'rental_id',
                                            'name' => 'rental_id',
                                            'data' => [],
                                            'value' => 'id',
                                            'text' => 'sequence_number',
                                            'searchUrl' => 'searchRentalUrl',
                                            'class' => 'App\Rental',
                                            'link' => route('edit_rental_view', ['locale' => $lng]).'?cat_id=',
                                            'extra_fields' => ['brand_id', 'checkout_station_id']
                                        ])
                                        @endselector
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-6">
                                <label for="invoicee_id">{{__('Παραστατικό για')}} :</label>
                                <div id="drivers_block">
                                    @if (!isset($invoice))
                                        @transactorSelector([
                                            'id' => 'invoicee_id',
                                            'name' => 'invoicee_id',
                                            'transactors' =>  [],
                                            'depends' => ['rental' => 'rental_id'],
                                            'searchUrl' => 'searchTransactorFromRentalUrl',
                                            'extra_fields' => [
                                                'transaction_id'
                                            ]
                                        ])
                                        @endtransactorSelector
                                    @else
                                        <input class="form-control" type="text" disabled value="{{ $invoice->invoicee->name ?? $invoice->invoicee->full_name }}" />
                                    @endif
                                    @if (!isset($invoice))
                                        {{-- <input type="hidden" id="transaction_id" name="transaction_id" /> --}}
                                    @endif
                                    <input type="hidden" id="invoicee_type" name="invoicee_type" @if (isset($invoice))value="{{ $invoice->invoicee_type }}"@endif />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="invoicee_id">{{__('Επωνυμία')}} :</label>
                                <div id="drivers_block">
                                    @if (!isset($invoice))
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            @foreach(App\Brand::all() as $brand)
                                                <option
                                                    value="{{$brand->id}}" @if((isset($invoice) && $brand->id == $invoice->brand_id)){{'selected'}}@endif>
                                                    @if(!is_null($brand->getProfileByLanguageId($lng)))
                                                        {{$brand->getProfileByLanguageId($lng)->title}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="form-control" type="text" disabled value="{{ $invoice->brand->profile_title ?? '' }}" />
                                    @endif
                                </div>
                            </div>

                            @if (!isset($invoice))
                                <div class="col-sm-6">
                                    <label for="driver_id">{{__('Σταθμός')}}</label>
                                    <div id="drivers_block">
                                        @stationSelector([
                                            'id' => 'station_id',
                                            'name' => 'station_id',
                                            'stations' => []
                                        ])
                                        @endstationSelector
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-6">
                                    <label for="driver_id">{{__('Σταθμός')}}</label>
                                    <div id="drivers_block">
                                        <input disabled class="form-control" type="text" disabled="" value="{{ $invoice->station->profile_title ?? '' }}">
                                        <input hidden class="form-control" type="text" disabled="" value="{{ $invoice->station_id }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div id="drivers_block">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Μίσθωση') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice->rentals as $rental)
                                                    <tr>
                                                        <td>
                                                            <a target="_blank" class="disabled" href="{{ route('create_rental_view', ['locale' => $lng, 'cat_id' => $rental->id]) }}">{{ $rental->sequence_number }}</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <label for="driver_id">{{__('Πληρωμές')}}</label>
                                <div id="drivers_block">
                                    @selector([
                                        'id' => 'payment_id',
                                        'name' => 'payment_id',
                                        'data' => isset($invoice) ? $invoice->collections : [],
                                        'value' => 'id',
                                        'text' => 'sequence_number',
                                        'searchUrl' => 'searchPaymentUrl',
                                        'class' => 'App\Payment',
                                        'link' => route('edit_payment_view', ['locale' => $lng, 'payment_type' => \App\Payment::PAYMENT_TYPE]).'?cat_id=',
                                        'query_fields' => !isset($invoice) ? [
                                            'invoice_id' => 0
                                        ] : [
                                            'invoice_id' => 0,
                                            'payer_id' => $invoice->payer_id,
                                            'payer_type' => $invoice->payer_type,
                                        ],
                                        'depends' => !isset($invoice) ? [
                                            'payer_id' => 'invoicee_id',
                                            'rental_id' => 'rental_id'
                                        ] : [],
                                        'multiple' => true
                                    ])
                                    @endselector
                                </div>
                            </div>

                            {{-- <div class="col-sm-6">
                                <label for="driver_id">{{__('Μίσθωση')}}</label>
                                <div id="drivers_block">
                                    @if (!isset($invoice))
                                        @selector([
                                            'id' => 'rental_id',
                                            'name' => 'rental_id',
                                            'data' => [],
                                            'value' => 'id',
                                            'text' => 'id',
                                            'searchUrl' => 'searchRentalUrl',
                                            'class' => 'App\Rental',
                                            'query_fields' => ['status' => \App\Rental::STATUS_PRE_CHECKED_IN, 'invoice' => null]
                                        ])
                                        @endselector
                                    @else
                                    @endif
                                </div>

                            </div> --}}
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <tbody>

                                <tr>
                                    <th></th>
                                    <th>Code</th>
                                    <th>Αιτιολογία Χρέωσης</th>
                                    <th class="text-right">Τιμή (με ΦΠΑ)</th>
                                    <th class="text-right">Ποσότητα</th>
                                    <th class="text-right">Σύνολο (με ΦΠΑ)</th>
                                </tr>
                                @if (isset($invoice))
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td></td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td class="text-right">{{ $item->price / $item->quantity }}</td>
                                            <td class="text-right">{{ $item->quantity }}</td>
                                            <td class="text-right">{{ $item->price }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @for($i=0; $i<1; $i++)
                                        <tr class="product{{$i}} product">
                                        </tr>
                                    @endfor
                                    <tr id="addProductRow">
                                        <td colspan="6">
                                            <input id="addProduct" type="button" value="Προσθήκη νέου προϊόντος">
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-sm-12 ml-auto">
                                <table class="table table-clear invoice-total">
                                    <tbody>
                                    <tr>
                                        <td class="text-left">
                                            <strong class="text-dark">Υποσύνολο</strong>
                                        </td>
                                        <td class="text-right">
                                            <div class="input-group">
                                                <input readonly class="form-control" name="sub_discount_total" id="sub_discount_total" @if(isset($invoice))value="{{ $invoice->sub_discount_total }}" @endif />
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">€</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left d-flex">
                                            <strong class="text-dark input-group-text">Φ.Π.Α. </strong>
                                            <div class="input-group">
                                                <input readonly class="form-control" type="number" class="text-right" id="invoice-fpa" name="fpa_perc"
                                                        @if(isset($invoice))value="{{ $invoice->fpa_perc }}"@endif value="24" max="100"/>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">%</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input readonly class="form-control" name="final_fpa" id="final_fpa" @if(isset($invoice))value="{{ $invoice->final_fpa }}" @endif />
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">€</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <strong class="text-dark">Σύνολο</strong></td>
                                        <td class="text-right">
                                            <div class="input-group">
                                                <input readonly class="form-control" name="final_total" id="final_total" @if(isset($invoice))value="{{ $invoice->final_total }}" @endif />
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">€</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <p class="mt-0">Σημειώσεις:</p>
                        <textarea class="form-control" id="notes0-notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="row options pt-5">
                    <div class="col-sm-12 text-right mb-2">
                        <input type="checkbox" name="print" id="printAndSave" checked>
                        <label for="printAndSave">Εκτύπωση μετά την αποθήκευση</label>
                    </div>
                    <div class="col-sm-12 text-right">
                        <input id="save_invoice" type="submit" class="btn btn-success" value="{{ isset($invoice) ? 'Αποθήκευση' : 'Δημιουργία' }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            @if(isset($invoice))
                print_mail_url = "{{route('mail_invoice_pdf', ['id'=>$invoice->id, 'locale'=>$lng])}}";
            @endif

            var searchange = "<?php echo e(route('search_range_ajax', $lng)); ?>";

            @if (!isset($invoice))
                invoicee_id.on('selectr.select', function(option) {
                    option = $(option);
                    const type = option.data('transactor_type');
                    // const debit = parseFloat(option.data('debit'));
                    // const net_debit = roundTo(debit/1.24, 2);
                    // const fpa = roundTo(net_debit*0.24, 2);
                    // const transaction_id = option.data('transaction_id');
                    $('#invoicee_type').val(type);
                    // $('#sub_discount_total').val(net_debit);
                    // $('#final_fpa').val(fpa);
                    // $('#final_total').val(debit);
                    // $('#transaction_id').val(transaction_id);
                });

                rental_id.on('ajax_search_extra_args', function (e) {
                    e.detail.depends.model = 'model';
                    e.detail.depends.make = 'make';
                });

                $('#payment_id').on('ajax_search_query_fields_args', function (e) {
                    e.detail.query_fields.payer_type = $('#invoicee_id').find(':selected').data('transactor_type');
                });


                rental_id.on('selectr.select', function(option) {
                    option = $(option);
                    $('#brand_id').val(option.data('brand_id'));
                    const station = station_id.getValue();
                    if (station != option.data('checkout_station_id')) {
                        station_id.setValue(option.data('checkout_station_id'));
                    }
                });
            @endif

            //Delete single client
            $(document).on('click', '.delete_invoice', function () {
                if (confirm("ΠΡΟΣΟΧΗ!\n\nΤο παραστατικό ίσως έχει συμμετάσχει σε φορολογικές αναφορές ή ίσως έχει δοθεί ήδη σε πελάτη.\n\nΘέλεις να συνεχίσεις τη διαγραφή; ")) {
                    var ids = [];
                    ids.push($(this).attr('data-id'));
                    $("#invoice_index_" + $(this).attr('data-id')).hide();
                    $("#invoice_index_" + $(this).attr('data-id')).remove();
                    console.log(ids);
                    $.post(delete_route, {
                        ids: ids
                    }, function ($return) {
                        console.log($return);
                    });
                } else
                    console.log("Canceled");
            });


            $(document).on('change', '#pages', function () {
                $("#change_pages").submit();
            });
            $(document).on('click', '#print_invoice', function () {
                window.print();
                return false;
            });
            $(document).on('click', '.deleteRow', function () {
                $('.product' + $(this).data('index')).remove();
                calculate_fees();
            });

            var index = 0;
            $(document).on('click', '#addProduct', function () {
                index++;
                $('<tr class="product' + index + ' product">' +
                    '    <td><i class="fa fa-trash text-danger deleteRow" data-index="' + index + '"></i></td>' +
                    '    <td>' +
                    '        <input type="text" min="1" required class="product' + index + '-code form-control" id="product' + index + '-title" name="products[' + index + '][code]" data-index="' + index + '" data-type="product" data-field="code"/>' +
                    '    </td>' +
                    '    <td>' +
                    '        <input type="text" min="1" required class="product' + index + '-title form-control" id="product' + index + '-title" name="products[' + index + '][title]" data-index="' + index + '" data-type="product" data-field="title"/>' +
                    '    </td>' +
                    '    <td class="text-right">' +
                    '        <div class="input-group float-right">' +
                    '            <input type="number" min="1" step="0.01" required class="product' + index + '-price text-right float-right form-control max-width subCalculate" id="product' + index + '-price" name="products[' + index + '][price]" data-index="' + index + '" data-type="product" data-field="price"/>' +
                    '            <div class="input-group-prepend">' +
                    '                <span class="input-group-text" id="basic-addon1">€</span>' +
                    '            </div>' +
                    '        </div>' +
                    '    </td>' +
                    '    <td class="text-right">' +
                    '        <input type="number" min="1" step="0.01" required class="product' + index + '-quantity float-right text-right form-control max-width subCalculate" id="product' + index + '-quantity" name="products[' + index + '][quantity]" data-index="' + index + '" data-type="product" data-field="quantity" value="1"/>' +
                    '    </td>' +
                    '    <td class="text-right product'+ index +'-total">' +
                    '       <div class="input-group">' +
                    '           <input class="sub_total form-control total' + index + '" val="0" name="products[' + index + '][total]" data-index="' + index + '" data-type="product" data-field="total">' +
                    '           <div class="input-group-prepend">' +
                    '               <span class="input-group-text" id="basic-addon1">€</span>' +
                    '           </div>' +
                    '       </div>' +
                    '    </td>' +
                    '</tr>').insertBefore("#addProductRow");
                calculate_fees();
            });

            $(document).on('keyup', '.subCalculate', function () {
                if ($(this).data('type') === 'product' && ($(this).data('field') === 'price' || $(this).data('field') === 'quantity')) {
                    $('.total' + $(this).data('index')).text('-');
                    var price = $('#product' + $(this).data('index') + "-price").val();
                    var quantity = $('#product' + $(this).data('index') + "-quantity").val();
                    price = parseFloat(price).toFixed(3);
                    quantity = parseFloat(quantity).toFixed(3);
                    $('.total' + $(this).data('index')).val(price * quantity);
                }
                calculate_fees();
            });

            $(document).on('keyup', '.sub_total', function () {
                if ($(this).data('type') === 'product') {
                    var quantity = $('#product' + $(this).data('index') + "-quantity").val();
                    total = $(this).val();
                    $('.product' + $(this).data('index') + '-price').val(parseFloat(total/quantity).toFixed(2));
                }
                calculate_fees();
            });

            $(document).on('keyup mouseup', '#invoice-fpa', function () {
                calculate_fees();
            });
            $(document).on('keyup mouseup', '#invoice-discount', function () {
                calculate_fees();
            });


            $(document).on('change', '#range, #invoice_type', function () {
                var range = $("#range").val();
                var type = $("#invoice_type").val();
                $('#save_invoice').prop('disabled', true);
                $('#date').prop('disabled', true);
                $('#number').prop('disabled', true);
                $.post(searchange, {
                    range: range,
                    type: type
                }, function (resposne) {
                    if (resposne === '' || resposne == null) {
                        $('#date').attr('min', 2010);
                        $('#number').attr('min', 1);
                        $('#number').val('1');
                    } else {
                        $('#date').attr('min', resposne.date);
                        $('#number').attr('min', resposne.number + 1);

                        $('#date').val(resposne.date);
                        $('#number').val(resposne.number + 1);
                    }

                    $('#save_invoice').prop('disabled', false);
                    $('#date').prop('disabled', false);
                    $('#number').prop('disabled', false);
                });
            });

            function calculate_discount() {
                var discount = 0;
                var discountPer = $("#invoice-discount").val();
                var sub_total = parseFloat($("#sub_total").text());
                discount = (discountPer / 100) * sub_total;
                $("#final_discount").text(discount.toFixed(2));
                $("#sub_discount_total").text((sub_total - discount).toFixed(2));
            }


            function calculate_fpa() {
                var fpa = 0;
                var total = parseFloat($('#final_total').val());
                var fpaPer = $("#invoice-fpa").val();
                var sub_total = total/(1 + fpaPer/100);
                fpa = total - sub_total;
                $("#final_fpa").val(fpa.toFixed(2));
                $('#sub_discount_total').val((sub_total).toFixed(2));
            }

            function calculate_sub_total() {
                var total = 0;

                $('.sub_total').each(function () {
                    total += parseFloat($(this).val());
                });

                $('#final_total').val(total.toFixed(2));
            }

            function calculate_fees() {
                calculate_sub_total();
                // calculate_discount();
                calculate_fpa();
            }
        </script>
    @endpush
    @include('template-parts.printer')
@endsection
