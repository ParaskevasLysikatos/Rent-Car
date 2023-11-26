@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_characteristic', $lng ?? 'el'),
    'formCancel' => route('characteristics', $lng ?? 'el'),
    'formSubmit' => (isset($characteristic))? __('Ενημέρωση') : __('Προσθήκη')
])

@include('characteristics.layout')
