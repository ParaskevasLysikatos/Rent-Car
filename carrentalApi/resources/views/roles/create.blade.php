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
                    <form method="POST" action="{{ route('create_role', $lng ?? 'el') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            @if(isset($role))
                                <input type="hidden" name="id" value="{{$role->id}}">
                            @endif

                            <label for="id_name">{{__('ID')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($role))
                                    <input type="text" class="form-control" id="id_name" name="id_name"
                                           value="{{ $role->id }}">
                                @else
                                    <input type="text" class="form-control" id="id_name" name="id_name"
                                           value="{{ old('id_name') }}">
                                @endif
                            </div>
                            <label for="title">{{__('Τίτλος')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($role))
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ $role->title }}">
                                @else
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title') }}">
                                @endif
                            </div>
                            <label for="description">{{__('Περιγραφή')}}</label>
                            <div class="input-group mb-3">
                                @if(isset($role))
                                    <input type="text" class="form-control" id="description" name="description"
                                           value="{{ $role->description }}">
                                @else
                                    <input type="text" class="form-control" id="description" name="description"
                                           value="{{ old('description') }}">
                                @endif
                            </div>

                        </div>
                        <div class="card-footer">
                            <a href="{{route('roles', $lng ?? 'el')}}"
                               class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($role))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
