@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_option', $lng ?? 'el'),
    'formCancel' => route('options', $lng ?? 'el'),
    'formSubmit' => (isset($option))? __('Ενημέρωση') : __('Προσθήκη')
])

@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
@endsection

@section('main-fields')
    <input type="hidden" name="option_type" value="insurances" />
    @php $currentLang = \App\Language::find($lng); @endphp
    <div class="input-group mb-3">
        <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Τίτλος')}}</span>
            </div>
            @if(isset($option))
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]"
                    value="{{ $option->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
            @else
                <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                    name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
            @endif

        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">{{__('Περιγραφή')}}</span>
            </div>
            @if(isset($option))
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ $option->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
            @else
                <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                        name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
            @endif
        </div>
    </div>

    @if(isset($option))
        <input type="hidden" name="id" value="{{$option->id}}">
    @endif

    <label for="icon">{{__('Εικονίδιο')}}</label>
    <div class="input-group mb-3">
        <input type="file" class="form-control" id="icon" name="icon">
    </div>
    @if(isset($option) && $option->icon!=null)
        <div class="row">
            <div class="col-sm-3 text-center mb-4 image_{{$option->id}}">
                <img class="img-thumbnail"
                    src='{{ asset('storage/'.$option->icon) }}'
                    width="150">
                <br/><input class="btn btn-sm btn-warning option_icon"
                            data-id="{{$option->id}}" type="button"
                            value="{{__('Διαγραφή')}}"/>
            </div>
        </div>
    @endif

    <label for="items_max">{{__('Μέγιστη ποσότητα')}}</label>
    <div class="input-group mb-3">
        <select name="items_max" id="items_max" class="form-control">
            <option selected disabled>{{__('Επιλέξτε')}}...</option>
            @for($i = 1; $i<=10; $i++)
                @if(isset($option) && $option->items_max==$i)
                    <option value="{{$option->items_max}}"
                            selected>{{$option->items_max}}</option>
                @else
                    <option value="{{$i}}">{{$i}}</option>
                @endif
            @endfor
        </select>
    </div>

    <label for="cost_daily">{{__('Κόστος')}}</label>
    <div class="input-group mb-3">
        @if(isset($option))
            <input type="text" class="form-control float-input" id="cost_daily" name="cost_daily"
                value="{{ $option->cost_daily }}">
        @else
            <input type="text" class="form-control float-input" id="cost_daily" name="cost_daily"
                value="{{ old('cost_daily') }}">
        @endif
    </div>



    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="form-check-label">
                <input type="checkbox" id="active_daily_cost"
                    name="active_daily_cost" {{ (isset($option) && $option->active_daily_cost==1)?"checked":''  }}> {{__('Κόστος ανά ημέρα')}}
            </label>
        </div>
    </div>

    <div class="cost_max_view {{(isset($option) && $option->active_daily_cost==1)?"":'d-none'}}">
        <label for="cost_max">{{__('Μέγιστο κόστος')}}</label>
        <div class="input-group mb-3">
            @if(isset($option))
                <input type="text" class="form-control float-input" id="cost_max" name="cost_max"
                    value="{{ $option->cost_max }}" {{(isset($option) && $option->active_daily_cost==1)?"":'disabled'}}>
            @else
                <input type="text" class="form-control float-input" id="cost_max" name="cost_max"
                    value="{{ old('cost_max') }}" {{(isset($option) && $option->active_daily_cost==1)?"":'disabled'}}>
            @endif
        </div>
    </div>

    <div class="input-group mb-3">
        <div class="form-check-inline">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input"
                    name="default_on" {{ (isset($option) && $option->default_on==1)?"checked":''  }}>{{ __('Πάντα επιλεγμένο') }}
            </label>
        </div>
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
                @if(isset($option))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $option->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($option))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $option->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@endsection
