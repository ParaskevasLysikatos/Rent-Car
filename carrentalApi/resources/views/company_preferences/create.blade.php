@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ Session::get('message') }}
                    </p>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ __('Εγκατάσταση Εταιρείας') }}
                    </div>
                    <form method="POST" action="{{ route('create_company_preferences', $lng ?? 'el') }}">
                        @csrf
                        @if(isset($preferences))
                            <input type="hidden" name="id"  value="{{ $preferences->id }}"/>
                        @endif
                        <div class="card-body">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#infotab">{{__('Γενικά')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#sequences">{{__('Ακολουθίες')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#print">{{__('Λεκτικά Εκτυπώσεων')}}</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane container active" id="infotab">
                                    <label for="name">{{__('Όνομα Εταιρείας')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="name" name="name" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->name }}" @endif />
                                    </div>

                                    <label for="title">{{__('Διακριτικός Τίτλος')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="title" name="title" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->title }}" @endif />
                                    </div>

                                    <label for="job">{{__('Δραστηριότητα')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="job" name="job" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->job }}" @endif />
                                    </div>

                                    <label for="afm">{{__('ΑΦΜ')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="afm" name="afm" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->afm }}" @endif />
                                    </div>

                                    <label for="doi">{{__('ΔΟΥ')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="doi" name="doi" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->doi }}" @endif />
                                    </div>

                                    <label for="phone">{{__('Τηλέφωνο')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="phone" name="phone" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->phone }}" @endif />
                                    </div>

                                    <label for="email">{{__('Email')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="email" name="email" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->email }}" @endif />
                                    </div>

                                    <label for="website">{{__('Website')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="website" name="website" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->website }}" @endif />
                                    </div>

                                    <label for="mite_number">{{__('Αρ. ΜΗΤΕ')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="mite_number" name="mite_number" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->mite_number }}" @endif />
                                    </div>

                                    <label for="vat">{{__('Ποσοστό ΦΠΑ')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="vat" name="vat" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->vat }}" @endif />
                                    </div>

                                    {{-- <label for="timezone">{{__('Ζώνη ώρας')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="timezone" name="timezone" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->timezone }}" @endif />
                                    </div> --}}

                                    <label for="checkin_free_minutes">{{__('Δωρεάν Λεπτά Ενοικίασης')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="checkin_free_minutes" name="checkin_free_minutes" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->checkin_free_minutes }}" @endif />
                                    </div>

                                    <label for="quote_available_days">{{__('Διαθέσιμες μέρες Προσφοράς')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="quote_available_days" name="quote_available_days" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->quote_available_days }}" @endif />
                                    </div>

                                    <div class="d-flex mb-3">
                                        <label class="pr-3 mb-0" for="show_rental_charges">{{__('Εμφάνιση ποσών στη μίσθωση')}}</label>
                                        <input id="show_rental_charges" name="show_rental_charges" type="checkbox"
                                            @if(isset($preferences) && $preferences->show_rental_charges) checked @endif />
                                    </div>

                                    <label for="station_id">{{ __('Προεπιλεγμένος Σταθμός') }}</label>
                                    <div class="input-group mb-3">
                                        @stationSelector([
                                            'id' => 'station_id',
                                            'name' => 'station_id',
                                            'stations' => isset($preferences) && $preferences->station ? [$preferences->station] : []
                                        ])
                                        @endstationSelector
                                    </div>

                                    <label for="place_id">{{ __('Προεπιλεγμένη Τοποθεσία') }}</label>
                                    <div class="input-group mb-3">
                                        @placesSelector([
                                            'id' => 'place_id',
                                            'name' => 'place',
                                            'option' => isset($preferences) && $preferences->place ? $preferences->place : null,
                                            'text' => isset($preferences) && $preferences->place ? $preferences->place->profile_title : null,
                                            'addBtn' => true,
                                            'depends' => ['stations' => 'station_id']
                                        ])
                                        @endplacesSelector
                                    </div>

                                    <label for="quote_source_id">{{ __('Προεπιλεγμένη Πηγή για Προσφορές') }}</label>
                                    <div class="input-group mb-3">
                                        @sourceSelector([
                                            'id' => 'quote_source_id',
                                            'name' => 'quote_source_id',
                                            'sources' => isset($preferences) && $preferences->quote_source ? [$preferences->quote_source] : [],
                                            'addBtn' => true
                                        ])
                                        @endsourceSelector
                                    </div>

                                    <label for="booking_source_id">{{ __('Προεπιλεγμένη Πηγή για Κρατήσεις') }}</label>
                                    <div class="input-group mb-3">
                                        @sourceSelector([
                                            'id' => 'booking_source_id',
                                            'name' => 'booking_source_id',
                                            'sources' => isset($preferences) && $preferences->booking_source ? [$preferences->booking_source] : [],
                                            'addBtn' => true
                                        ])
                                        @endsourceSelector
                                    </div>

                                    <label for="rental_source_id">{{ __('Προεπιλεγμένη Πηγή για Μισθώσεις') }}</label>
                                    <div class="input-group mb-3">
                                        @sourceSelector([
                                            'id' => 'rental_source_id',
                                            'name' => 'rental_source_id',
                                            'sources' => isset($preferences) && $preferences->rental_source ? [$preferences->rental_source] : [],
                                            'addBtn' => true
                                        ])
                                        @endsourceSelector
                                    </div>
                                </div>
                                <div id="sequences" class="tab-pane container">
                                    <h3>{{ __('Προθέματα') }}</h3>
                                    <label for="quote_prefix">{{__('Προσφορά')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="quote_prefix" name="quote_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->quote_prefix }}" @endif />
                                    </div>

                                    <label for="booking_prefix">{{__('Κράτηση')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="booking_prefix" name="booking_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->booking_prefix }}" @endif />
                                    </div>

                                    <label for="rental_prefix">{{__('Μίσθωση')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="rental_prefix" name="rental_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->rental_prefix }}" @endif />
                                    </div>

                                    <label for="invoice_prefix">{{__('Τιμολόγιο')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="invoice_prefix" name="invoice_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->invoice_prefix }}" @endif />
                                    </div>

                                    <label for="receipt_prefix">{{__('Απόδειξη Λιανικής')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="receipt_prefix" name="receipt_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->receipt_prefix }}" @endif />
                                    </div>

                                    <label for="payment_prefix">{{__('Απόδειξη Είσπραξης')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="payment_prefix" name="payment_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->payment_prefix }}" @endif />
                                    </div>

                                    <label for="pre_auth_prefix">{{__('Απόδειξη Εγγύησης')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="pre_auth_prefix" name="pre_auth_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->pre_auth_prefix }}" @endif />
                                    </div>

                                    <label for="refund_prefix">{{__('Απόδειξη Επιστροφής Χρημάτων')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="refund_prefix" name="refund_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->refund_prefix }}" @endif />
                                    </div>

                                    <label for="refund_pre_auth_prefix">{{__('Απόδειξη Επιστροφής Χρημάτων Εγγύησης')}}</label>
                                    <div class="input-group mb-3">
                                        <input id="refund_pre_auth_prefix" name="refund_pre_auth_prefix" class="form-control"
                                            @if(isset($preferences)) value="{{ $preferences->refund_pre_auth_prefix }}" @endif />
                                    </div>
                                </div>
                                <div id="print" class="tab-pane container">
                                    <h3>Εκτύπωση Μίσθωσης</h3>
                                    <label for="rental_rate_terms">{{__('Οικονομικοί Όροι')}}</label>
                                    <div class="input-group mb-3">
                                        <textarea id="rental_rate_terms" name="rental_rate_terms" class="form-control tinymce-editor">
                                            @if(isset($preferences)) {{ $preferences->rental_rate_terms }} @endif
                                        </textarea>
                                    </div>
                                    <label for="rental_vehicle_condition">{{__('Κατάσταση οχήματος')}}</label>
                                    <div class="input-group mb-3">
                                        <textarea id="rental_vehicle_condition" name="rental_vehicle_condition" class="form-control tinymce-editor">
                                            @if(isset($preferences)) {{ $preferences->rental_vehicle_condition }} @endif
                                        </textarea>
                                    </div>
                                    <label for="rental_gdpr">{{__('GDPR')}}</label>
                                    <div class="input-group mb-3">
                                        <textarea id="rental_gdpr" name="rental_gdpr" class="form-control tinymce-editor">
                                            @if(isset($preferences)) {{ $preferences->rental_gdpr }} @endif
                                        </textarea>
                                    </div>

                                    <h3>Εκτύπωση Τιμολογίου</h3>
                                    <label for="invoice_first_box">{{__('Πρώτο πλαίσιο')}}</label>
                                    <div class="input-group mb-3">
                                        <textarea id="invoice_first_box" name="invoice_first_box" class="form-control tinymce-editor">
                                            @if(isset($preferences)) {{ $preferences->invoice_first_box }} @endif
                                        </textarea>
                                    </div>
                                    <label for="invoice_second_box">{{__('Δεύτερο πλαίσιο')}}</label>
                                    <div class="input-group mb-3">
                                        <textarea id="invoice_second_box" name="invoice_second_box" class="form-control tinymce-editor">
                                            @if(isset($preferences)) {{ $preferences->invoice_second_box }} @endif
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer mt-5">
                            <a href="{{route('contacts', $lng ?? 'el')}}"
                            class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                            <button type="submit" class="btn btn-success float-right">{{ (isset($contact))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'link',
            height: 300,
            // toolbar: 'fontsizeselect',
            fontsize_formats: "8px 10px 12px 14px 18px 24px 36px"
        });
    </script>
@endpush
