@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_type', $lng ?? 'el'),
    'formCancel' => route('types', $lng ?? 'el'),
    'formSubmit' => (isset($type))? __('Ενημέρωση') : __('Προσθήκη')
])

@include('types.layout')
