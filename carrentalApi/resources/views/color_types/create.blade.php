@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('message') }}
                    </p>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
                    </div>
                    <form method="POST" action="{{ route('create_color_type', $lng ?? 'el') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            @if(isset($color_type))
                                <input type="hidden" name="id" value="{{$color_type->id}}">
                            @endif

                            <label for="title">{{__('Τίτλος')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($color_type))
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ $color_type->title }}">
                                @else
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title') }}">
                                @endif
                            </div>
                            <label for="international_title">{{__('International Title')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($color_type))
                                    <input type="text" class="form-control" id="international_title" name="international_title"
                                           value="{{ $color_type->international_title }}">
                                @else
                                    <input type="text" class="form-control" id="international_title" name="international_title"
                                           value="{{ old('international_title') }}">
                                @endif
                            </div>

                            <label for="hex_code">{{__('Δεκαεξαδικός κωδικός')}}</label>
                            <div class="input-group mb-3">
                                <input type="color" class="form-control" id="hex_code" name="hex_code"
                                    value="@if(isset($color_type)){{ $color_type->hex_code }}@else{{ old('hex_code') }}@endif">
                            </div>

                        </div>
                        <div class="card-footer">
                            <a href="{{route('color_types', $lng ?? 'el')}}"
                               class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($color_type))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
