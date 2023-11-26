@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_status', $lng ?? 'el'),
    'formCancel' => route('status', $lng ?? 'el'),
    'formSubmit' => (isset($status))? __('Ενημέρωση') : __('Προσθήκη')
])

@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
@endsection

@section('main-fields')
    @if(isset($status))
        <input type="hidden" name="id" value="{{$status->id}}">
    @endif
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($status))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]"
                value="{{ $status->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Περιγραφή')}}</span>
        </div>
        @if(isset($status))
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ $status->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>
@endsection

@section('multilingual-fields')
    @foreach($lang as $lg)
        @if($lg->id != config('app.locale'))
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($status))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $status->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($status))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $status->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@endsection
