<?php

$arr = [
    'url' => 'https://www.carrentalthessaloniki.com',
    'number_decimal_separator' => ',',
    'number_thousands_separator' => '.',
    'php_date_format' => 'd/m/Y',
    'php_time_format' => 'H:i',
    'moment_date_format' => 'DD/MM/YYYY',
    'moment_time_format' => 'HH:mm',
    'datepicker_date_format' => 'DD/MM/YYYY',
    'datepicker_time_format' => 'HH:mm',
    'timezone' => 'Europe/Athens',
    'quote_source' => '1',
    'booking_source' => '1',
    'rental_source' => '1',
    'station' => '1',
    'place' => '2',
    'extra_time' => 60,
    'primary_mail'=> 'e.gjoni123@gmail.com',
    'vat' => 24, //ΦΠΑ
    'fuel_level_fees'=> ['FF' => 1.58, 'SL' =>25.87, 'FE'=>35.98, 'FH'=>69.90],
    'payment_methods'=> ['Μετρητά'=>'cash', 'Πιστωτική κάρτα'=>'credit_card', 'Επιταγή'=>'cheque', 'Τραπεζική κατάθεση'=>'bank_transfer'],
    'months'         => ['Ιανουάριος'=>1, 'Φεβρουάριος'=>2, 'Μάρτιος'=>3, 'Απρίλιος'=>4, 'Μάιος'=>5, 'Ιούνιος'=>6, 'Ιούλιος'=>7, 'Αύγουστος'=>8, 'Σεπτέμβριος'=>9, 'Οκτώβριος'=>10, 'Νοέμβριος'=>11, 'Δεκέμβριος'=>12]
];

$arr['php_datetime_format'] = $arr['php_date_format'] . ' '. $arr['php_time_format'];
$arr['moment_datetime_format'] = $arr['moment_date_format'] . ' '. $arr['moment_time_format'];
$arr['datepicker_datetime_format'] = $arr['datepicker_date_format'] . ' '. $arr['datepicker_time_format'];

return $arr;


?>
