@extends('layouts.multiLingualForm', [
    'formAction' => route('create_booking_source', $lng ?? 'el' ),
    'formCancel' => route('booking_sources', $lng ?? 'el'),
    'formSubmit' => (isset($booking_source))? __('Ενημέρωση') : __('Προσθήκη')
])

@include('booking_source.layout')
