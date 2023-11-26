@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία τοποθεσίας'): __('Προσθήκη νέας τοποθεσίας') }}
@overwrite

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($place))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]"
                value="{{ $place->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Περιγραφή')}}</span>
        </div>
        @if(isset($place))
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ $place->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>

    @if(isset($place))
        <input type="hidden" name="id" value="{{$place->id}}">
    @endif

    <label for="station">{{__('Σταθμοί')}}</label>
    <div class="input-group mb-3">
        @stationSelector([
            'name' => 'stations',
            'stations' => isset($place) ? $place->stations : [],
            'multiple' => true
        ])
        @endstationSelector
    </div>

    <label for="latitude">{{__('Latitude')}}</label>
    <div class="input-group mb-3">
        @if(isset($place))
            <input type="text" class="coord-input form-control" id="latitude" name="latitude"
                    value="{{ $place->latitude }}">
        @else
            <input type="text" class="coord-input form-control" id="latitude" name="latitude"
                    value="{{ old('latitude') }}">
        @endif
    </div>
    <label for="longitude">{{__('Longitude ')}}</label>
    <div class="input-group mb-3">
        @if(isset($place))
            <input type="text" class="coord-input form-control" id="longitude" name="longitude"
                    value="{{ $place->longitude }}">
        @else
            <input type="text" class="coord-input form-control" id="longitude" name="longitude"
                    value="{{ old('longitude') }}">
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
                @if(isset($place))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $place->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($place))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $place->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@overwrite
