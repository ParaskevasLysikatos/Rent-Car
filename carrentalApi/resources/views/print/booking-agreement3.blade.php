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

        hr {
            margin: 40px 0px;
        }

        .signatures {
            border: 2px solid gray;
        }

        .signatures td {
            height: 100px !important;
            border: 2px solid gray;
        }

        .small-text tr, .small-text td {
            border: 1px solid gray;
        }

        .small-text td {
            font-size: 8px;
        }
    </style>
</head>

<body>
<div class="invoice-box">

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">
                <table>
                    <tr class="heading">
                        <td colspan="2">Rental Agreement / Μισθωτήριο {{$model->id}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="3">Στοιχεία Εταιρείας</td>
                    </tr>
                    <tr>
                        <td colspan="2">Όνομα εταιρείας</td>
                        <td colspan="1">Α.Φ.Μ.</td>
                    </tr>
                    <tr>
                        <td>Διεύθηνση</td>
                        <td>Πόλη</td>
                        <td>Χώρα</td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="3">Στοιχεία Κράτησης</td>
                    </tr>
                    <tr>
                        <td colspan="2">Agent/Συνεργάτης</td>
                        <td colspan="1">Reference</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="3">Πληροφορίες Οδηγού</td>
                    </tr>
                    <tr>
                        <td colspan="3">Ονοματεπώνυμο</td>
                    </tr>
                    <tr>
                        <td colspan="1">Ημερ. Γέννησης</td>
                        <td colspan="2">Τόπος Γέννησης</td>
                    </tr>
                    <tr>
                        <td>Α.Δ.Τ.-Διαβατηρίου</td>
                        <td>Ημ.Έκδωσης</td>
                        <td>Ημ.Λήξης</td>
                    </tr>
                    <tr>
                        <td>Α.Δ.Τ.-Διπλώματος</td>
                        <td>Ημ.Έκδωσης</td>
                        <td>Ημ.Λήξης</td>
                    </tr>
                    <tr>
                        <td colspan="3">Διεύθυνση</td>
                    </tr>
                    <tr>
                        <td>Πόλη</td>
                        <td>Τ.Κ.</td>
                        <td>Χώρα</td>
                    </tr>
                    <tr>
                        <td colspan="2">E-mail</td>
                        <td colspan="1">Τηλέφωνο</td>
                    </tr>
                    <tr>
                        <td colspan="3">Διεύθηνση Κατοικίας</td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="3">Στοιχεία Ενοικίασης</td>
                    </tr>
                    <tr>
                        <td>Ημ. & 'Ωρα έναρξης</td>
                        <td>Ημ. & 'Ωρα λήξης</td>
                        <td>Διάρκεια ενοικίασης</td>
                    </tr>
                    <tr>
                        <td colspan="3">Παράταση έως</td>
                    </tr>
                    <tr>
                        <td colspan="2">Κατάστημα παραλαβής</td>
                        <td colspan="1">Τηλ. Επικοινωνίας</td>
                    </tr>
                    <tr>
                        <td colspan="2">Κατάστημα παράδοσης</td>
                        <td colspan="1">Τηλ. Επικοινωνίας</td>
                    </tr>
                    <tr class="heading">
                        <td colspan="3">Εξοπλιμός</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="4">Στοιχεία Επιπλέον Οδηγών</td>
                    </tr>
                    <tr>
                        <td>Ονοματεπώνυμο</td>
                        <td>Αρ.Διπλώματος</td>
                        <td>Ημ.Εκδοσης</td>
                        <td>Ημ.Λήξης</td>
                    </tr>
                    <tr class="heading">
                        <td colspan="4">Στοιχεία Ενοικιαζόμενου Οχήματος</td>
                    </tr>
                    <tr>
                        <td colspan="1">Αρ.Κυκλοφορίας</td>
                        <td colspan="2">Μοντέλο</td>
                        <td colspan="1">Χρώμα</td>
                    </tr>
                    <tr>
                        <td colspan="2">Καύσιμο</td>
                        <td colspan="2">Group</td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr class="heading">
                        <td colspan="3">Χρεώσεις</td>
                    </tr>
                    <tr>
                        <td><strong>code</strong></td>
                        <td><strong>Service</strong></td>
                        <td><strong>Charge</strong></td>
                    </tr>
                    @for($i=1;$i<5;$i++)
                        <tr>
                            <td>#{{$i}}</td>
                            <td>Item {{$i}}</td>
                            <td>{{ rand(100,200) }} &euro;</td>
                        </tr>
                    @endfor
                </table>
            </td>
        </tr>
    </table>

</div>

</body>

</html>
