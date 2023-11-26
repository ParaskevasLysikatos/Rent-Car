@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_station', $lng ?? 'el' ),
    'formCancel' => route('stations', $lng ?? 'el'),
    'formSubmit' => (isset($station))? __('Ενημέρωση') : __('Προσθήκη')
])

@include('stations.layout')
