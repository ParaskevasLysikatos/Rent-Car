<!DOCTYPE html>
<html>

<head>
    @php
        $form = $model->payment_type;
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
            font-family: "Roboto", sans-serif;
            font-size: 10px;
            color: #000000;
            --theme-color: #212E74;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            font-size: 12px;
            line-height: 15px;
            color: black;
            margin-top: -16px;
        }

        table {
            padding: 0 !important;
            margin: 0;
            border-collapse: collapse;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px 0px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 0;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            /* line-height: 45px; */
            color: #333;
        }

        .invoice-box table tr.information table td {
            /*padding-bottom: 40px;*/
        }

        .invoice-box table tr.title td {
            background: {{ $colors->primary_background_color ?? '#212E74' }};;
            color: {{ $colors->primary_text_color ?? '#212E74' }};
            font-weight: bold;
            padding: 5px 10px;
            text-align: center;
            font-size: 16px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
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
        hr{
            margin: 30px 0px;
            border-color: #eee;
        }
        .signatures td{
            height: 100px !important;
            border: 2px solid #eee;
        }
        .align-left {
            text-align: left !important;
        }

        .bold {
            font-weight: bold;
             word-wrap:break-word !important;
            overflow-wrap: break-word !important;
        }

        .d-block {
            display: block !important;
        }

        .main_company {
            font-size: 10px;
            line-height: 10px;
        }

        .top {
            font-size: 10px;
            color: #555;
        }

        .payment-info {
            color: {{ $colors->secondary_text_color ?? '#212E74' }};
            padding: 4px 14px;
        }

        .payment-info .highlight {
            font-weight: bold;
            color: black;
            font-size: 12px;
        }

        .input-box td {
            /* margin-left: 5px; */
            height: 30px;
            border-bottom: 1.5px solid #cececc;

        }
        .input-box td > * {
            margin-left: 3px;
            margin-right: 3px;
        }
        .input-box .sub-title {
            margin-top: -4px;
            margin-bottom: 6px;
            /* padding-bottom: 6px; */
            border-bottom: 1.2px solid {{ $colors->placeholder_text_color ?? '#212E74' }};
        }

        .colored, .sub-title {
            font-size: 12px;
            color: {{ $colors->placeholder_text_color ?? '#212E74' }};
            font-weight: bold;
        }
        .sub-title {
            width: 100%;
            display: block;
        }

        .table-td {
            padding: 0 !important;
        }

        .notes td {
            height: 50px;
        }

        .amount {
            /* background: #cececc; */
        }

        .text-gray {
            color: #555;
        }

        .title-info td {
            height: 5px;
            background: {{ $colors->secondary_background_color ?? '#cececc' }};
        }


    </style>
</head>

<body>
    @php
        $main_company = \App\CompanyPreferences::first();
    @endphp
    <div class="invoice-box">
        @for($i= 0; $i<2; $i++)
        <table style="margin-top: -36px;margin-bottom: -16px;" cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td width="23%" class="title">
                                <img src="{{ asset('storage/'.$model->brand->icon) }}"
                                     style="width: 200px !important;padding: 0px; margin: 0px;">
                            </td>
                            <td width="38%" style="padding-left: 30px" class="align-left main_company">
                                @if ($main_company)
                                    <span class="d-block">{{ $main_company->name }}</span>
                                    @if ($model->station_id != $main_company->station_id)
                                        <span class="d-block">{{ __('Υποκατάστημα').': '.$model->station->address }} {{ __('Τ.Κ.').' '.$model->station->zip_code }}</span>
                                    @endif
                                    <span class="d-block">{{ __('ΕΔΡΑ').': '.$main_company->station->address }} {{ __('Τ.Κ.').' '.$main_company->station->zip_code }}</span>
                                    <span class="d-block">{{ __('ΑΦΜ').': '.$main_company->afm }} {{ __('ΔΟΥ').': '.$main_company->doi }}</span>
                                    <span class="d-block">{{ __('ΑΡ.ΜΗΤΕ').': '.$main_company->mite_number }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr class="title">
                            <td colspan="2">
                                @if ($model->payment_type == \App\Payment::REFUND_PRE_AUTH_TYPE)
                                    DEPOSIT REFUND RECEIPT / ΑΠΟ∆ΕΙΞΗ ΕΠΙΣΤΡΟΦΗΣ ΧΡΗΜΑΤΩΝ ΕΓΓΥΗΣΗΣ
                                @else
                                    REFUND RECEIPT / ΑΠΟ∆ΕΙΞΗ ΕΠΙΣΤΡΟΦΗΣ ΧΡΗΜΑΤΩΝ
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 2px"></td>
                        </tr>
                        <tr class="title-info">
                            <td class="payment-info" width="50%" style="text-align: center">
                                Date / Ημερ.: <span class="highlight">{{ formatDate($model->created_at) }}</span>
                            </td>
                            <td class="payment-info" width="50%" style="text-align: center">
                                Receipt No/ Αριθμ. Απόδ.: <span class="highlight">{{$model->sequence_number}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 2px">
                                <table>
                                    <tr>
                                        <td width="65%" style="padding-right: 10px">
                                            <table>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">RECEIVED BY / ΕΛΑΒΑ ΑΠΟ</span>
                                                        <span class="bold">
                                                            {{$model->payer->full_name ?? $model->payer->name ?? $model->booking()->customer_text }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr class="input-box amount">
                                                    <td>
                                                        <span class="sub-title">AMOUNT / ΠΟΣΟ</span>
                                                        <span class="bold d-block">
                                                            {{number_format(abs($model->amount),2)}}&euro;
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td width="65%" style="padding-right: 10px">
                                            <table>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">JUSTIFICATION / ΑΙΤΙΟΛΟΓΙΑ</span>
                                                        <span class="bold">
                                                            @if ($model->rental())
                                                                Rental {{ __($model->type_title, [], 'en') }} {{ $model->rental()->sequence_number }} / {{ $model->type_title }} Μίσθωσης {{ $model->rental()->sequence_number }}
                                                            @elseif($model->booking())
                                                                Booking {{ __($model->type_title, [], 'en') }} {{ $model->booking()->sequence_number }} / {{ $model->type_title }} Κράτησης {{ $model->booking()->sequence_number }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr class="input-box">
                                                    <td>
                                                        <span class="sub-title">PAYMENT METHOD / ΤΡΟΠΟΣ ΕΙΣΠΡΑΞΗΣ</span>
                                                        <span class="bold">
                                                            {{__($model->method_title, [], 'en')}} / {{$model->method_title}}
                                                        </span>
                                                    </td>
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

            <tr class="input-box notes">
                <td colspan="2" >
                    <span class="sub-title">NOTES / ΣΗΜΕΙΩΣΕΙΣ</span>
                    <span class="bold">
                        {{$model->comments}}
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding-top: 10px"></td>
            </tr>

            <tr class="signatures" style="text-align: center!important">
                <td class="text-gray">
                    Receiver / Ο Λαβών
                </td>
                <td class="text-gray">
                    Payer / Ο Καταβάλων
                </td>
            </tr>

        </table>
            @if($i==0)
                <hr/>
            @endif
        @endfor

    </div>

</body>

</html>
