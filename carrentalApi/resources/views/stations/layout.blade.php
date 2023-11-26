@section('title')
    {{ (isset($station))?__('Επεξεργασία σταθμού'): __('Προσθήκη νέου σταθμού') }}
@overwrite

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <div class="input-group mb-3">
        <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Τίτλος')}}</span>
            </div>
            @if(isset($station))
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]"
                    value="{{ $station->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
            @else
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
            @endif

        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Περιγραφή')}}</span>
            </div>
            @if(isset($station))
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ $station->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
            @else
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
            @endif
        </div>
    </div>

    @if(isset($station))
        <input type="hidden" name="id" value="{{$station->id}}">
    @endif

    <label for="code">{{__('Κωδικός')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="code" name="code"
        @if(isset($station))
            value="{{ $station->code }}"
        @else
            value="{{ old('code') }}"
        @endif
        >
    </div>

    <label for="address">{{__('Διεύθυνση')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="address" name="address"
        @if(isset($station))
            value="{{ $station->address }}"
        @else
            value="{{ old('address') }}"
        @endif
        >
    </div>

    <label for="city">{{__('Πόλη')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="city" name="city"
        @if(isset($station))
            value="{{ $station->city }}"
        @else
            value="{{ old('city') }}"
        @endif
        >
    </div>

    <label for="country">{{__('Χώρα')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="country" name="country"
        @if(isset($station))
            value="{{ $station->country }}"
        @else
            value="{{ old('country') }}"
        @endif
        >
    </div>

    <label for="zip_code">{{__('ΤΚ')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="zip_code" name="zip_code"
        @if(isset($station))
            value="{{ $station->zip_code }}"
        @else
            value="{{ old('zip_code') }}"
        @endif
        >
    </div>

    <label for="phone">{{__('Τηλέφωνο')}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control coord-input" id="phone" name="phone"
        @if(isset($station))
            value="{{ $station->phone }}"
        @else
            value="{{ old('phone') }}"
        @endif
        >
    </div>

    <label for="location">{{__('Γεωγραφικά Διαμερίσματα')}}</label>
    <div class="input-group mb-3">
        <select name="location" id="location" class="form-control">
            <option selected disabled>{{__('Επιλέξτε')}}...</option>
            @foreach(App\Location::all() as $location)
                @if( (isset($station) && $station->location_id == $location->id) || (old('category') ==  $location->id))
                    <option selected
                            value="{{$location->id}}">{{$location->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}</option>
                @else
                    <option
                        value="{{$location->id}}">{{$location->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο')}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <label for="latitude">{{__('Latitude')}}</label>
    <div class="input-group mb-3">
        @if(isset($station))
            <input type="text" class="form-control coord-input" id="latitude" name="latitude"
                    value="{{ $station->latitude }}">
        @else
            <input type="text" class="form-control coord-input" id="latitude" name="latitude"
                    value="{{ old('latitude') }}">
        @endif
    </div>
    <label for="longitude">{{__('Longitude ')}}</label>
    <div class="input-group mb-3">
        @if(isset($station))
            <input type="text" class="form-control coord-input" id="longitude" name="longitude"
                    value="{{ $station->longitude }}">
        @else
            <input type="text" class="form-control coord-input" id="longitude" name="longitude"
                    value="{{ old('longitude') }}">
        @endif
    </div>
    <label for="aade_branch">{{__('ΑΑΔΕ Branch')}}</label>
    <div class="input-group mb-3">
        @if(isset($station))
            <input type="text" class="form-control coord-input" id="aade_branch" name="aade_branch"
                    value="{{ $station->aade_branch }}" required>
        @else
            <input type="text" class="form-control coord-input" id="aade_branch" name="aade_branch"
                    value="{{ old('aade_branch') }}" required>
        @endif
    </div>
@overwrite

@section('multilingual-fields')
    @foreach(App\Language::all() as $lg)
        @if($lg->id != config('app.locale'))
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($station))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $station->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($station))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $station->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@overwrite
