<!DOCTYPE html>
<html>

<head>
    @php
        $form = $model->type;
        $colors = \App\PrintingFormsColor::where('brand_id', $model->brand->id)->where('print_form', $form)->first();
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
            font-family: "Roboto", sans-serif !important;
            font-size: 10px;
            color: #000000;
            --theme-color: #212E74;
        }

        .invoice-box {
            /* width: 1000px; */
            /* max-width: 800px; */
            margin: auto;
            font-size: 10px;
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

        .invoice-box table tr.heading td, .invoice-box table span.heading {
            font-size: 11px;
            background: {{ $colors->secondary_background_color ?? '#212E74' }};
            border: 0.3px solid {{ $colors->secondary_background_color ?? '#212E74' }};
            color: {{ $colors->secondary_text_color ?? 'white' }};
            font-weight: bold;
            padding: 3px 5px;
            text-transform: uppercase;
            height: auto !important;
            text-align: center;
        }

        .heading {
            margin: 0;
            padding: 0;
        }

        .headings span.heading {
            display: block !important;
            height: 20px !important;
        }

        .title {
            font-size: 20px !important;
            padding: 3px 5px;
            background: {{ $colors->primary_background_color ?? '#D8D8D8' }};
            font-weight: bold;
            color: {{ $colors->primary_text_color ?? '#212E74' }};
            text-align: center;
            line-height: 17px;
            /* padding: 0 !important; */
            height: 20px !important;
        }

        .title > * {
            padding: 0 !important;
            margin: 0 !important;
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
            border: 1px solid {{ $colors->secondary_background_color ?? '#EB5000' }};
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
            font-size: 9px;
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
        }
        .sub-title {
            width: 100%;
            display: block;
        }

        .text-light {
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
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

        .no-border {
            border: none !important;
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
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            font-size: 9px;
        }

        .summary-table {
            border: 0.3px solid {{ $colors->secondary_background_color ?? '#EB5000' }};
        }

        .summary-table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .summary-table td, .summary-table th {
            border: 0.3px solid {{ $colors->secondary_background_color ?? '#EB5000' }} !important;
            border-bottom: none !important;
            border-top: none !important;
            font-weight: normal;
        }

        .summary-table td {
            height: 30px !important;
            line-height: 0.8;
            vertical-align: middle !important;
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
            white-space: pre;
            word-wrap:break-word !important;
            overflow-wrap: break-word !important;
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
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            font-size: 11px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .input-box td {
            /* margin-left: 5px; */
            height: 26px;
        }
        .input-box td > * {
            margin-left: 3px;
            margin-right: 3px;
        }
        .external-table {
            border-collapse: separate;
            border-spacing: 0 2px;
        }

        .bordered-inner td {
            border: 0.3px solid {{ $colors->secondary_background_color ?? '#EB5000' }};
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
            border: 1px solid {{ $colors->secondary_background_color ?? '#EB5000' }} !important;
        }

        .background {
            background: #D8D8D8;
        }

        .background-light {
            background: #F0F0F0;
        }

        .summary-table th.border-bottom {
            border-bottom: 0.5px solid {{ $colors->placeholder_text_color ?? '#212E74' }} !important;
        }
        .d-block-inline {
            display: inline-block;
            vertical-align: middle;
        }

        .vertical-middle {
            vertical-align: middle;
        }

        .summary-head {
            /* border-collapse: separate !important;
            border-spacing: 2px 4px !important; */
            margin: 0 -2px;
        }

        .summary-head td {
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            border: 0.3px solid {{ $colors->secondary_background_color ?? '#EB5000' }};
            vertical-align: middle;
            font-weight: normal;
            font-size: 10px;
            padding: 3px 8px !important;
        }

        .summary-final td:nth-child(1), .summary-final td:nth-child(2) {
            padding: 6px 0 !important;
        }

        .summary-final td:nth-child(1) {
            padding-right: 10px !important;
        }
    </style>
</head>

<body>
@php
    $company = $model->invoicee_type == \App\Company::class ? $model->invoicee : ($model->invoicee_type == \App\Agent::class ? $model->invoicee->company : '');
@endphp
@php
    $main_company = \App\CompanyPreferences::first();
@endphp
<div class="invoice-box">

        <table cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2" style="padding-bottom: 6px;">
                    <table>
                        <tr class="header">
                            <td width="50%" class="center vertical-middle">
                                @if ($model->brand->icon && file_exists(public_path('storage/'.$model->brand->icon)))
                                    <img class="d-block-inline" style="max-width: 180px; transform: translateY(15%);" src="{{ asset('storage/'.$model->brand->icon) }}"/>
                                @endif
                            </td>
                            <td width="50%" class="align-bottom main_company">
                                @if ($main_company)
                                    <span class="d-block">{{ $main_company->name }}</span>
                                    <span class="d-block">{{ $main_company->title }}</span>
                                    @if ($model->station_id && $model->station_id != $main_company->station_id)
                                        <span class="d-block">{{ __('ΥΠΟΚΑΤΑΣΤΗΜΑ').': '.$model->station->address }} {{ __('Τ.Κ.').' '.$model->station->zip_code }}</span>
                                    @endif
                                    <span class="d-block">{{ __('ΕΔΡΑ').': '.$main_company->station->address }} {{ __('Τ.Κ.').' '.$main_company->station->zip_code }}</span>
                                    <span class="d-block">{{ __('ΑΦΜ').': '.$main_company->afm }} {{ __('ΔΟΥ').': '.$main_company->doi }}</span>
                                    <span class="d-block">{{ __('Τηλ').".: " }}<a href="tel:{{ $main_company->phone }}">{{ $main_company->phone }}</a>, <a href="mailto:{{ $main_company->email }}" >{{ $main_company->email }}</a>, <a href="{{ $main_company->website }}">{{ $main_company->website }}</a></span>
                                    <span class="d-block">{{ __('ΑΡ.ΜΗΤΕ').': '.$main_company->mite_number }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding-top: 6px;">
                                <table>
                                    <tr class="headings">
                                        <td class="center" width="50%">
                                            <span class="title d-block">SERVICES RENTERED RECEIPT / ΑΠΟ∆ΕΙΞΗ ΠΑΡΟΧΗΣ ΥΠΗΡΕΣΙΩΝ</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="center">
                                            <table>
                                                <tr>
                                                    <td width="80%" class="center" style="padding-right: 6px"><span style="padding: 4px" class="d-block background">Date / Ημερομηνία: <span class="bold">{{ formatDateTime($model->created_at) }}</span></span></td>
                                                    <td class="center" style="padding-left: 6px"><span style="padding: 4px" class="d-block background bold">{{ $model->sequence_number }}</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <table class="bordered-inner external-table" style="margin-right: 5px">
                                        <tr>
                                            <td class="table-td">
                                                <table>
                                                    <tbody>
                                                        <tr class="heading">
                                                            <td colspan="4">Bill to / Στοιχεια Πελατη</td>
                                                        </tr>
                                                        <tr class="input-box">
                                                            <td colspan="4">
                                                                <span class="sub-title">Customer - Driver / Ονοματεπώνυμο πελάτη - οδηγού</span>
                                                                <span class="bold">
                                                                    {{$model->instance->name}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <table>
                                                                    <tr class="input-box">
                                                                        <td width="50%">
                                                                            <span class="sub-title">Date of Birth / Ημ. Γέννησης</span>
                                                                            <span class="bold">{{ formatDate($model->instance->birth_date) }}</span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="sub-title">Place of Birth / Τόπος Γέννησης</span>
                                                                            <span class="bold">{{$model->instance->birth_place}}</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr class="input-box">
                                                            <td>
                                                                <span class="sub-title">License No / Αρ. Διπλώματος</span>
                                                                <span class="bold">{{$model->instance->licence_number}}</span>
                                                            </td>
                                                            <td width="18%">
                                                                <span class="sub-title">Issue / Έκδοση</span>
                                                                <span class="bold">{{formatDate($model->instance->licence_created)}}</span>
                                                            </td>
                                                            <td width="17%">
                                                                <span class="sub-title">Expiry / Λήξη</span>
                                                                <span class="bold">{{formatDate($model->instance->licence_expire)}}</span>
                                                            </td>
                                                            <td width="22%">
                                                                <span class="sub-title">Country / Χώρα</span>
                                                                <span class="bold">{{$model->instance->licence_country}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="input-box">
                                                            <td>
                                                                <span class="sub-title">Id-Passp. / Α.Δ.Τ. / Διαβατηρίου</span>
                                                                <span class="bold">{{$model->instance->identification_number}}</span>
                                                            </td>
                                                            <td width="18%">
                                                                <span class="sub-title">Issue / Έκδοση</span>
                                                                <span class="bold">{{formatDate($model->instance->identification_created)}}</span>
                                                            </td>
                                                            <td width="17%">
                                                                <span class="sub-title">Expiry / Λήξη</span>
                                                                <span class="bold">{{formatDate($model->instance->identification_expire)}}</span>
                                                            </td>
                                                            <td width="22%">
                                                                <span class="sub-title">Country / Χώρα</span>
                                                                <span class="bold">{{$model->instance->identification_country}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="input-box">
                                                            <td colspan="4">
                                                                <span class="sub-title">Address / Διεύθυνση</span>
                                                                <span class="bold">
                                                                    {{$model->instance->address}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <table>
                                                                    <tbody>
                                                                        <tr class="input-box">
                                                                            <td>
                                                                                <span class="sub-title">City / Πόλη</span>
                                                                                <span class="bold">
                                                                                    {{$model->instance->city}}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="sub-title">Zip Code / Τ.Κ.</span>
                                                                                <span class="bold">
                                                                                    {{$model->instance->zip_code}}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="sub-title">Country / Χώρα</span>
                                                                                <span class="bold">
                                                                                    {{$model->instance->country}}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <table>
                                                                    <tr class="input-box">
                                                                        <td>
                                                                            <span class="sub-title">E-mail / Ηλ. Διεύθυνση</span>
                                                                            <span class="bold">
                                                                                {{$model->instance->email}}
                                                                            </span>
                                                                        </td>
                                                                        <td width="30%">
                                                                            <span class="sub-title">Telephone / Τηλέφωνο</span>
                                                                            <span class="bold">
                                                                                {{$model->instance->phone}}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="bordered-inner external-table" style="margin-left: 5px">
                                        <tr>
                                            <td class="table-td">
                                                <table>
                                                    <tr class="heading">
                                                        <td colspan="3">
                                                            Rental information / Πληροφοριες Ενοικιασης
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <table>
                                                                <tbody>
                                                                    <tr class="input-box">
                                                                        <td width="38%">
                                                                            <span class="sub-title">Rental Agr. Nr. / Αρ. Μισθωτ.</span>
                                                                            <span class="bold">{{$model->instance->rental_sequence_number}}</span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="sub-title">Res. Nr. / Αρ. Κράτησης</span>
                                                                            <span class="bold">{{$model->instance->booking_sequence_number}}</span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="sub-title">Voucher Nr / Αρ.Voucher</span>
                                                                            <span class="bold">{{$model->instance->voucher_no}}</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr class="input-box">
                                                        <td width="32%">
                                                            <span class="sub-title">Start Day / Ημ. έναρξης</span>
                                                            <span class="bold">{{formatDateTime($model->instance->checkout_datetime)}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Return Date / Ημ. Επιστροφής</span>
                                                            <span class="bold">{{formatDateTime($model->instance->checkin_datetime)}}</span>
                                                        </td>
                                                        <td width="28%">
                                                            <span class="sub-title">Duration / Διάρκεια</span>
                                                            <span class="bold">{{$model->instance->days}} DAYS/ΗΜΕΡΕΣ</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <table>
                                                                <tbody>
                                                                    <tr class="input-box">
                                                                        <td>
                                                                            <span class="sub-title">Service Station / Σταθμός Εξυπηρέτησης</span>
                                                                            <span class="bold">{{$model->instance->checkout_station}}</span>
                                                                        </td>
                                                                        <td width="40%">
                                                                            <span class="sub-title">Station Tel. / Τηλ. Σταθμού</span>
                                                                            <span class="bold">{{$model->instance->checkout_station_phone}}</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                    </tr>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">Nr Plate / Αρ. Πινακίδας</span>
                                                            <span class="bold">{{$model->instance->licence_plate}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Group</span>
                                                            <span class="bold">{{$model->instance->group}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Model / Μοντέλο</span>
                                                            <span class="bold">{{$model->instance->vehicle_whole_model}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">Check-out Km Έναρξης</span>
                                                            <span class="bold">{{$model->instance->checkout_km}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Check-in Kms Επιστροφής</span>
                                                            <span class="bold">{{$model->instance->checkin_km}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Driven km Διανυθέντα</span>
                                                            @if($model->instance->checkin_km)
                                                                <span class="bold">{{$model->instance->checkin_km - $model->instance->checkout_km}}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <table>
                                                                <tbody>
                                                                    <tr class="input-box">
                                                                        <td>
                                                                            <span class="sub-title">Καύσιμα παράδοσης / Checkout Fuel</span>
                                                                            <span class="bold">{{$model->instance->checkout_fuel_level}}/8</span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="sub-title">Καύσιμα παραλαβής / Checkin Fuel</span>
                                                                            <span class="bold">{{$model->instance->checkin_fuel_level}}/8</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr class="input-box">
                                                        <td colspan="3">
                                                            <span class="sub-title">Remarks / Παρατηρήσεις</span>
                                                            <span class="bold">{{$model->instance->comments}}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </tr>

            <tr>
                <td colspan="2" style="padding-top: 5px">
                    <table class="external-table">
                        <tbody>
                            <tr class="heading">
                                <td colspan="2">charges summary / χρεωσεις</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="summary-head" style="padding: 4px 0;">
                                    <table>
                                        <tr>
                                            <td class="center">Code</td>
                                            <td width="70%" style="padding-left: 8px">Service or Surcharge Description</td>
                                            <td class="center">Net Total &euro;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table class="summary-table">
                                        @php
                                            $total =0;
                                            $items = $model->items;
                                            $items_length = $items->count();
                                        @endphp
                                        @foreach($items as $item)
                                            <tr>
                                                <td class="center"><span class="d-block-inline center">{{ $item->code }}</span></td>
                                                <td width="70%" style="padding-left: 8px"><span class="d-block-inline">{{ $item->title }}</span></td>
                                                @php $price = $item->price/1.24; @endphp
                                                <td class="center"><label class="item_price"><span class="d-block-inline center">{{ number_format(round($price, 2), 2) }}</span></label></td>
                                            </tr>
                                            @php $total += $price; @endphp
                                        @endforeach
                                        @for($i = $items_length + 1; $i < 14; $i++)
                                            <tr>
                                                <td></td>
                                                <td width="70%"></td>
                                                <td></td>
                                            </tr>
                                        @endfor
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 6px;"></td>
            </tr>
            <tr>
                <td class="background-light center" style="vertical-align: middle; height: 120px;"">
                    <span class="d-block-inline center">
                        {!! $main_company->invoice_first_box !!}
                    </span>
                </td>
                <td>
                    <table>
                        <tr class="summary-final">
                            <td class="text-right text-light">
                                Net Value / {{ __('Μερικό Σύνολο') }}
                            </td>
                            <td width="13%" class="center bordered">{{ number_format(round($total, 2), 2) }}</td>
                        </tr>

                        @if ($model->discount > 0)
                            <tr class="summary-final">

                                <td colspan="2" class="no-paddings" style="padding: 0 !important;">
                                    <table>
                                        <tr>
                                            <td class="text-right text-light">
                                                Discount / Έκπτωση
                                            </td>
                                            <td width="13%" class="center bordered bold">{{ $model->discount }}%</td>
                                            <td width="13%" class="center bordered bold">{{ number_format($total - $model->sub_discount_total, 2) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="summary-final">
                                <td class="text-right bold text-light">
                                    Total Charge / {{ __('Γενικό Σύνολο') }}
                                </td>
                                <td class="center bordered background bold">{{ number_format(round($model->sub_discount_total, 2), 2) }}</td>
                            </tr>
                        @endif

                        <tr class="summary-final">
                            <td class="text-right text-light">
                                VAT / {{ __('ΦΠΑ') }} 24%
                            </td>
                            <td class="center bordered">{{ $model->final_fpa }}</td>
                        </tr>

                        <tr class="summary-final">
                            <td class="text-right bold text-light">
                                Total Charge / {{ __('Γενικό Σύνολο') }}
                            </td>
                            <td class="center bordered background bold">{{ number_format($model->final_total, 2) }}</td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table>
                        <tr>
                            <td width="50%" class="background-light center" style="vertical-align: middle; height: 100px;">
                                <span class="d-block-inline center">
                                    {!! $main_company->invoice_second_box !!}
                                </span>
                            </td>
                            <td class="table-td" style="padding-left: 10px !important">
                                <table class="bordered-inner">
                                    <tbody>
                                        @php
                                            $payments = $model->collections;
                                            $methods = [];
                                            foreach ($payments as $payment) {
                                                $methods[] = $payment->method;
                                            }
                                            $methods = array_unique($methods);
                                        @endphp
                                        <tr class="heading">
                                            <td colspan="2">Payment method / {{ __('Τροπος πληρωμης') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">Cash / {{ __('Μετρητά') }}</span>
                                                            <span class="bold center">
                                                                @if (in_array(\App\Payment::CASH_METHOD, $methods)) x @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Credit Card</span>
                                                            <span class="bold center">
                                                                @if (in_array(\App\Payment::CARD_METHOD, $methods)) x @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Amount Paid / {{ __('Πληρωθέν ποσό') }}</span>
                                                            <span class="bold right d-block">{{ number_format($model->getTotalPaid(), 2) }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="input-box">
                                            <td>
                                                <span class="sub-title">Voucher</span>
                                                <span class="bold">@if($model->voucher) x @endif</span>
                                            </td>
                                            <td>
                                                <span class="sub-title">Voucher Value</span>
                                                <span class="bold right d-block">{{ number_format($model->voucher, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="input-box">
                                            <td>
                                                <span class="bold d-block center" style="padding-top: 8px;">
                                                    @if($model->final_total - $model->voucher - $model->getTotalPaid() == 0)
                                                        ΕΞΟΦΛΗΘΗ
                                                    @else
                                                        ΕΠΙ ΠΙΣΤΩΣΕΙ
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span class="sub-title">Balance / {{ __('Υπόλοιπο') }}</span>
                                                <span class="bold right d-block">{{ number_format($model->final_total - $model->voucher - $model->getTotalPaid(), 2) }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

</div>

</body>

</html>
