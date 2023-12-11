<!DOCTYPE html>
<html>

<head>
    @php
        $colors = \App\PrintingFormsColor::where('brand_id', $model->brand->id)->where('print_form', 'quote')->first();
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
            color: {{ $colors->secondary_text_color ?? '#212E74' }};
            border: none !important;
            border-bottom: 2px solid {{ $colors->secondary_text_color ?? '#212E74' }} !important;
            background: {{ $colors->secondary_background_color ?? 'white' }};
            font-weight: bold;
            padding: 3px 0px;
            text-transform: uppercase;
            height: auto !important;
            text-align: left;
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
            border: 1px solid {{ $colors->secondary_background_color ?? '#D8D8D8' }};
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
            color: {{ $colors->placeholder_text_color ?? '#5A5B5D' }};
        }
        .sub-title {
            width: 100%;
            display: block;
            padding: 1px 0 !important;
        }

        .text-light {
            color: {{ $colors->secondary_text_color ?? '#212E74' }};
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

        td.no-border {
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
            color: {{ $colors->secondary_text_color ?? '#212E74' }};
            font-size: 9px;
        }

        .summary-table tr {
            background-color: #F2F2F2;
        }

        .summary-table td, .summary-table th {
            border: none !important;
            font-weight: normal;
        }

        .summary-table td {
            height: 28px !important;
            line-height: 1;
            vertical-align: middle !important;
            font-size: 11px;
        }

        .summary-table {
            border-collapse: separate;
            border-spacing: 2px 0;
        }

        .summary-head {
            border-collapse: separate !important;
            border-spacing: 2px 4px !important;
            margin: 0 -2px;
        }

        .summary-head td {
            color: {{ $colors->placeholder_text_color ?? '#5A5B5D' }};
            vertical-align: middle;
            font-weight: normal;
            font-size: 10px;
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

        .invoice-box table tr.title td {
            border-bottom: 2px solid {{ $colors->primary_text_color ?? '#212E74' }} !important;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            line-height: 20px;
            padding: 0;
            color: {{ $colors->primary_text_color ?? '#212E74' }};
        }

        .title td {
            background: {{ $colors->primary_background_color ?? 'white' }} !important;
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
        }
        .external-table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .bordered-inner td {
            border-bottom: 1.5px solid #D8D8D8;
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
            border: 1px solid {{ $colors->secondary_background_color ?? '#D8D8D8' }} !important;
        }

        .background {
            background: {{ $colors->secondary_background_color ?? '#D8D8D8' }};
        }

        .summary-table th.border-bottom {
            border-bottom: 0.5px solid {{ $colors->secondary_text_color ?? '#212E74' }} !important;
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

        .summary-final td {
            padding: 10px 0 !important;
        }

        .summary-final td:nth-child(1) {
            padding-right: 10px !important;
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
                        <td width="25%">
                            @if ($model->brand->icon && file_exists(public_path('storage/'.$model->brand->icon)))
                                <img src="{{ asset('storage/'.$model->brand->icon) }}"/>
                            @endif
                        </td>
                        <td width="50%" style="padding-left: 30px" class="align-bottom main_company">
                            @if ($main_company)
                                <span class="d-block">{{ $main_company->name }}</span>
                                <span class="d-block">{{ $main_company->title }}</span>
                                @if ($model->checkout_station_id != $main_company->station_id)
                                    <span class="d-block">{{ __('ΥΠΟΚΑΤΑΣΤΗΜΑ').': '.$model->checkout_station->address }} {{ __('Τ.Κ.').' '.$model->checkout_station->zip_code }}</span>
                                @endif
                                <span class="d-block">{{ __('ΕΔΡΑ').': '.$main_company->station->address }} {{ __('Τ.Κ.').' '.$main_company->station->zip_code }}</span>
                                <span class="d-block">{{ __('ΑΦΜ').': '.$main_company->afm }} {{ __('ΔΟΥ').': '.$main_company->doi }}</span>
                                <span class="d-block">{{ __('ΑΡ.ΜΗΤΕ').': '.$main_company->mite_number }}</span>
                            @endif
                        </td>
                        <td>
                            <table style="font-size: 10px; line-height: 7px;">
                                <tr>
                                    <td style="height: 10px;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="d-block right bold"><a style="font-size: 14px;" href="{{ $main_company->website }}">{{ $main_company->website }}</a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="d-block right">{{ __('Τηλ').".: " }}<a href="tel:{{ $main_company->phone }}">{{ $main_company->phone }}</a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="d-block right"><a href="mailto:{{ $main_company->email }}" >{{ $main_company->email }}</a></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top: 12px">
                            <table>
                                <tr class="heading title">
                                    <td style="font-weight: normal; vertical-align: middle; line-height: 18px;">
                                        <span class="d-block left">
                                            {{ formatDateTime($model->created_at) }}
                                        </span>
                                    </td>
                                    <td height="25px" class="center">
                                        <span class="title d-block">{{ 'QUOTATION / '.('ΠΡΟΣΦΟΡΑ') }}</span>
                                    </td>
                                    <td style="font-weight: normal; vertical-align: middle; line-height: 18px;">
                                        <span class="d-block right">
                                            @if($model->manual_agreement){{ $model->manual_agreement }}@else{{ $model->sequence_number }}@endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="center" style="padding: 10px 0 !important;">
                            Valid Until / Ισχύει έως {{ formatDate($model->valid_date) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-right: 4px;">
                <table class="bordered-inner external-table">
                    <tr>
                        <td class="no-border">
                            <table>
                                <tr class="heading">
                                    <td>Requested by / {{ __('Ζητηθηκε απο') }}</td>
                                </tr>
                                @if ($model->company_id)
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Company / {{ __('Εταιρεία') }}</span>
                                            <span class="bold">{{ $model->company->name }}</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="input-box">
                                    <td>
                                        <span class="sub-title">Customer / {{ __('Πελάτης') }}</span>
                                        <span class="bold">{{ $model->customer_text }}</span>
                                    </td>
                                </tr>
                                <tr class="input-box">
                                    <td>
                                        <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                        <span class="bold">{{ $model->customer->phone ?? $model->phone }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border">
                            <table>
                                <tr class="heading">
                                    <td colspan="2">PickUp Details / {{ __('Στοιχεια Παραδοσης') }}</td>
                                </tr>
                                <tr class="input-box">
                                    <td colspan="2">
                                        <span class="sub-title">PickUp Date / {{ __('Ημ/νία Παράδοσης') }}</span>
                                        <span class="bold">{{ formatDatetime($model->checkout_datetime) }}</span>
                                    </td>
                                </tr>
                                <tr class="input-box">
                                    <td colspan="2">
                                        <span class="sub-title">Station / {{ __('Σταθμός') }}</span>
                                        <span class="bold">{{ $model->checkout_station->profile_title }}</span>
                                    </td>
                                </tr>
                                <tr class="input-box">
                                    <td>
                                        <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                        <span class="bold">{{ $model->checkout_station->phone }}</span>
                                    </td>
                                    <td>
                                        <span class="sub-title">Place / {{ __('Τόπος') }}</span>
                                        <span class="bold">{{ $model->checkout_place_text }}</span>
                                    </td>
                                </tr>
                                <tr class="input-box">
                                    <td colspan="2">
                                        <span class="sub-title">Remarks / {{ __('Παρατηρήσεις') }}</span>
                                        <span class="bold">{{ $model->checkout_comments }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 4px;">
                <table class="bordered-inner external-table">
                    <tr>
                        <td class="no-border">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="2">Agent Information / Πληροφοριες Συνεργατη</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Source / Πηγή</span>
                                            <span class="bold">{{ $model->source->profile_title ?? '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Agent / Συνεργάτης</span>
                                            <span class="bold">{{ $model->agent->name ?? '' }}
                                                @if($model->sub_account) - {{ $model->sub_account->name ?? $model->sub_account->firstname }}@endif</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Agent Conf. Nr. / Αρ. Επιβεβ. Πρακτ/ου</span>
                                            <span class="bold">{{ $model->confirmation_number ?? '' }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Duration / Διάρκεια</span>
                                            <span class="bold">{{ $model->duration ?? 0 }} DAYS / ΗΜΕΡΕΣ</span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border">
                            <table>
                                <tbody>
                                    <tr class="heading">
                                        <td colspan="2">DropOff Details / {{ __('Στοιχεια Επιστροφης') }}</td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Return Date / {{ __('Ημ/νία Επιστροφής') }}</span>
                                            <span class="bold">{{ formatDatetime($model->checkin_datetime) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Station / {{ __('Σταθμός') }}</span>
                                            <span class="bold">{{ $model->checkin_station->profile_title }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td>
                                            <span class="sub-title">Phone / {{ __('Τηλέφωνο') }}</span>
                                            <span class="bold">{{ $model->checkin_station->phone }}</span>
                                        </td>
                                        <td>
                                            <span class="sub-title">Place / {{ __('Τόπος') }}</span>
                                            <span class="bold">{{ $model->checkin_place_text }}</span>
                                        </td>
                                    </tr>
                                    <tr class="input-box">
                                        <td colspan="2">
                                            <span class="sub-title">Remarks / {{ __('Παρατηρήσεις') }}</span>
                                            <span class="bold">{{ $model->checkin_comments }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="external-table">
                    <tbody>
                        <tr class="heading">
                            <td colspan="2" style="text-align: center !important;">CHARGES INFORMATION / ΣΤΟΙΧΕΙΑ ΧΡΕΩΣΕΩΝ</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="bordered-inner">
                                    <tr class="input-box">
                                        <td class="center">
                                            <span class="sub-title">Car Group / Κατ. Οχήμ.</span>
                                            <span class="bold">{{ $model->type->category->profile_title ?? '' }} / {{ $model->type->international_code ?? '' }}</span>
                                        </td>
                                        <td class="center">
                                            <span class="sub-title">Group Charged / Κατ. Χρέωσ.</span>
                                            <span class="bold">{{ $model->charge_type->category->profile_title ?? '' }} / {{ $model->type->international_code ?? '' }}</span>
                                        </td>
                                        <td class="center">
                                            <span class="sub-title">Excess / Εγγύηση</span>
                                            <span class="bold">{{ $model->excess }}</span>
                                        </td>
                                        <td class="center">
                                            <span class="sub-title">Days / Ημέρες</span>
                                            <span class="bold">{{ $model->duration }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="summary-head">
                                <table>
                                    <tr>
                                        <td class="center">Qty</td>
                                        <td class="center">Code</td>
                                        <td width="80%" colspan="2" style="padding-left: 8px">Service or Surcharge Description</td>
                                        <td class="center">Total &euro;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 0 !important">
                                <table class="summary-table">
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
                                                <td class="center"><span class="d-block-inline center">{{ $option->quantity }}</span></td>
                                                <td class="center"><span class="d-block-inline center">{{ $option->option->code }}</span></td>
                                                <td colspan="2" width="80%" style="padding-left: 8px"><span class="d-block-inline">{{ $option->option->getProfileByLanguageId('en')->title ?? '' }} / {{ $option->option->profile_title }}</span></td>
                                                <td class="center"><span class="d-block-inline center">{{ number_format($option->gross, 2) }}</span></td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="center"><span class="d-block-inline center">1</span></td>
                                            <td class="center"><span class="d-block-inline center">KM</span></td>
                                            <td colspan="2" width="80%" style="padding-left: 8px">
                                                <span class="d-block-inline">
                                                    Included @if ($model->distance > 0){{ $model->distance }}@else UNLIMITED @endif km @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                    / Περιλαμβάνει @if ($model->distance > 0){{ $model->distance }}@else ΕΛΕΥΘΕΡΑ @endif χλμ @if ($model->distance > 0) - EXTRA km ({{ $model->distance_rate }}/km) @endif
                                                </span>
                                            </td>
                                            </td>
                                            <td class="center"></td>
                                        </tr>
                                        @if($model->vat_included)
                                            <tr>
                                                <td class="center"><span class="d-block-inline center">1</span></td>
                                                <td class="center"><span class="d-block-inline center">VAT</span></td>
                                                <td style="padding-left: 8px" colspan="2"><span class="d-block-inline">Included VAT 24% / Περιλαμβάνει ΦΠΑ 24%</span></td>
                                                <td></td>
                                            </tr>
                                            @php $options_length++; @endphp
                                        @endif
                                        @for($i = $options_length + 1; $i < 16; $i++)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2"></td>
                                                <td></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="10" cellspacing="5" width="100%" style='table-layout:fixed;'>
                    <tr class="heading">
                        <td class="center">Remarks / {{ ('Παρατηρησεις') }}</td>
                    </tr>
                    <tr>
                        <td class="comment" ><p>{{ $model->comments."\n" }}</p></td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr class="summary-final">
                        <td class="text-right bold text-light">
                            Total Charge / {{ __('Σύνολο Χρέωσης') }}
                        </td>
                        <td width="13%" class="center bold bordered">{{ number_format($model->subcharges_fee, 2) }}</td>
                    </tr>
                    @if($model->discount > 0)
                        <tr class="no-borders summary-final">
                            <td colspan="2" class="no-paddings text-right" style="padding: 0 !important;">
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
                        <tr class="summary-final">
                            <td class="text-right bold text-light">
                                Final Total / {{ __('Τελικό Σύνολο') }}
                            </td>
                            <td class="center bold bordered">{{ number_format($model->total, 2) }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

</div>

</body>

</html>
