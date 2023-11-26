@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_place', $lng ?? 'el' ),
    'formCancel' => route('places', $lng ?? 'el'),
    'formSubmit' => (isset($place))? __('Ενημέρωση') : __('Προσθήκη')
])

@include('places.layout')
