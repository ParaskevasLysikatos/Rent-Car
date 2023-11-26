<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <style>
        body {
            /*font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;*/
            font-family: "dejavu sans", serif;
            font-size: 10px;
            color: #000;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            font-size: 12px;
            line-height: 10px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px 2px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 25px;
            line-height: 25px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            /*padding-bottom: 40px;*/
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 5px 10px;
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
            margin: 40px 0px;
        }
        .signatures{
            border: 2px solid gray;
        }
        .signatures td{
            height: 100px !important;
            border: 2px solid gray;
        }
        .small-text tr, .small-text td{
            border:1px solid gray;
        }
        .small-text td{
            font-size: 8px;
        }
    </style>
</head>

<body>
<div class="invoice-box">

        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title" style="background-color: lightslategray;text-align: center;">
                                <img src="https://www.carrentalthessaloniki.com/images/logo_blue_rent_a_car.png"
                                     style="width: 100px !important;padding: 0px; margin: 0px; transform: translateY(30%);">
                            </td>
                            <td class="bordered">
                                Reservation No.:
                                <h3>{{$model->id}}</h3>
                                Travel Agent:
                                @if(!is_null($model->agent_id))
                                <strong>{{$model->agent->name}}</strong>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="2">Pick Up Details</td>
                        </tr>
                        <tr>
                            <td>Station:</td>
                            <td>
                                @if(!is_null($model->checkout_station_id))
                                    @if(!is_null($model->checkout_station->getProfileByLanguageId($lng)))
                                        <b> {{$model->checkout_station->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Place:</td>
                            <td>
                                @if(!is_null($model->checkout_place_id))
                                    @if(!is_null($model->checkout_place->getProfileByLanguageId($lng)))
                                        <b> {{$model->checkout_place->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Date - Time:</td>
                            <td>
                                <b> {{$model->checkout_datetime}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Notes:</td>
                            <td>
                                <b> {{$model->checkout_notes}}</b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="2">Pick Up Details</td>
                        </tr>
                        <tr>
                            <td>Station:</td>
                            <td>
                                @if(!is_null($model->checkin_station_id))
                                    @if(!is_null($model->checkin_station->getProfileByLanguageId($lng)))
                                        <b> {{$model->checkin_station->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Place:</td>
                            <td>
                                @if(!is_null($model->checkin_place_id))
                                    @if(!is_null($model->checkin_place->getProfileByLanguageId($lng)))
                                        <b> {{$model->checkin_place->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Date - Time:</td>
                            <td>
                                <b> {{$model->checkin_datetime}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Notes:</td>
                            <td>
                                <b> {{$model->checkin_notes}}</b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="2">Your Details</td>
                        </tr>
                        <tr>
                            <td>Drivers Names:</td>
                            <td>
                                @foreach($model->drivers as $driver)
                                    {{$driver->getFullName()}}
                                    @if(!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>
                                -
                            </td>
                        </tr>
                        <tr>
                            <td>Country:</td>
                            <td>
                                -
                            </td>
                        </tr>
                        <tr>
                            <td>Telephone:</td>
                            <td>
                                -
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="2">Vehicle Details</td>
                        </tr>
                        <tr>
                            <td>Booked Category:</td>
                            <td>
                                @if(!is_null($model->type_id))
                                    @if(!is_null($model->type->getProfileByLanguageId($lng)))
                                        <b>
                                            @if($model->type->category)
                                                @if(!is_null($model->type->category->getProfileByLanguageId($lng)) )
                                                    {{$model->type->category->getProfileByLanguageId($lng)->title}} -
                                                @endif
                                            @endif
                                            {{$model->type->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Charged Category:</td>
                            <td>
                                @if(!is_null($model->charge_type_id))
                                    @if(!is_null($model->charge_type->getProfileByLanguageId($lng)))
                                        <b>
                                            @if($model->charge_type->category)
                                                @if(!is_null($model->charge_type->category->getProfileByLanguageId($lng)) )
                                                    {{$model->charge_type->category->getProfileByLanguageId($lng)->title}} -
                                                @endif
                                            @endif
                                            {{$model->charge_type->getProfileByLanguageId($lng)->title}}</b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Registration No.:</td>
                            <td>
                                -
                            </td>
                        </tr>
                        <tr>
                            <td>Vehicle Model:</td>
                            <td>
                                {{$model->vehicle->make}} {{$model->vehicle->model }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="3">Charge Summary</td>
                        </tr>
                        <tr>
                            <td><b>code</b></td>
                            <td><b>Service or Surcharge Description</b></td>
                            <td><b>Charge</b></td>
                        </tr>
                        @for($i=0; $i<6; $i++)
                            <tr>
                                <td>#{{$i}}</td>
                                <td>Title {{$i}}</td>
                                <td>{{ $i + rand(20, 200) }} &euro;</td>
                            </tr>
                        @endfor
                    </table>
                </td>
                <td>
                    <table>
                        <tr class="heading">
                            <td colspan="3">Special Equipment</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td colspan="2">Notes</td>
            </tr>
            <tr>
                <td colspan="2">{{$model->notes}}</td>
            </tr>

        </table>

</div>

</body>

</html>
