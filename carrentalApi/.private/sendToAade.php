<?php

use App\Invoice;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$kernel->handle(
  $request = Illuminate\Http\Request::capture()
);

$invoices = Invoice::where('date', '>=', '2021-10-01')->where(function ($q) {
    $q->where('sent_to_aade', '!=', 1)->orWhereNull('sent_to_aade');
})->get();

/** @var Invoice $invoice */
foreach ($invoices as $invoice) {
    $invoice->sendToAade();
}
