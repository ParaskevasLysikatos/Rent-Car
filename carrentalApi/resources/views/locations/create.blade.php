
@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_location', $lng ?? 'el' ),
    'formCancel' => route('locations', $lng ?? 'el'),
    'formSubmit' => (isset($location))? __('Ενημέρωση') : __('Προσθήκη')
])

@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία τοποθεσίας'): __('Προσθήκη νέας τοποθεσίας') }}
@endsection

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($location))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]"
                value="{{ $location->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif
    </div>

    @if(isset($location))
        <input type="hidden" name="id" value="{{$location->id}}">
    @endif
    <label for="latitude">{{__('Latitude')}}</label>
    <div class="input-group mb-3">
        @if(isset($location))
            <input type="text" class="form-control coord-input" id="latitude" name="latitude"
                value="{{ $location->latitude }}">
        @else
            <input type="text" class="form-control coord-input" id="latitude" name="latitude"
                value="{{ old('slug') }}">
        @endif
    </div>
    <label for="slug">{{__('Longitude ')}}</label>
    <div class="input-group mb-3">
        @if(isset($location))
            <input type="text" class="form-control coord-input" id="longitude" name="longitude"
                value="{{ $location->longitude }}">
        @else
            <input type="text" class="form-control coord-input" id="longitude" name="longitude"
                value="{{ old('slug') }}">
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
                @if(isset($location))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $location->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
        @endif
    @endforeach
@endsection
