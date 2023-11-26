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
                    <form method="POST" action="{{ route('create_documentType', $lng ?? 'el') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            @if(isset($documentType))
                                <input type="hidden" name="id" value="{{$documentType->id}}">
                            @endif
                                @foreach($lang as $lg)
                                    <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{__('Τίτλος')}}</span>
                                        </div>
                                        @if(isset($documentType))
                                            <input type="text" class="form-control" id="title[{{$lg->id}}]"
                                                   name="title[{{$lg->id}}]"
                                                   value="{{ $documentType->getProfileByLanguageId($lg->id)->title ?? '' }}">
                                        @else
                                            <input type="text" class="form-control" id="title[{{$lg->id}}]"
                                                   name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                                        @endif

                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{__('Περιγραφή')}}</span>
                                        </div>
                                        @if(isset($documentType))
                                            <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                                      name="description[{{$lg->id}}]">{{ $documentType->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                                        @else
                                            <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                                      name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                                        @endif
                                    </div>
                                @endforeach

                        </div>
                        <div class="card-footer">
                            <a href="{{route('documentTypes', $lng ?? 'el')}}"
                               class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($documentType))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
