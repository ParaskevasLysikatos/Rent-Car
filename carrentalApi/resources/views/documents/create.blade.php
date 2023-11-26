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
                        {{ (isset($_GET['cat_id']))?__('Επεξεργασία τοποθεσίας'): __('Προσθήκη νέας τοποθεσίας') }}
                    </div>
                    <form method="POST" action="{{ route('create_document', $lng ?? 'el' ) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @if(isset($document))
                                <input type="hidden" name="id" value="{{$document->id}}">
                            @endif

                            <label for="type">{{__('Γεωγραφικά Διαμερίσματα')}}</label>
                            <div class="input-group mb-3">
                                <select name="type" id="type" class="form-control">
                                    <option selected disabled>{{__('Επιλέξτε')}}...</option>
                                    @foreach($types as $type)
                                        @if( (isset($document) && $document->type_id == $type->id) || (old('type') ==  $type->id))
                                            <option selected
                                                    value="{{$type->id}}">{{$type->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}</option>
                                        @else
                                            <option
                                                value="{{$type->id}}">{{$type->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο')}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <label for="file">{{__('Εικονίδιο')}}</label>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="file" name="file">
                            </div>
                            @if(isset($document) && $document->path!=null)
                                <div class="row">
                                    <div class="col-sm-3 text-center mb-4 image_{{$document->id}}">
                                        <img class="img-thumbnail"
                                             src='{{ asset('storage/'.$document->path) }}'
                                             width="150">
                                        <br/><input class="btn btn-sm btn-warning document_icon"
                                                    data-id="{{$document->id}}" type="button"
                                                    value="{{__('Διαγραφή')}}"/>
                                    </div>
                                </div>
                            @endif

                            <label for="comments">{{__('Σχόλια')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($document))
                                    <textarea class="form-control" id="comments" name="comments">{{ $document->comments }}</textarea>
                                @else
                                    <textarea class="form-control" id="comments" name="comments">{{ old('comments') }}</textarea>
                                @endif
                            </div>

                        </div>
                        <div class="card-footer">
                            <a href="{{route('documents', $lng ?? 'el')}}"
                               class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($document))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
