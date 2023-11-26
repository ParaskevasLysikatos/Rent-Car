@extends('layouts.app')

@section('content')
    @php
        $total =0;
    @endphp
    @if(isset($_GET['print']) && $_GET['print']=='on')
        <script>
            $(document).ready(function () {
                $('#print_invoice').click();
            });
        </script>
    @endif
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="row justify-content-center invoice mb-5">
                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header p-3">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="d-flex flex-row bd-highlight">
                                        <div class="img-thumbnail bg-gradient-light bd-highlight flex-row">
                                            <img style="background-color: darkblue; max-width: 200px" class="invoice-logo mb-2" src="https://www.carrentalthessaloniki.com/images/logo_blue_rent_a_car.png">
                                        </div>
                                        <div class="pl-2 pr-2 bd-highlight">
                                            <h3>{{ config('company.name') }} <br/><small>Λουκάς Κατσαμάγκας</small></h3>
                                            <hr class="m-0">
                                            <p class="mb-0">Α.Φ.Μ.: <b>{{ config('company.afm') }}</b>, Τηλ.:
                                                <b>{{ config('company.phone') }}</b></p>
                                            <p class="mb-0">Δ.Ο.Υ.: <b>{{ config('company.doy') }}</b>, e-mail:
                                                <b>{{ config('company.email') }}</b></p>
                                            <p class="mb-0">Διεύθυνση: <b>{{ config('company.address') }}
                                                    , {{ config('company.city') }} </b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 justify-content-center align-self-center">
                                    <div class="mb-0 text-right">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Ημερομηνία</span>
                                            </div>
                                            <input id="date" type="text" name="date" class="datepicker form-control text-center"
                                                   value="{{ formatDate($invoice->date) }}" disabled>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Σειρά</span>
                                            </div>
                                            <input type="text" class="form-control" name="range" id="range"
                                                   value="{{$invoice->range}}" disabled>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Αριθμός</span>
                                            </div>
                                            <input id="number" type="number" required class="form-control" name="number"
                                                   value="{{$invoice->number}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-0">
                                <h3 class="mb-3">{{ ucfirst(__($invoice->type)) }}</h3>
                                <table class="table table-bordered table-striped invoice-details">
                                    <tr>
                                        <td></td>
                                        <td><b class="text-dark">Πελάτης</b></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Επωνυμία:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->name }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Τηλέφωνο:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark ">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->phone }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Ιστότοπος:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->website }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Α.Φ.Μ.:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark ">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->afm }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Δ.Ο.Υ.:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark ">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->doy }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>E-mail:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->email }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Πόλη:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->city }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>Διεύθυνση:</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                @if(isset($invoice->compnay))
                                                    {{ $invoice->company->city.", " }}{{ $invoice->company->country }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table table-striped table-bordered invoice-table-items">
                                    <tbody>

                                    <tr>
                                        <th>Προϊόν</th>
                                        <th class="text-right">Τιμή</th>
                                        <th class="text-right">Ποσότητα</th>
                                        <th class="text-right">Σύνολο</th>
                                    </tr>
                                    @if($invoice->items)
                                        @foreach($invoice->items as $item)
                                            <tr class="product">
                                                <td>
                                                    <label>{{$item->title}}</label>
                                                </td>
                                                <td class="text-right">
                                                    <label>{{$item->price}} €</label>
                                                </td>
                                                <td class="text-right">
                                                    <label>{{$item->quantity}}</label>
                                                </td>
                                                <td class="text-right product0-total">
                                                    <b>{{$item->rowTotal()}}</b><b>€</b>
                                                    @php
                                                        $total += $item->rowTotal();
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-7 col-md-4 col-sm-12">
                                    <p class="mt-0">Σημειώσεις:</p>
                                    <p>{{$invoice->notes}}</p>
                                </div>
                                <div class="col-lg-5 col-md-8 col-sm-12 ml-auto">
                                    <table class="table table-clear invoice-total">
                                        <tbody>
                                        <tr>
                                            <td class="text-left">
                                                <strong class="text-dark">Υποσύνολο <small>(χωρίς
                                                        έκπτωση)</small></strong>
                                            </td>
                                            <td class="text-right">
                                                <strong
                                                    id="sub_total">{{ $sub_total = number_format($total,2)}}</strong>
                                                <strong>€</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong class="text-dark">Έκπτωση {{$invoice->discount ?? 0}} %</strong>
                                            </td>
                                            <td class="text-right">
                                                <strong
                                                    id="sub_total">{{number_format( ( $invoice->discount/100)*$total,2)}}</strong>
                                                <strong>€</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong class="text-dark">Υποσύνολο <small>(με έκπτωση)</small></strong>
                                            </td>
                                            <td class="text-right">
                                                <strong
                                                    id="sub_total">{{$sub_discount_total = number_format( $total-(( $invoice->discount/100)*$total),2)}}</strong>
                                                <strong>€</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <div>
                                                    <div>
                                                        <strong class="text-dark">Φ.Π.Α. </strong>
                                                        <strong class="text-dark">{{$invoice->fpa}}</strong>
                                                        <label> %</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <strong
                                                    id="final_fpa">{{ $fpa = number_format( (( $invoice->fpa/100)*$sub_discount_total),2)}}</strong>
                                                <strong> €</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong class="text-dark">Σύνολο</strong></td>
                                            <td class="text-right">
                                                <strong
                                                    id="final_total">{{ number_format($fpa+$sub_discount_total,2)}}</strong>
                                                <strong>€</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="row text-center">
                                <div class="col-6">
                                    <label>Έκδοση</label>
                                </div>
                                <div class="col-6">
                                    <label>Παραλαβή</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row options pt-5">
                        <div class="col-sm-12 text-right">
                            <input id="print_invoice" type="submit" class="btn btn-success" value="Εκτύπωση">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
