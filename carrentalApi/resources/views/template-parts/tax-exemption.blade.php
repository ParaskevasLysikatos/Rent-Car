@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Υπολογισμός ΦΠΑ') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="input-group text-right">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text min-w-40">{{__('Ποσοστό ΦΠΑ')}}</span>
                                    </div>
                                    <input type="text" class="form-control text-right" value="24" id="fpa">
                                    <div class="input-group-append">
                                        <span class="input-group-text min-w-40">%</span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">{{__('Τιμή χωρίς ΦΠΑ')}}</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control text-right"  id="subtotal">
                                    <div class="input-group-append">
                                        <span class="input-group-text min-w-40">€</span>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-info btn-xs float-right" id="subtotalCalc">{{__('Υπολογισμός')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">{{__('Ποσό ΦΠΑ')}}</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control text-right" id="fpaPrice">
                                    <div class="input-group-append">
                                        <span class="input-group-text min-w-40">€</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text min-w-40">{{__('Τιμή με ΦΠΑ')}}</span>
                                    </div>
                                        <input type="number" step="0.01" class="form-control text-right" id="total">
                                    <div class="input-group-append">
                                        <span class="input-group-text min-w-40">€</span>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-info btn-xs float-right" id="totalCalc">{{__('Υπολογισμός')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-secondary" id="resetTaxfields"> {{ __('Καθαρισμός') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
