@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
@overwrite

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($type))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]"
                    value="{{ $type->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Περιγραφή')}}</span>
        </div>
        @if(isset($type))
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ $type->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>

    @if(isset($type->id))
        <input type="hidden" name="id" value="{{$type->id}}">
    @endif

    <label for="icon">{{__('Εικονίδιο')}}</label>
    <div class="input-group mb-3">
        <input type="file" class="form-control" id="icon" name="icon">
    </div>

    <label for="images">{{__('Εικόνες')}}</label>
    <div class="input-group mb-3">
        <input type="file" class="form-control" id="images" name="images[]" multiple>
    </div>
    @if(isset($type) && count($type->images)>0)
        <div class="row">
            @foreach($type->images as $file)
                @image(['file' => $file, 'image_link_id' => $type->id, 'image_link_type' => get_class($type)])
                @endimage
            @endforeach
        </div>
    @endif

    <label for="category">{{__('Κατηγορία')}}</label>
    <div class="input-group mb-3">
        <select name="category" id="category" class="form-control">
            <option selected disabled>{{__('Επιλέξτε')}}...</option>
            @foreach($categories as $category)
                @if( (isset($type) && $type->category_id == $category->id) || (old('category') ==  $category->id))
                    <option selected
                            value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')->title}}</option>
                @else
                    <option
                        value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')['title']}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <label for="min_category">{{__('Ελάχιστη κατηγορία')}} ({{__('προαιρετικό')}})</label>
    <div class="input-group mb-3">
        <select name="min_category" id="min_category" class="form-control">
            <option selected disabled>{{__('Επιλέξτε')}}...</option>
            @foreach($categories as $category)
                @if( (isset($type) && $type->min_category == $category->id) || (old('min_category') ==  $category->id))
                    <option selected
                            value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')->title}}</option>
                @else
                    <option
                        value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')['title']}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <label for="max_category">{{__('Μέγιστη κατηγορία')}} ({{__('προαιρετικό')}})</label>
    <div class="input-group mb-3">
        <select name="max_category" id="max_category" class="form-control">
            <option selected disabled>{{__('Επιλέξτε')}}...</option>
            @foreach($categories as $category)
                @if( (isset($type) && $type->max_category == $category->id) || (old('max_category') ==  $category->id))
                    <option selected
                            value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')->title}}</option>
                @else
                    <option
                        value="{{$category->id}}">{{$category->getProfileByLanguageId($lng ?? 'el')['title']}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <label
        for="international_code">{{ __('Διεθνής κωδικός') }}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="international_code" name="international_code" @if(isset($type))value="{{ $type->international_code }}"@else{{ old('international_code') }}@endif>
    </div>

    <label>{{__('Ποσό απαλλαγής')}}</label>
    <br/>
    <div class="input-group mb-3">
        <input class="form-control float-input" name="excess" @if(isset($type)) value="{{ $type->excess }}" @endif />
    </div>


    <label>{{__('Options')}}</label>
    <br/>

    @foreach($options as $option)
        <div class="custom-control custom-control-inline">
            <input type="checkbox" class="custom-control-input" id="options{{$option->id}}"
                    {{ (is_array(old('options')) and in_array($option->id, old('options'))) ? ' checked' : '' }}
                    name="options[]"
                    value="{{$option->id}}" {{(isset($type) && $type->options->contains('id', $option->id))?'checked':''}}>
            <label class="custom-control-label"
                    for="options{{$option->id}}">{{ $option->getProfileByLanguageId($lng ?? 'el')->title ?? __('Μη μεταφρασμένο') }}</label>
        </div>
    @endforeach

    <br/><br/>
    <label>{{__('Χαρακτηριστικά')}}</label>
    <br/>
    @foreach($characteristics as $characteristic)
        <div class="custom-control custom-control-inline">
            <input type="checkbox" class="custom-control-input"
                    {{ (is_array(old('characteristic')) and in_array($characteristic->id, old('characteristic'))) ? ' checked' : '' }}
                    id="characteristic{{$characteristic->id}}"
                    name="characteristic[]"
                    value="{{$characteristic->id}}" {{(isset($type) && $type->characteristics->contains('id', $characteristic->id))?'checked':''}}>
            <label class="custom-control-label"
                    for="characteristic{{$characteristic->id}}">{{ $characteristic->getProfileByLanguageId($lng)->title ?? __('Μη μεταφρασμένο') }}</label>
        </div>
    @endforeach
@overwrite

@section('multilingual-fields')
    @foreach($lang as $lg)
        @if($lg->id != config('app.locale'))
            <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Τίτλος')}}</span>
                </div>
                @if(isset($type))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $type->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($type))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $type->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@overwrite
