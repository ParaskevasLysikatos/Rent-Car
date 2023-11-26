<div class="card-header">
    {{ (isset($brand))?__('Επεξεργασία brand'): __('Προσθήκη νέου brand') }}
</div>
<form method="POST" action="{{ route('create_brand', $lng ?? 'el') }}"
      enctype="multipart/form-data">
    @csrf
    @php $rand = rand(); @endphp
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#brand{{ $rand }}"
            role="tab"
            aria-selected="true">{{__('Γενικά')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#colors{{ $rand }}">{{__('Χρωματισμοί Εκτυπώσεων')}}</a>
        </li>
    </ul>
    <div class="card-body tab-content">
        <div id="brand{{ $rand }}" class="tab-pane fade show active">
            @if(isset($brand))
                <input type="hidden" name="id" value="{{$brand->id}}">
            @endif

            <label for="slug">{{__('Σύνδεσμος')}}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{url('/')}}/brands/</span>
                </div>
                @if(isset($brand))
                    <input type="text" class="form-control" id="slug" name="slug"
                        value="{{ $brand->slug }}">
                @else
                    <input type="text" class="form-control" id="slug" name="slug"
                        value="{{ old('slug') }}">
                @endif
            </div>


            <label for="icon">{{__('Εικονίδιο')}}</label>
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="icon" name="icon">
            </div>
            @if(isset($brand) && $brand->icon!=null)
                <div class="row">
                    <div class="col-sm-3 text-center mb-4 image_{{$brand->id}}">
                        <img class="img-thumbnail"
                            src='{{ asset('storage/'.$brand->icon) }}'
                            width="150">
                        <br/><input class="btn btn-sm btn-warning brand_icon"
                                    data-id="{{$brand->id}}" type="button"
                                    value="{{__('Διαγραφή')}}"/>
                    </div>
                </div>
            @endif


            @foreach(App\Language::all() as $lg)
                <label for="title[{{$lg->id}}]">{{__($lg->title)}}</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{__('Τίτλος')}}</span>
                    </div>
                    @if(isset($brand))
                        <input type="text" class="form-control" id="title[{{$lg->id}}]"
                            name="title[{{$lg->id}}]"
                            value="{{ $brand->getProfileByLanguageId($lg->id)->title ?? '' }}">
                    @else
                        <input type="text" class="form-control" id="title[{{$lg->id}}]"
                            name="title[{{$lg->id}}]" value="{{ old('title.'.$lg->id) }}">
                    @endif

                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{__('Περιγραφή')}}</span>
                    </div>
                    @if(isset($brand))
                        <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                name="description[{{$lg->id}}]">{{ $brand->getProfileByLanguageId($lg->id)->description ?? '' }}</textarea>
                    @else
                        <textarea type="text" class="form-control" id="description[{{$lg->id}}]"
                                name="description[{{$lg->id}}]">{{ old('description.'.$lg->id) }}</textarea>
                    @endif
                </div>
            @endforeach
        </div>
        <div id="colors{{ $rand }}" class="tab-pane fade ">
            @php
                $forms = [
                    'quote' => 'Προσφορά',
                    'booking' => 'Κράτηση',
                    'rental' => 'Μίσθωση',
                    'invoice' => 'Τιμολόγιο',
                    'receipt' => 'Απόδειξη Λιανικής',
                    'payment' => 'Απόδειξη Είσπραξης',
                    'refund' => 'Απόδειξη Επιστροφής Χρημάτων',
                    'pre-auth' => 'Απόδειξη Εγγύησης',
                    'refund-pre-auth' => 'Απόδειξη Επιστροφής Χρημάτων Εγγύησης'
                ];

                $forms_assoc = [];

                if (isset($brand)) {
                    foreach ($brand->printing_forms as $form) {
                        $forms_assoc[$form->print_form] = $form;
                    }
                }
            @endphp
            @foreach ($forms as $form => $form_name)
                <div class="card p-2">
                    <h3>{{ $form_name }}</h3>
                    <label>Placeholder Text Color</label>
                    <input type="color" class="form-control" name="forms[{{ $form }}][placeholder_text_color]" @if(isset($brand) && isset($forms_assoc[$form])) value="{{ $forms_assoc[$form]->placeholder_text_color }}" @endif />
                    <label>Primary Background Color</label>
                    <input type="color" class="form-control" name="forms[{{ $form }}][primary_background_color]" @if(isset($brand) && isset($forms_assoc[$form])) value="{{ $forms_assoc[$form]->primary_background_color }}" @endif />
                    <label>Primary Text Color</label>
                    <input type="color" class="form-control" name="forms[{{ $form }}][primary_text_color]" @if(isset($brand) && isset($forms_assoc[$form])) value="{{ $forms_assoc[$form]->primary_text_color }}" @endif />
                    <label>Secondary Background Color</label>
                    <input type="color" class="form-control" name="forms[{{ $form }}][secondary_background_color]" @if(isset($brand) && isset($forms_assoc[$form])) value="{{ $forms_assoc[$form]->secondary_background_color }}" @endif />
                    <label>Secondary Text Color</label>
                    <input type="color" class="form-control" name="forms[{{ $form }}][secondary_text_color]" @if(isset($brand) && isset($forms_assoc[$form])) value="{{ $forms_assoc[$form]->secondary_text_color }}" @endif />
                </div>
            @endforeach
        </div>
    </div>
    <div class="card-footer">
        <a href="{{route('brands', $lng ?? 'el')}}"
           class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
        <button type="submit" class="btn btn-success float-right">{{ (isset($brand))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
    </div>
</form>
