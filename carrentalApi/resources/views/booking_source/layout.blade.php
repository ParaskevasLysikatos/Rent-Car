@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία πηγής'): __('Προσθήκη νέας πηγής') }}
@overwrite

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <label for="title[{{$currentLang->id}}]">{{__($currentLang->title)}}</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Τίτλος')}}</span>
        </div>
        @if(isset($booking_source))
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]"
                value="{{ $booking_source->profile_title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{$currentLang->id}}]"
                name="title[{{$currentLang->id}}]" value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Περιγραφή')}}</span>
        </div>
        @if(isset($booking_source))
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ $booking_source->profile_description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{$currentLang->id}}]"
                    name="description[{{$currentLang->id}}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>

    @if(isset($booking_source))
        <input type="hidden" name="id" value="{{$booking_source->id}}">
    @endif

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Πρόγραμμα')}}</span>
        </div>
        <select name="program_id" id="program_id" class="form-control">
            <option @if(!isset($booking_source) || !$booking_source->program_id) selected @endif label="-"></option>
            @foreach (\App\Program::all() as $program)
                <option @if(isset($booking_source) && $booking_source->program_id ==$program->id ) selected @endif value="{{ $program->id }}">{{ $program->profile_title }}</option>
            @endforeach
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Επωνυμία')}}</span>
        </div>

        <select name="brand_id" id="brand_id" class="form-control">
            <option @if(!isset($booking_source) || !$booking_source->brand_id) selected @endif label="-"></option>
            @foreach(\App\Brand::all() as $brand)
                @if($brand->profile_title)
                    <option value="{{$brand->id}}" @if(isset($booking_source) && $brand->id == $booking_source->brand_id){{'selected'}}@endif>
                        {{ $brand->profile_title }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{__('Συνεργάτης')}}</span>
        </div>
        @php
            $agent_params = [
                'name' => 'agent_id',
                'agents' => isset($booking_source) && $booking_source->agent ? [$booking_source->agent] : []
            ];
            if (isset($booking_source)) {
                $agent_params['query_fields'] = ['booking_source' => $booking_source->id];
            }
        @endphp
        @agentSelector($agent_params)
        @endagentSelector
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
                @if(isset($booking_source))
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]"
                        value="{{ $booking_source->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{$lg->id}}]"
                        name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{__('Περιγραφή')}}</span>
                </div>
                @if(isset($booking_source))
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ $booking_source->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                            name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@overwrite
