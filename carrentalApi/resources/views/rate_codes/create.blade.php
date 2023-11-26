@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_rate_code', $lng ?? 'el' ),
    'formCancel' => route('rate_codes', $lng ?? 'el'),
    'formSubmit' => (isset($rate_code))? __('Ενημέρωση') : __('Προσθήκη')
])

@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία τοποθεσίας'): __('Προσθήκη νέου Rate Code') }}
@endsection

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <div class="input-group mb-3">
        <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Τίτλος')}}</span>
            </div>
            @if(isset($rate_code))
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]"
                    value="{{ $rate_code->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
            @else
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
            @endif

        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Περιγραφή')}}</span>
            </div>
            @if(isset($rate_code))
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ $rate_code->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
            @else
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
            @endif
        </div>
    </div>

    @if(isset($rate_code))
        <input type="hidden" name="id" value="{{$rate_code->id}}">
    @endif
@endsection

@section('multilingual-fields')
    @foreach($lang as $lg)
        @if($lg->id != config('app.locale'))
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($rate_code))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $rate_code->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($rate_code))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $rate_code->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@endsection
