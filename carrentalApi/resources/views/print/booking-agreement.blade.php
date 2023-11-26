<!DOCTYPE html>
<html>

<head>
    @php
        $colors = \App\PrintingFormsColor::where('brand_id', $model->brand->id)->where('print_form', 'booking')->first();
    @endphp
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/Roboto-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/Roboto-Bold.ttf') }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        body {
            /*font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;*/
            font-family: "Roboto", sans-serif;
            font-size: 10px;
            color: #000000;
            --theme-color: #212E74;
        }

        .invoice-box {
            /* width: 1000px; */
            /* max-width: 800px; */
            margin: auto;
            font-size: 11px;
            line-height: 10px;
            color: #000000;
            padding: 0;
            margin: -25px;
            margin-top: -35px;
            margin-bottom: -55px;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            table-layout:fixed;
        }

        .invoice-box table td {
            /* padding: 5px 2px; */
            padding: 0px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.information table td {
            /*padding-bottom: 40px;*/
        }

        .invoice-box table tr.heading td {
            font-size: 11px;
            background: {{ $colors->primary_background_color ?? '#212E74' }};
            color: {{ $colors->primary_text_color ?? 'white' }};
            font-weight: bold;
            padding: 3px 5px;
            text-transform: uppercase;
            height: auto !important;
            border: none !important;
            text-align: center;
        }

        .heading {
            margin: 0;
            padding: 0;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and __(max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            /*font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;*/
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        hr {
            margin: 40px 0px;
        }

        .signatures td {
            padding: 0px 0 !important;
        }

        .small-text tr, .small-text td {
            border: 1px solid gray;
        }

        .small-text td {
            font-size: 8px;
        }

        .bordered {
            border: 1px solid #D8D8D8;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
            display: block;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        span.center {
            display: block;
        }

        .text-right {
            text-align: right;
        }

        h3 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        table {
            padding: 0 !important;
            margin: 0;
            border-collapse: collapse;
        }

        .table-td {
            padding: 0 !important;
        }

        table tr {
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        table td {
            margin: 0;
        }

        img {
            width: 100%;
        }

        .colored, .sub-title {
            font-size: 10px;
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
        }
        .sub-title {
            width: 100%;
            display: block;
            padding-bottom: 2px;
        }

        .text-light {
            color: {{ $colors->primary_text_color ?? '#212E74' }};
        }

        .terms {
            font-size: 8.1px;
            line-height: 0.7;
            color: #4D4D4F;
            text-align: justify;
        }

        .d-block {
            display: block !important;
        }

        .d-flex {
            /* display: flex !important; */
        }

        .fuel-container {
            /* display: flex; */
            /* flex-wrap: nowrap; */
        }

        .fuel-container img {
            display: inline-block;
            text-align: right;
            width: 50%;
            margin-top: 15px;
            margin-left: 30px;
        }

        .fuel {
            font-size: 14px;
            text-align: center;
            vertical-align: middle;
            display: inline;
            margin-left: 30px;
        }

        .kilometers {
            font-size: 12px;
        }

        .no-borders td {
            border: 0px !important;
        }

        .pr-2 {
            /* padding-right: 8px !important; */
        }

        .second-td {
            display: flex;
            flex-direction: column;

        }

        .align-bottom {
            vertical-align:bottom
        }

        .main_company span {
            margin-left: 10px;
        }

        .d-block {
            display: block;
        }

        .main-title {
            color: {{ $colors->primary_text_color ?? '#212E74' }};
            font-size: 9px;
        }

        .summary-table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .summary-table td, .summary-table th {
            border: 1px solid #D8D8D8 !important;
            border-bottom: none !important;
            border-top: none !important;
            font-weight: normal;
        }

        .summary-table td {
            height: 28px !important;
            line-height: 1;
            vertical-align: middle !important;
            font-size: 11px;
        }

        .summary-head {
            border-collapse: separate !important;
            border-spacing: 2px 4px !important;
            margin: 0 -2px;
        }

        .summary-head td {
            line-height: 1.6;
            height: 20px;
            background: #D8D8D8;
            vertical-align: middle;
        }

        .px {
            padding: 0 5px !important;
        }

        .replace-text {
            height: 16px !important;
        }

        .car {
            /* width: 80%; */
        }

        .comment {
            word-wrap:break-word !important;
            overflow-wrap: break-word !important;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            text-align: center;
            line-height: 20px;
            border: 2px solid {{ $colors->secondary_background_color ?? '#EB5000' }};
            padding: 10px 0;
        }
        .rental {
            font-size: 14px;
            background: #EB5000;
            color: white;
            font-weight: bold;
            text-align: center;
            /* padding: 8px; */
            line-height: 17px;
        }

        .header {
            color: {{ $colors->secondary_text_color ?? '#212E74' }};
            font-size: 11px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .input-box td {
            /* margin-left: 5px; */
            height: 37px;
        }
        .input-box td > * {
            margin-left: 3px;
        }
        .external-table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .bordered-inner td {
            border: 1px solid #D8D8D8;
            border-collapse: collapse;
        }

        .signature-box {
            display: block;
            height: 25px;
            width: 100%;
            background: #D8D8D8;
        }

        .signature-row td {
            line-height: 16px;
        }

        .left {
            text-align: left;
        }

        td.bordered {
            border: 1px solid #D8D8D8 !important;
        }

        .background {
            background: #D8D8D8;
        }

        .summary-table th.border-bottom {
            border-bottom: 0.5px solid #212E74 !important;
        }
        .d-block-inline {
            display: inline-block;
            vertical-align: middle;
        }

        .remarks td {
            height: 68px !important;
        }

        .mite {
            font-size: 9px;
            padding-left: 8px;
        }

        .header table {
            border-collapse: separate;
            border-spacing: 0 4px;
        }

        .summary-final td:nth-child(1), .summary-final td:nth-child(2) {
            padding: 2px 0 !important;
        }

        .summary-final td:nth-child(1) {
            padding-right: 10px !important;
        }
    </style>
</head>

@php
    $main_company = \App\CompanyPreferences::first();
@endphp

<body>
<div class="invoice-box">

    <table cellpadding="0" cellspacing="0">
        <tr class="header">
            <td width="33%" style="padding: 10px 30px; vertical-align: middle">
                <span class="d-block-inline">
                    @if ($model->brand->icon && file_exists(public_path('storage/'.$model->brand->icon)))
                        <img src="{{ asset('storage/'.$model->brand->icon) }}"/>
                    @endif
                </span>
            </td>
            <td style="padding-left: 6px;">
                <table>
                    <tr class="center">
                        <td class="bold"><h3 class="title">{{ 'RESERVATION FORM / '.('ΔΕΛΤΙΟ ΚΡΑΤΗΣΗΣ') }}</h3></td>
                    </tr>
                    <tr class="center background">
                        <td style="padding: 0;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="left" style="padding-left: 8px">@if($model->modification_number > 0) Amendment - {{ $model->modification_number }} @else New Booking @endif</td>
                                        <td class="center">{{ formatDateTime($model->created_at) }}</td>
                                        <td class="right" style="padding-right: 8px">{{ $model->sequence_number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td width="33%">
                <table class="bordered-inner external-table">
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td>Renter Information / {{ __('Στοιχεια Μισθωτη') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Driver / {{ __('Οδηγός') }}</span>
                                            <span class="bold">{{ $model->customer->full_name ?? $model->customer_text }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                            <span class="bold">{{ $model->phone ?? ($model->customer_id ? $model->customer->phone : '') }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td>PickUp Details / {{ __('Στοιχεια Παραδοσης') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">PickUp Date / {{ __('Ημ/νία Παράδοσης') }}</span>
                                            <span class="bold">{{ formatDatetime($model->checkout_datetime) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Station / {{ __('Σταθμός') }}</span>
                                            <span class="bold">{{ $model->checkout_station->profile_title }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                            <span class="bold">{{ $model->checkout_station->phone }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Place / {{ __('Τόπος') }}</span>
                                            <span class="bold">{{ $model->checkout_place_text }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Flight / {{ __('Πτήση') }}</span>
                                            <span class="bold">{{ $model->flight }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box remarks">
                                        <td>
                                            <span class="sub-title">Remarks / {{ __('Παρατηρήσεις') }}</span>
                                            <span class="bold">{{ $model->checkout_comments }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td>DropOff Details / {{ __('Στοιχεια Επιστροφης') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Return Date / {{ __('Ημ/νία Επιστροφής') }}</span>
                                            <span class="bold">{{ formatDatetime($model->checkin_datetime) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Station / {{ __('Σταθμός') }}</span>
                                            <span class="bold">{{ $model->checkin_station->profile_title }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                            <span class="bold">{{ $model->checkin_station->phone }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Place / {{ __('Τόπος') }}</span>
                                            <span class="bold">{{ $model->checkin_place_text }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box remarks">
                                        <td>
                                            <span class="sub-title">Remarks / {{ __('Παρατηρήσεις') }}</span>
                                            <span class="bold">{{ $model->checkin_comments }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td>Invoice to / {{ __('Τιμολογηση σε') }}</td>
                                    </tr>
                                    @php
                                        $varAgent='';
                                    @endphp
                                    @foreach ($model->rental_chargesBasic as $var )
                                        $varAgent=$var->payer;
                                    @endforeach

                                    @if($varAgent=='agent' && $model->agent->company)
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Company / Επωνυμία</span>
                                            <span class="bold">@if($model->agent->company){{ $model->agent->company->title ?? $model->agent->company->name }}@endif</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Occupation / Δραστηριότητα</span>
                                            <span class="bold">{{ $model->agent->company->job ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Billing Address / Διεύθυνση</span>
                                            <span class="bold">{{ $model->agent->company->address ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Tax ID / ΑΦΜ</span>
                                            <span class="bold">{{ $model->agent->company->afm ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Tax Office / Δ.Ο.Υ.</span>
                                            <span class="bold">{{ $model->agent->company->doy ?? '' }}</span>
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Company / Επωνυμία</span>
                                            <span class="bold">@if($model->company_id){{ $model->company->title ?? $model->company->name }}@endif</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Occupation / Δραστηριότητα</span>
                                            <span class="bold">{{ $model->company->job ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Billing Address / Διεύθυνση</span>
                                            <span class="bold">{{ $model->company->address ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Tax ID / ΑΦΜ</span>
                                            <span class="bold">{{ $model->company->afm ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Tax Office / Δ.Ο.Υ.</span>
                                            <span class="bold">{{ $model->company->doy ?? '' }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 6px;">
                <table class="bordered-inner external-table">
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="2">Agent Information / Πληροφοριες Συνεργατη</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Source / Πηγή</span>
                                            <span class="bold">{{ $model->booking_source->profile_title ?? '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agent / Συνεργάτης</span>
                                            <span class="bold">{{ $model->agent->name ?? '' }}
                                                @if($model->sub_account) - {{ $model->sub_account->name ?? $model->sub_account->firstname }}@endif</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Voucher Number / Αρ. Voucher</span>
                                            <span class="bold">{{ $model->agent_voucher ?? '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agent Conf. Nr. / Αρ. Επιβεβ. Πρακτ/ου</span>
                                            <span class="bold">{{ $model->confirmation_number ?? '' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="2">CHARGES INFORMATION / ΣΤΟΙΧΕΙΑ ΧΡΕΩΣΕΩΝ</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Rate Code / {{ __('Τιμοκατάλογος') }}</span>
                                            <span class="bold">Local</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Payment Method / {{ __('Μέθοδος Πληρωμής') }}</span>
                                            <span class="bold">{{ $model->program->getPrintTitle('en').' / '.$model->program->print_title ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">Car Group / Κατ. Οχήμ.</span>
                                                        <span class="bold">{{ $model->type->international_title }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Charge Gr. / Κατ. Χρέωσ.</span>
                                                        <span class="bold">{{ $model->charge_type->international_title }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Excess / Εγγύηση</span>
                                                        <span class="bold">{{ $model->excess }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Days / Ημέρες</span>
                                                        <span class="bold">{{ $model->duration }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="no-borders">
                        <td>
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="6">Charges / {{ __('Χρεωσεις') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="padding: 0 !important">
                                            <table class="summary-head">
                                                <tbody>
                                                    <tr>
                                                        <td width="7%" class="colored center"><span class="d-block-inline center">Qty</span></td>
                                                        {{-- <td width="8%" class="colored center"><span class="d-block-inline center">Code</span></td> --}}
                                                        <td class="colored" style="padding-left: 8px" colspan="2"><span class="d-block-inline">Service or Surcharge Description</span></td>
                                                        <td width="12%" class="colored center"><span class="d-block-inline center">Daily Unit &euro;</span></td>
                                                        <td width="10%" class="colored center"><span class="d-block-inline center">Total &euro;</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="summary-table bordered">
                                                <tbody>
                                                    @php
                                                        $options_without_price = $model->options()->where('quantity', '>', 0)
                                                            ->where('booking_items.gross', 0)->get();
                                                        $options_with_price = $model->options()->where('quantity', '>', 0)
                                                            ->whereNotIn('booking_items.id', $options_without_price->pluck('id'))->get();
                                                        $options = $options_without_price->merge($options_with_price);
                                                        $options_length = $options->count();
                                                    @endphp
                                                    @foreach ($options as $option)
                                                        <tr>
                                                            <td class="center" width="7%"><span class="d-block-inline center">{{ $option->quantity }}</span></td>
                                                            {{-- <td class="center" width="8%"><span class="d-block-inline center">{{ $option->option->code }}</span></td> --}}
                                                            <td style="padding-left: 8px" colspan="2"><span class="d-block-inline">{{ $option->option->getProfileByLanguageId('en')->title ?? '' }} / {{ $option->option->profile_title }}</span></td>
                                                            <td class="center" width="12%"><span class="d-block-inline center">{{ number_format($option->rate, 2) }}</span></td>
                                                            <td class="center" width="10%"><span class="d-block-inline center">{{ number_format($option->gross, 2) }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td width="7%" class="center"><span class="d-block-inline center">1</span></td>
                                                        {{-- <td class="center"><span class="d-block-inline center">KM</span></td> --}}
                                                        <td style="padding-left: 8px" colspan="2">
                                                            <span class="d-block-inline">
                                                                Included @if ($model->distance > 0){{ $model->distance }}@else UNLIMITED @endif km @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                                / Περιλαμβάνει @if ($model->distance > 0){{ $model->distance }}@else ΕΛΕΥΘΕΡΑ @endif χλμ @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                            </span>
                                                        </td>
                                                        <td width="12%"></td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    @if($model->vat_included)
                                                        <tr>
                                                            <td class="center"><span class="d-block-inline center">1</span></td>
                                                            {{-- <td class="center"><span class="d-block-inline center">VAT</span></td> --}}
                                                            <td style="padding-left: 8px" colspan="2"><span class="d-block-inline">Included VAT 24% / Περιλαμβάνει ΦΠΑ 24%</span></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @php $options_length++; @endphp
                                                    @endif
                                                    @for($i = $options_length + 1; $i < 17; $i++)
                                                        <tr>
                                                            <td></td>
                                                            {{-- <td></td> --}}
                                                            <td colspan="2"></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="no-borders summary-final">
                                        <td colspan="5" class="text-right colored" style="padding-right: 10px">
                                            Total Charge / {{ __('Σύνολο Χρέωσης') }}
                                        </td>
                                        <td colspan="1" width="10%" class="center bordered">{{ number_format($model->subcharges_fee, 2) }}</td>
                                    </tr>
                                    @if($model->discount > 0)
                                        <tr class="no-borders summary-final">
                                            <td colspan="6" class="no-paddings text-right" style="padding: 0 !important;">
                                                <table>
                                                    <tr>
                                                        <td class="text-right colored bold" style="padding-right: 10px;">
                                                            Discount / Έκπτωση
                                                        </td>
                                                        <td width="10%" class="center bordered bold">{{ $model->discount }}%</td>
                                                        <td width="10%" class="center bordered bold">{{ number_format($model->subcharges_fee - $model->total,2 ) }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="no-borders summary-final">
                                            <td colspan="5" class="text-right colored bold" style="padding-right: 10px">
                                                Final Total / {{ __('Τελικό Σύνολο') }}
                                            </td>
                                            <td colspan="1" class="center bordered bold">{{ number_format($model->total, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="no-borders summary-final">
                                        <td colspan="5" class="text-right colored" style="padding-right: 10px">
                                            Voucher Value / {{ __('Αξία Voucher') }}
                                        </td>
                                        <td colspan="1" class="center bordered">{{ number_format($model->voucher, 2) }}</td>
                                    </tr>
                                    @if($model->total_paid > 0)
                                        <tr class="no-borders summary-final">
                                            <td colspan="5" class="text-right colored" style="padding-right: 10px">
                                                Advance / Προκαταβολή
                                            </td>
                                            <td colspan="1" class="center bordered">{{ number_format($model->total_paid, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="no-borders summary-final">
                                        <td colspan="5" class="text-right colored bold" style="padding-right: 10px">
                                            Payable on the desk / {{ __('Πληρωτεο στο γραφειο') }}
                                        </td>
                                        <td colspan="1" class="center bordered bold background">{{ number_format($model->total - $model->voucher - $model->total_paid, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bordered">
                <table cellpadding="1" cellspacing="1" width="100%" style='table-layout:fixed;'>
                    <tr class="heading">
                        <td class="center">Remarks / {{ ('Παρατηρησεις') }}</td>
                    </tr>
                    <tr>
                        <td class="comment"><p>{{ $model->comments."\n" }}</p></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 10px;color: grey" class="mite">ΑΡ. ΜΗΤΕ {{ $main_company->mite_number }}</td>
            <td style="text-align: right">
                <span style="text-align: right;float: right; width: 500px" class="center colored">Rental Agent: {{ $model->user->name }}</span>
            </td>
        </tr>
    </table>

</div>

</body>

</html>
