<!DOCTYPE html>
<html>
<head>
    @php
        $colors = \App\PrintingFormsColor::where('brand_id', $model->brand->id)->where('print_form', 'rental')->first();
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
            font-size: 8.1px !important;
            line-height: 1 !important;
            color: #4D4D4F !important;
            text-align: justify !important;
        }

        .terms p {
            padding: 0 !important;
            margin: 0 !important;
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
            line-height: 8px;
        }

        .d-block {
            display: block;
        }

        .main-title {
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
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
            height: 22px !important;
            font-weight: bold;
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
             word-wrap:break-word !important;
            overflow-wrap: break-word !important;
        }

        .title {
            font-size: 20px;
            background: #D8D8D8;
            font-weight: bold;
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            text-align: center;
            line-height: 20px;
            margin-right: 7px;
        }
        .rental {
            font-size: 14px;
            background: {{ $colors->secondary_background_color ?? '#EB5000' }};
            color: {{ $colors->secondary_text_color ?? 'white' }};
            font-weight: bold;
            text-align: center;
            /* padding: 8px; */
            line-height: 17px;
        }

        .header {
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            font-size: 10px;
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
            /* margin-right: 3px; */
        }
        .external-table {
            border-collapse: separate;
            border-spacing: 0 2px;
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
            border-bottom: 0.5px solid {{ $colors->placeholder_text_color ?? '#212E74' }} !important;
        }
        .d-block-inline {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    @php
        $main_company = \App\CompanyPreferences::first();
    @endphp

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <table>
                    <tr class="header">
                        <td width="20%">
                            <table>
                                <tr>
                                    <td>
                                        @if ($model->brand->icon && file_exists(public_path('storage/'.$model->brand->icon)))
                                            <img src="{{ asset('storage/'.$model->brand->icon) }}"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td @if ($model->brand->icon) style="padding-top: 5px;" @endif>
                                        @if($main_company->mite_number) <span class="d-block">{{ __('ΑΡ.ΜΗΤΕ').': '.$main_company->mite_number }}</span>@endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="40%" style="padding-left: 10px" class="align-bottom main_company">
                            @if ($main_company)
                                <span class="d-block">{{ $main_company->name }}</span>
                                <span class="d-block">{{ $main_company->title }}</span>
                                @if ($model->checkout_station_id != $main_company->station_id)
                                    <span class="d-block">{{ __('ΥΠΟΚΑΤΑΣΤΗΜΑ').': '.$model->checkout_station->address }} {{ __('Τ.Κ.').' '.$model->checkout_station->zip_code }}</span>
                                @endif
                                <span class="d-block">{{ __('ΕΔΡΑ').': '.$main_company->station->address }} {{ __('Τ.Κ.').' '.$main_company->station->zip_code }}</span>
                                <span class="d-block">{{ $main_company->station->city.' '.__('ΤΗΛ').': ' }}<a href="tel:{{ $main_company->phone }}">{{ $main_company->phone }}</a></span>
                                <span class="d-block">{{ __('ΑΦΜ').': '.$main_company->afm }} {{ __('ΔΟΥ').': '.$main_company->doi }}</span>
                                <span class="d-block"><a href="https://{{ $main_company->website }}" target="_blank">{{ $main_company->website }}</a>@if($main_company->website && $main_company->email)  -  @endif @if($main_company->email)<a href="mailto:{{ $main_company->email }}" >{{ $main_company->email }}</a>@endif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top: 5px">
                            <table>
                                <tr>
                                    <td height="25px" width="80%" class="left">
                                        <span class="title d-block">{{ 'RENTAL AGREEMENT / '.('ΜΙΣΘΩΤΗΡΙΟ ΣΥΜΒΟΛΑΙΟ') }}</span>
                                    </td>
                                    <td class="rental" width="18%">
                                        @if($model->manual_agreement){{ $model->manual_agreement }}@else{{ $model->sequence_number }}@endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table class="bordered-inner external-table" style="margin-right: 5px">
                    <tr>
                        <td class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="3">Booking Information / {{ __('Στοιχεια Κρατησης') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Reserv. Nr. / {{ __('Αρ. Κράτησης') }}</span>
                                            <span class="bold">{{ $model->booking ? $model->booking->sequence_number : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Booked Grp / {{ __('Grp Κράτησης') }}</span>
                                            <span class="bold">{{ $model->booking ? $model->booking->charge_type->category->profile_title.' - '.$model->booking->charge_type->international_code : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Conf.Nr / {{ __('Αρ. Επιβεβ.') }}</span>
                                            <span class="bold">{{ $model->confirmation_number }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Source / {{ __('Πηγή') }}</span>
                                            <span class="bold">{{ $model->booking_source->profile_title }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agent / {{ __('Συνεργάτης') }}</span>
                                            <span class="bold">{{ $model->agent ? $model->agent->name : '' }}{{ $model->sub_account ? ' - '.($model->sub_account->firstname ?? $model->sub_account->name) : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Voucher Nr / {{ __('Αρ.Voucher') }}</span>
                                            <span class="bold">{{ $model->agent_voucher }}</span>
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
                                        <td colspan="2">Corporate Information / {{ __('Στοιχεια Εταιρειας') }}</td>
                                    </tr>
                                    @php
                                        $company = $model->company ?? null;
                                        if ($model->voucher > 0) {
                                            $company = $model->agent->company ?? null;
                                        }
                                    @endphp
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Company / {{ __('Επωνυμία') }}</span>
                                            <span class="bold">{{ $company ? $company->name : '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Occupation / {{ __('Δραστηριότητα') }}</span>
                                            <span class="bold">{{ $company ? $company->job : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Tax ID-Office / {{ __('Α.Φ.Μ. - Δ.Ο.Y.') }}</span>
                                            <span class="bold">{{ $company ? $company->afm . ' - ' . $company->doy : '' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">Billing Address / {{ __('Διεύθυνση') }}</span>
                                                        <span class="bold">{{ $company ? $company->address : '' }}</span>
                                                    </td>
                                                    <td width="18%">
                                                        <span class="sub-title">Zip Code / {{ __('ΤΚ') }}</span>
                                                        <span class="bold">{{ $company ? $company->zip_code : '' }}</span>
                                                    </td>

                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">City / {{ __('Πόλη') }}</span>
                                            <span class="bold">{{ $company ? $company->city : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Country / {{ __('Χώρα') }}</span>
                                            <span class="bold">{{ $company->country ?? '' }}</span>
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
                                        <td colspan="4">Hirer information / {{ __('Πληροφοριες Οδηγου') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="4">
                                            <span class="sub-title">Full Name / {{ __('Ονοματεπώνυμο') }}</span>
                                            <span class="bold">{{ $model->driver->full_name }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Date of Birth / {{ __('Ημερ. Γέννησης') }}</span>
                                            <span class="bold">{{ formatDate($model->driver->birthday) }}</span>
                                        </td>
                                        <td colspan="2">
                                            <span class="sub-title">Place of Birth / {{ __('Τόπος Γέννησης') }}</span>
                                            <span class="bold">{{ $model->driver->birth_place }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table>
                                                <tbody>
                                                    <tr class="input-box">
                                                        <td width="40%">
                                                            <span class="sub-title">License No / {{ __('Αρ. Διπλώματος') }}</span>
                                                            <span class="bold">{{ $model->driver->licence_number }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Issue / {{ __('Έκδοση') }}</span>
                                                            <span class="bold">{{ formatDate($model->driver->licence_created) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Expiry / {{ __('Λήξη') }}</span>
                                                            <span class="bold">{{ formatDate($model->driver->licence_expire) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Country / {{ __('Χώρα') }}</span>
                                                            <span class="bold">{{ $model->driver->licence_country }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">Id-Passp. / {{ __('Α.Δ.Τ').' / '.__('Διαβατηρίου') }}</span>
                                                            <span class="bold">{{ $model->driver->identification_number }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Issue / {{ __('Έκδοση') }}</span>
                                                            <span class="bold">{{ formatDate($model->driver->identification_created) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Expiry / {{ __('Λήξη') }}</span>
                                                            <span class="bold">{{ formatDate($model->driver->identification_expire) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Country / {{ __('Χώρα') }}</span>
                                                            <span class="bold">{{ $model->driver->identification_country }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Address / {{ __('Διεύθυνση') }}</span>
                                            <span class="bold">{{ $model->driver->address }}</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table>
                                                <tr class="input-box">
                                                    <td width="45%">
                                                        <span class="sub-title">City / {{ __('Πόλη') }}</span>
                                                        <span class="bold">{{ $model->driver->city }}</span>
                                                    </td>
                                                    <td width="20%">
                                                        <span class="sub-title">Zip Code / {{ __('Τ.Κ.') }}</span>
                                                        <span class="bold">{{ $model->driver->zip }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Country / {{ __('Χώρα') }}</span>
                                                        <span class="bold">{{ $model->driver->country }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table>
                                                <tr class="input-box">
                                                    <td width="65%">
                                                        <span class="sub-title">E-mail / {{ __('Ηλ. Διεύθυνση') }}</span>
                                                        <span class="bold">{{ $model->driver->email }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Telephone / {{ __('Τηλέφωνο') }}</span>
                                                        <span class="bold">{{ $model->driver->phone }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @php
                        $driver = new StdClass();
                        if ($model->drivers->count() > 0) {
                            $driver = $model->drivers[0];
                        }
                    @endphp
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="4">Additional Driver Info / {{ __('Στοιχεια Επιπλεον Οδηγων') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="4">
                                            <span class="sub-title">Full Name / {{ __('Ονοματεπώνυμο') }}</span>
                                            <span class="bold">{{ $driver->full_name ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Date of Birth / {{ __('Ημερ. Γέννησης') }}</span>
                                            <span class="bold">{{ formatDate($driver->birthday ?? '') }}</span>
                                        </td>
                                        <td colspan="2">
                                            <span class="sub-title">Place of Birth / {{ __('Τόπος Γέννησης') }}</span>
                                            <span class="bold">{{ $driver->birth_place ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table>
                                                <tbody>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">License No / {{ __('Αρ. Διπλ.') }}</span>
                                                            <span class="bold">{{ $driver->licence_number ?? '' }}</span>
                                                        </td>
                                                        <td width="18%">
                                                            <span class="sub-title">Issue / {{ __('Έκδοση') }}</span>
                                                            <span class="bold">{{ formatDate($driver->licence_created ?? '') }}</span>
                                                        </td>
                                                        <td width="16%">
                                                            <span class="sub-title">Expiry / {{ __('Λήξη') }}</span>
                                                            <span class="bold">{{ formatDate($driver->licence_expire ?? '') }}</span>
                                                        </td>
                                                        <td width="25%">
                                                            <span class="sub-title">Country / {{ __('Χώρα') }}</span>
                                                            <span class="bold">{{ $driver->licence_country ?? '' }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="table-td no-borders">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="4">Rate Terms / {{ __('Οικονομικοι Οροι') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="terms" colspan="4" style="padding: 3px 3px;">
                                            <span class="d-block">
                                                {!! $main_company->rental_rate_terms !!}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="signature-row">
                                        <td width="28%">
                                            <span class="colored" style="padding-left: 3px">Signature / {{ __('Υπογραφή') }}:</span>
                                        </td>
                                        <td width="23%">
                                     @if (file_exists(public_path('../signatures/signatureExcess-'.$model->sequence_number.'.png')))
                                        <img src="{{ asset('../signatures/signatureExcess-'.$model->sequence_number.'.png') }}" width='80px' height='20px'/>
                                        @else
                                            <span class="signature-box"></span>
                                        @endif
                                        </td>
                                        <td width="39%">
                                            <span class="colored right d-block" style="padding-right: 5px">Excess Amount / {{ __('Ποσό Ευθύνης') }}:</span>
                                        </td>
                                        <td width="12%">
                                            <span class="bold">{{ number_format($model->excess, 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr><td style="height: 5px"></td></tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="no-borders">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="3">Vehicle Condition / {{ ('Κατασταση οχηματος') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;padding-left: 5px;" width="35%"><img class="car" src="{{ asset('storage/car.svg') }}"  /></td>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="colored center">Check-out Fuel</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="colored center">{{ __('Καύσιμο έναρξης') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="d-block center"><img style="width:50%" width="50%" src="{{ asset('storage/fuel-level'.$model->checkout_fuel_level.'.jpg') }}" /></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="center bold">{{ $model->checkout_fuel_level }} / 8</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="colored center">{{ __('Check-out Km Έναρξης') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="center bold">{{ $model->checkout_km }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="colored center">Check-in Fuel</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="colored center">{{ __('Καύσιμο επιστροφής') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-block center">
                                                                @if($model->status == \App\Rental::STATUS_CLOSED || $model->status == \App\Rental::STATUS_CHECKED_IN || $model->status == \App\Rental::STATUS_PRE_CHECKED_IN)
                                                                    <img style="width: 50%" src="{{ asset('storage/fuel-level'.$model->checkin_fuel_level.'.jpg') }}" />
                                                                @else
                                                                    <img style="width: 50%" src="{{ asset('storage/fuel_dull.jpg') }}" />
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="center bold">
                                                            @if($model->status == \App\Rental::STATUS_CLOSED || $model->status == \App\Rental::STATUS_CHECKED_IN || $model->status == \App\Rental::STATUS_PRE_CHECKED_IN)
                                                                {{ $model->checkin_fuel_level }}
                                                            @endif
                                                            / 8</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="colored center">{{ __('Check-in Km Επιστροφή') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="center bold">
                                                            @if($model->status == \App\Rental::STATUS_CLOSED || $model->status == \App\Rental::STATUS_CHECKED_IN || $model->status == \App\Rental::STATUS_PRE_CHECKED_IN)
                                                                {{ $model->checkin_km }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 3px;" colspan="3">
                                            <div class="background d-block" style="padding: 2px;">
                                                <span class="terms d-block">
                                                    {!! $main_company->rental_vehicle_condition !!}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="text-align: right">
                <table class="bordered-inner external-table" style="margin-left: 5px">
                    <tr>
                        <td colspan="2" class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="2">Rental Information / {{ __('Στοιχεια Ενοικιασης') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Start / {{ __('Έναρξη') }}</span>
                                            <span class="bold">{{ formatDateTime($model->checkout_datetime) }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agreed Return / {{ __('Συμφ. Επιστροφή') }}</span>
                                            <span class="bold">{{ formatDateTime($model->booked_checkin_datetime) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">PickUp Location / {{ __('Τόπος Παραλαβής') }}</span>
                                            <span class="bold">{{ $model->checkout_station->profile_title .'-'. $model->checkout_place_text }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agreed Return Station / {{ __('Τόπος Επιστροφής') }}</span>
                                            <span class="bold">{{ $model->checkin_station->profile_title .'-'. $model->checkin_place_text }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Station Contact Tel. / {{ __('Τηλ.Επικοιν. Σταθμού') }}</span>
                                            <span class="bold">{{ $model->checkout_station->phone ?? '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Station Contact Tel. / {{ __('Τηλ.Επικοιν. Σταθμού ') }}</span>
                                            <span class="bold">{{ $model->checkin_station->phone ?? '' }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Return Date - Time / {{ __('Ημ. - Ώρα Επιστροφής') }}</span>
                                            <span class="bold">
                                                @if ($model->checkin_datetime != $model->booked_checkin_datetime || $model->status == \App\Rental::STATUS_CLOSED)
                                                    {{ formatDateTime($model->checkin_datetime) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Duration / {{ __('Διάρκεια') }}</span>
                                            <span class="bold">{{ $model->duration }} DAYS/{{ __('ΗΜΕΡΕΣ') }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="4">Vehicle Details / {{ __('Στοιχεια Οχηματος') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="table-td">
                                            <table>
                                                <tbody>
                                                    <tr class="input-box">
                                                        <td>
                                                            <span class="sub-title">Plate Nr / {{ __('Πινακίδα') }}</span>
                                                            <span class="bold">{{ $model->vehicle->licence_plate }}</span>
                                                        </td>
                                                        <td width="25%">
                                                            <span class="sub-title">Model / {{ __('Μοντέλο') }}</span>
                                                            <span class="bold">{{ $model->vehicle->whole_model }}</span>
                                                        </td>
                                                        <td width="15%">
                                                            <span class="sub-title">Given Group</span>
                                                            <span class="bold">{{ $model->type->category->profile_title }} - {{ $model->type->international_code }}</span>
                                                        </td>
                                                        <td width="18%">
                                                            <span class="sub-title">Fuel / {{ __('Καύσιμο') }}</span>
                                                            <span class="bold">{{ $model->vehicle->fuel_type->international_title }}</span>
                                                        </td>
                                                        <td width="18%">
                                                            <span class="sub-title">Color / {{ __('Χρώμα') }}</span>
                                                            <span class="bold">{{ $model->vehicle->color_type->international_title ?? '' }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="4">
                                            <span class="sub-title">Registration Nr / {{ __('Αρ. Κυκλοφορίας') }}</span>
                                            <span class="bold">{{ $model->vehicle->licence_plate }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="sub-title">Given Group</span>
                                            <span class="bold">{{ $model->type->profile_title }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Model / {{ __('Μοντέλο') }}</span>
                                            <span class="bold">{{ $model->vehicle->whole_model }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Fuel Type / {{ __('Καύσιμο') }}</span>
                                            <span class="bold">{{ ' ' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Color / {{ __('Χρώμα') }}</span>
                                            <span class="bold">{{ $model->vehicle->color_exterior }}</span>
                                        </td>
                                    </tr> --}}
                                    <td colspan="5">
                                        <table>
                                            <tbody>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">Check-out Km {{ __('Έναρξη') }}</span>
                                                        <span class="bold">{{ $model->checkout_km }}</span>
                                                    </td>
                                                    <td width="18%">
                                                        <span class="sub-title">Check-out Fuel</span>
                                                        <span class="bold replace-text">{{ $model->checkout_fuel_level ?? '' }} / 8</span>
                                                    </td>
                                                    <td>
                                                        <span class="sub-title">Check-in Km {{ __('Επιστρ.') }}</span>
                                                        <span class="bold">
                                                            @if($model->status == \App\Rental::STATUS_CLOSED || $model->status == \App\Rental::STATUS_CHECKED_IN || $model->status == \App\Rental::STATUS_PRE_CHECKED_IN)
                                                                {{ $model->checkin_km }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td width="17%">
                                                        <span class="sub-title">Check-in Fuel</span>
                                                        <span class="bold replace-text">
                                                            @if($model->status == \App\Rental::STATUS_CLOSED || $model->status == \App\Rental::STATUS_CHECKED_IN || $model->status == \App\Rental::STATUS_PRE_CHECKED_IN)
                                                                {{ $model->checkin_fuel_level }} / 8
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td width="12%">
                                                        <span class="sub-title">km Driven</span>
                                                        <span class="bold">@if ($model->km_driven > 0){{ $model->km_driven }}@endif</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    {{-- <tr>
                                        <td colspan="4" class="table-td">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="40%">
                                                            <span class="sub-title">Check-out Fuel Level / {{ __('Καύσιμο έναρξης') }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="bold">{{ $model->checkout_fuel_level }} / 8</span>
                                                        </td>
                                                        <td width="40%">
                                                            <span class="sub-title">Check-in Fuel Level / {{ __('Καύσιμο επιστροφής') }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="bold">@if($model->checkin_km > 0){{ $model->checkin_fuel_level }} / 8 @endif</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @php
                        $replace = $model->replace();
                        $replaced_car = $model->replacedCar();
                    @endphp
                    <tr>
                        <td colspan="2" class="table-td">
                            <table>
                                <tbody>

                                    <tr class="heading">
                                        <td>Exchange Vehicle / {{ __('Αντικατασταση Οχηματος') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="table-td">
                                            <table>
                                                <tbody>
                                                    <tr class="input-box">
                                                        <td witdh="">
                                                            <span class="sub-title">Plate Nr / {{ __('Πινακίδα') }}</span>
                                                            <span class="bold">{{ $replace->old_vehicle->licence_plate ?? '' }}</span>
                                                        </td>
                                                        <td width="23%">
                                                            <span class="sub-title">Model / {{ __('Μοντέλο') }}</span>
                                                            <span class="bold replace-text">{{ $replace->old_vehicle->model ?? '' }}</span>
                                                        </td>
                                                        <td width="53%">
                                                            <span class="sub-title">Exch.Date - Time / {{ __('Ημ. - Ώρα Αντικατάστασης') }}</span>
                                                            <span class="bold">@if($replace){{ formatDateTime($replace->datetime) ?? '' }}@endif</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table>
                                                <tbody>
                                                    <tr class="input-box">
                                                        <td width="30%">
                                                            <span class="sub-title">Check-out Km {{ __('Έναρξη') }}</span>
                                                            <span class="bold">{{ $replace->old_vehicle_rental_co_km ?? '' }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Check-out Fuel</span>
                                                            <span class="bold replace-text">{{ $replace->old_vehicle_rental_co_fuel_level ?? '' }}</span>
                                                        </td>
                                                        <td width="30%">
                                                            <span class="sub-title">Check-in Km {{ __('Επιστροφή') }}</span>
                                                            <span class="bold">{{ $replace->old_vehicle_rental_ci_km ?? '' }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="sub-title">Check-in Fuel</span>
                                                            <span class="bold">{{ $replace->old_vehicle_rental_ci_fuel_level ?? '' }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="no-borders">
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td colspan="2" class="table-td bordered">
                                        <table class="summary-table">
                                            <tbody>
                                                @php
                                                    $closed = $model->status == \App\Traits\ModelAffectsVehicleStatusInterface::STATUS_CLOSED;
                                                    $closed = false;
                                                @endphp
                                                <tr class="heading">
                                                    <td colspan="@if (config('preferences.show_rental_charges') || $closed){{ 7 }}@else{{ 5 }}@endif">Charges Summary / {{ __('Χρεωσεις') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="border-bottom colored center">Code</th>
                                                    <th class="border-bottom colored pr-2 left" colspan="3" style="padding-left: 5px">Service or Surcharge Description</th>
                                                    @if (config('preferences.show_rental_charges') || $closed)
                                                        <th class="border-bottom colored center">Unit</th>
                                                    @endif
                                                    <th class="border-bottom colored center">Qty x Days</th>
                                                    @if (config('preferences.show_rental_charges') || $closed)
                                                        <th class="border-bottom colored center">Total</th>
                                                    @endif
                                                </tr>
                                                @php
                                                    $options_without_price = $model->options()->where('quantity', '>', 0)
                                                        ->where('booking_items.gross', 0);
                                                    $options_with_price = $model->options()->where('quantity', '>', 0)
                                                        ->whereNotIn('booking_items.id', $options_without_price->pluck('id'));

                                                    // $options_without_price = $options_without_price->where('booking_items.payer', '!=', 'agent');
                                                    // $options_with_price = $options_with_price->where('booking_items.payer', '!=', 'agent');

                                                    $options_without_price = $options_without_price->get();
                                                    $options_with_price = $options_with_price->get();
                                                    $options = $options_without_price->merge($options_with_price);
                                                    $options_length = $options->count();
                                                @endphp
                                                @foreach ($options as $option)
                                                    <tr>
                                                        <td width="9%" class="bold"><span class="d-block-inline center">{{ $option->option->code }}</span></td>
                                                        <td class="bold px" colspan="3" style="height: 12px !important;"><span class="d-block-inline">{{ $option->option->getProfileByLanguageId('en')->title ?? '' }} / {{ $option->option->profile_title }}</span></td>
                                                        @if (config('preferences.show_rental_charges') || $closed)
                                                            <td width="10%"><span class="d-block-inline center">{{ number_format($option->rate, 2) }}</span></td>
                                                        @endif
                                                        <td @if (!config('preferences.show_rental_charges') && !$closed) width="15%" @endif ><span class="d-block-inline center">{{ $option->quantity }}@if($option->option->active_daily_cost) x {{ $option->duration }}@endif</span></td>
                                                        @if (config('preferences.show_rental_charges') || $closed)
                                                            <td width="12%" class="bold"><span class="d-block-inline center">{{ number_format($option->gross, 2) }}</span></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="bold"><span class="d-block-inline center">KM</span></td>
                                                    <td class="bold px" colspan="3" style="height: 12px !important;">
                                                        <span class="d-block-inline">
                                                            Included @if ($model->distance > 0){{ $model->distance }}@else UNLIMITED @endif km @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                            / Περιλαμβάνει @if ($model->distance > 0){{ $model->distance }}@else ΕΛΕΥΘΕΡΑ @endif χλμ @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                        </span>
                                                    </td>
                                                    @if (config('preferences.show_rental_charges') || $closed)
                                                        <td class="right px"><span class="d-block-inline"></span></td>
                                                    @endif
                                                    <td><span class="d-block-inline center"></span></td>
                                                    @if (config('preferences.show_rental_charges') || $closed)
                                                        <td class="bold right px"><span class="d-block-inline"></span></td>
                                                    @endif
                                                </tr>
                                                @php
                                                    $lines_len = config('preferences.show_rental_charges') || $closed ? 14 : 20;
                                                @endphp
                                                @for($i = $options_length + 1; $i < $lines_len; $i++)
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="3"></td>
                                                        @if (config('preferences.show_rental_charges') || $closed)
                                                            <td></td>
                                                        @endif
                                                        <td></td>
                                                        @if (config('preferences.show_rental_charges') || $closed)
                                                            <td></td>
                                                        @endif
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @if (config('preferences.show_rental_charges') || $closed)
                                    @if($model->discount > 0)
                                        <tr class="no-borders">
                                            <td class="text-right colored bold" style="padding-right: 6px">
                                                Charge / {{ __('Σύνολο') }}
                                            </td>
                                            <td class="center bordered bold @if($model->discount == 0) background @endif">{{ number_format($model->subcharges_fee, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="no-paddings" style="padding: 0 !important;">
                                                <table>
                                                    <tr>
                                                        <td class="text-right text-light" style="padding-right: 10px;">
                                                            Discount / Έκπτωση
                                                        </td>
                                                        <td width="13%" class="center bordered bold">{{ $model->discount }}%</td>
                                                        <td width="13%" class="center bordered bold">{{ number_format($model->subcharges_fee - $model->total, 2) }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="no-borders">
                                        <td class="text-right colored" style="padding-right: 6px">
                                            Net Charge / {{ __('Μερικό Σύνολο') }}
                                        </td>
                                        <td width="12%" class="center bordered bold">{{ number_format($model->total_net, 2) }}</td>
                                    </tr>
                                    <tr class="no-borders">
                                        <td class="text-right colored" style="padding-right: 6px">
                                            VAT / {{ __('ΦΠΑ') }} 24%
                                        </td>
                                        <td class="center bordered bold">{{ number_format($model->vat_fee, 2) }}</td>
                                    </tr>
                                    <tr class="no-borders">
                                        <td class="text-right colored bold" style="padding-right: 6px">
                                            Final Total / {{ __('Τελικό Σύνολο') }}
                                        </td>
                                        <td  class="center bordered bold background">{{ number_format($model->total, 2) }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    @if (config('preferences.show_rental_charges') || $closed)
                        <tr>
                            <td colspan="2" class="table-td">
                                <table>
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
                                            <td colspan="2">Payment information / {{ __('Πληροφοριες Εισπραξης') }}</td>
                                        </tr>
                                        <tr class="input-box">
                                            <td>
                                                <span class="sub-title">Cash / {{ __('Μετρητά') }}</span>
                                                <span class="bold center">
                                                    @if (in_array(\App\Payment::CASH_METHOD, $methods)) x @endif
                                                </span>
                                            </td>
                                            <td width="50%">
                                                <span class="sub-title">Amount Paid / {{ __('Πληρωθέν ποσό') }}</span>
                                                <span class="bold right d-block">{{ number_format($model->getTotalPaid(), 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="input-box">
                                            <td>
                                                <span class="sub-title">Voucher</span>
                                                <span class="bold center">@if($model->voucher) x @endif</span>
                                            </td>
                                            <td>
                                                <span class="sub-title">Voucher Value</span>
                                                <span class="bold right d-block">{{ number_format($model->voucher, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="input-box">
                                            <td @if (!config('preferences.show_rental_charges')) colspan="2" @endif>
                                                <span class="sub-title">Credit Card</span>
                                                <span class="bold center">
                                                    @if (in_array(\App\Payment::CARD_METHOD, $methods)) x @endif
                                                </span>
                                            </td>
                                            @if (config('preferences.show_rental_charges'))
                                                <td>
                                                    <span class="sub-title">Balance / {{ __('Υπόλοιπο') }}</span>
                                                    <span class="bold right d-block">{{ number_format($model->total - $model->voucher - $model->total_paid, 2) }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                    @php
                        $pre_auths = $model->pre_auths;
                        $total = 0;
                        $pre_auth_methods = [];
                        foreach ($pre_auths as $pre_auth) {
                            $total += $pre_auth->amount;
                            $pre_auth_methods[] = $pre_auth->method;
                        }
                        $pre_auth_methods = array_unique($pre_auth_methods);
                    @endphp
                    <tr>
                        <td colspan="2" class="table-td">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="3">Pre-authorization - Security deposit / {{ __('Προεγκριση - Εγγυηση') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Cash / {{ __('Μετρητά') }}</span>
                                            <span class="bold center">
                                                @if (in_array(\App\Payment::CASH_METHOD, $pre_auth_methods))
                                                x
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Credit Card</span>
                                            <span class="bold center">
                                                @if (in_array(\App\Payment::CARD_METHOD, $pre_auth_methods))
                                                x
                                                @endif
                                            </span>
                                        </td>
                                        <td width="40%">
                                            <span class="sub-title">Deposit Paid / {{ __('Εγγύηση') }}</span>
                                            <span class="bold">{{ number_format($total,2) }}</span>
                                        </td>
                                        {{-- <td>
                                            <span class="text-light">@if($pre_auths->count() > 0) {{ __($pre_auth->method_title, [], 'en') }} / {{ $pre_auth->method_title }}@endif</span>
                                        </td> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="no-borders">
                        <td colspan="2">
                            <span class="terms d-block">
                                {!! $main_company->rental_gdpr !!}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 5px;">
                <table>
                    <tr class="signature-row">
                        <td width="13%" class="text-light">
                            Driver's Signature:
                        </td>
                        <td width="18%">
                         @if (file_exists(public_path('../signatures/signatureMain-'.$model->sequence_number.'.png')))
                         <img src="{{ asset('../signatures/signatureMain-'.$model->sequence_number.'.png') }}" width='80px' height='20px'/>
                        @else
                            <span class="signature-box"></span>
                        @endif
                        </td>
                        <td width="22%" class="text-light center">
                            Additional Driver's Signature:
                        </td>
                        <td width="18%">
                            @if (file_exists(public_path('../signatures/signatureSecDriver-'.$model->sequence_number.'.png')))
                         <img src="{{ asset('../signatures/signatureSecDriver-'.$model->sequence_number.'.png') }}" width='80px' height='20px'/>
                        @else
                            <span class="signature-box"></span>
                        @endif
                        </td>
                        <td class="text-light center">
                            Rental Agent:
                        </td>
                        <td width="18%">
                            <span class="signature-box center colored">{{ $model->checkout_driver->full_name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right colored" colspan="6">
                            CHARGES SUBJECT TO FINAL AUDIT / {{ __('ΟΙ ΧΡΕΩΣΕΙΣ ΥΠΟΚΕΙΝΤΑΙ ΣΕ ΤΕΛΙΚΟ ΕΛΕΓΧΟ') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>

</body>

</html>
