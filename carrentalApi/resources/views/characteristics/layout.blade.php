@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία χαρακτηριστικού'): __('Προσθήκη νέου χαρακτηριστικού') }}
@overwrite

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($characteristic))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]"
                    value="{{ $characteristic->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Περιγραφή')}}</span>
        </div>
        @if(isset($characteristic))
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ $characteristic->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>

    @if(isset($characteristic))
        <input type="hidden" name="id" value="{{$characteristic->id}}">
    @endif

    <label for="icon">{{__('Εικονίδιο')}}</label>
    <div class="input-group mb-3">
        <input type="file" class="form-control" id="icon" name="icon">
    </div>
    @if(isset($characteristic) && $characteristic->icon!=null)
        <div class="row">
            <div class="col-sm-3 text-center mb-4 image_{{$characteristic->id}}">
                <img class="img-thumbnail"
                        src='{{ asset('storage/'.$characteristic->icon) }}'
                        width="150">
                <br/><input class="btn btn-sm btn-warning characteristic_icon"
                            data-id="{{$characteristic->id}}" type="button"
                            value="{{__('Διαγραφή')}}"/>
            </div>
        </div>
    @endif
@overwrite

@section('multilingual-fields')
    @foreach(\App\Language::all() as $lg)
        @if($lg->id != config('app.locale'))
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($characteristic))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $characteristic->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($characteristic))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $characteristic->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@overwrite
