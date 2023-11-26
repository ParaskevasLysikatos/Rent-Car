@extends('layouts.multiLingualCreate', [
    'formAction' => route('create_category', $lng ?? 'el' ),
    'formCancel' => route('categories', $lng ?? 'el'),
    'formSubmit' => (isset($category))? __('Ενημέρωση') : __('Προσθήκη')
])

@section('title')
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία κατηγορίας'): __('Προσθήκη νέας κατηγορίας') }}
@endsection

@section('main-fields')
    @php $currentLang = \App\Language::find($lng); @endphp
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span
                class="input-group-text">{{ __('Κατηγορία') }}</span>
        </div>
        @if (isset($category))
            <input type="text" class="form-control" id="title[{{ $currentLang->id }}]"
                name="title[{{ $currentLang->id }}]"
                value="{{ $category->getProfileByLanguageId($currentLang->id)->title ?? '' }}">
        @else
            <input type="text" class="form-control" id="title[{{ $currentLang->id }}]"
                name="title[{{ $currentLang->id }}]"
                value="{{ old('title.'.$currentLang->id) }}">
        @endif

    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span
                class="input-group-text">{{ __('Περιγραφή') }}</span>
        </div>
        @if (isset($category))
            <textarea type="text" class="form-control" id="description[{{ $currentLang->id }}]"
                name="description[{{ $currentLang->id }}]">{{ $category->getProfileByLanguageId($currentLang->id)->description ?? '' }}</textarea>
        @else
            <textarea type="text" class="form-control" id="description[{{ $currentLang->id }}]"
                name="description[{{ $currentLang->id }}]">{{ old('description.'.$currentLang->id) }}</textarea>
        @endif
    </div>

    @if (isset($category))
        <input type="hidden" name="id" value="{{ $category->id }}">
    @endif

    <label
        for="icon">{{ __('Εικονίδιο') }}</label>
    <div class="input-group mb-3">
        <input type="file" class="form-control" id="icon" name="icon">
    </div>
    @if (isset($category) && $category->icon!=null)
        <div class="row">
            <div class="col-sm-3 text-center mb-4 image_{{ $category->id }}">
                <img class="img-thumbnail"
                    src='{{ asset('storage/'.$category->icon) }}'
                    width="150">
                <br /><input class="btn btn-sm btn-warning category_icon"
                    data-id="{{ $category->id }}" type="button"
                    value="{{ __('Διαγραφή') }}" />
            </div>
        </div>
    @endif
@endsection

@section('multilingual-fields')
    @foreach ($lang as $lg)
        @if($lg->id != config('app.locale'))
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span
                        class="input-group-text">{{ __('Κατηγορία') }}</span>
                </div>
                @if (isset($category))
                    <input type="text" class="form-control" id="title[{{ $lg->id }}]"
                        name="title[{{ $lg->id }}]"
                        value="{{ $category->getProfileByLanguageId($lg->id)->title ?? '' }}">
                @else
                    <input type="text" class="form-control" id="title[{{ $lg->id }}]"
                        name="title[{{ $lg->id }}]"
                        value="{{ old('title.'.$lg->id) }}">
                @endif

            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span
                        class="input-group-text">{{ __('Περιγραφή') }}</span>
                </div>
                @if (isset($category))
                    <textarea type="text" class="form-control" id="description[{{ $lg->id }}]"
                        name="description[{{ $lg->id }}]">{{ $category->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                @else
                    <textarea type="text" class="form-control" id="description[{{ $lg->id }}]"
                        name="description[{{ $lg->id }}]">{{ old('description.'.$lg->id) }}</textarea>
                @endif
            </div>
        @endif
    @endforeach
@endsection
