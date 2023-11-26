@extends('layouts.app')

@section('title')
    {{ (isset($quote) && !$duplicate)?__('Επεξεργασία Προσφοράς'): __('Προσθήκη Προσφοράς') }}
@endsection

@section('content')
    <style>
        .selectr-selected {
            border: 1px solid #e9ecef !important;
        }
    </style>
    @php
        $mb = "mb-2";
    @endphp
    <div class="container-fluid booking-container quote-container">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('message') }}
            </p>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex mb-2">
                    <h1 class="mr-3">
                        @yield('title')
                    </h1>
                    @if(isset($quote) && !$duplicate)
                        <div class="fixed-bottom-menu">
                            <button type="submit" id="print_files" class="print_file float-left btn-sm btn-info fa fa-print">{{__('Εκτύπωση')}}</button>
                            @if($quote->booking)
                                <a href="{{ route('edit_booking_view', ['locale' => $lng, 'cat_id' => $quote->booking->id]) }}" class="btn btn-sm btn-dark ml-3 mb-3">{{ __('Δες την αντίστοιχη κράτηση') }}</a>
                            @else
                                <form id="create_next-form" action="{{route('create_booking_view', ['locale' => $lng])}}" method="get">
                                    <input type="hidden" name="quote_id" value="{{ $quote->id }}" />
                                    <input type="submit" id="create_next-btn" class="btn btn-sm btn-dark ml-3" value="{{__('Δημιουργία Κράτησης')}}" />
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <form id="booking-form" action="{{route('create_quote', $lng)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="create_next" name="create_next" />
            @if(isset($quote) && !$duplicate)
                <input type="number" name="id" value="{{$quote->id}}" hidden/>
            @endif
            <div class="row">

                <div class="col-sm-8">
                    <div class="row no-gutters">
                        <div class="card col-12">
                            <div class="card-header">
                                <h3 class="float-left">{{ _('Γενικές Πληροφορίες') }}</h3>
                                <div class="d-flex float-right">
                                    <input type="text" class="form-control" value="@if(isset($quote)){{$quote->sequence_number}}@if($quote->modification_number > 0) - {{ $quote->modification_number }}@endif @endif"
                                        disabled>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                                        <div class="p-2 flex-fill">
                                            <label class="mr-3">* {{_('Ημ. Δημιουργίας')}}:</label>
                                            <input type="text" class="datepicker form-control mb-2"
                                                value="@if(isset($quote)){{ formatDate($quote->created_at) }}@else{{ formatDate(now()) }}@endif"
                                                disabled>
                                        </div>
                                    @endif
                                    <div class="p-2 flex-fill">
                                        <label class="mr-3">* {{_('Πηγή')}}:</label>
                                        @php $defSource = \App\BookingSource::find(config('preferences.quote_source_id')); @endphp
                                        @sourceSelector([
                                            'id' => 'booking_source',
                                            'name' => 'source_id',
                                            'extra_fields' => ['brand_id','agent_id'],
                                            'sources' => isset($quote) && $quote->source ? [$quote->source] : [$defSource],
                                            'addBtn' => true,
                                            'required' => true
                                        ])
                                        @endsourceSelector
                                    </div>
                                    <div class="p-2 flex-fill">
                                        <label class="mr-3">* {{_('Επωνυμία')}}:</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            @foreach(App\Brand::all() as $brand)
                                                <option
                                                    value="{{$brand->id}}" @if((isset($quote) && $brand->id == $quote->brand_id) ||
                                                    (!isset($quote) && $defSource->brand_id == $brand->id)){{'selected'}}@endif>
                                                    @if(!is_null($brand->getProfileByLanguageId($lng)))
                                                        {{$brand->getProfileByLanguageId($lng)->title}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                                        <div class="p-2 flex-fill" id="create_by">
                                            <label class="mr-3">* {{_('Δημ. Από')}}:</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                @if(isset($quote))
                                                    <option selected
                                                            value="{{ $quote->user->id }}">{{ $quote->user->name }}</option>
                                                @else
                                                    <option value="{{Auth::id()}}">{{Auth::user()->name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                    @endif
                                    <div class="p-2 flex-fill" id="create_by">
                                        <label class="mr-3">* {{_('Διαθέσιμο έως')}}:</label>
                                        <input type="text" class="datepicker form-control mb-2" name="valid_date"
                                            value="@if(isset($quote)){{ formatDate($quote->valid_date) }}@else{{ formatDate(now()->addDays(config('preferences.quote_available_days'))) }}@endif">
                                    </div>
                                    <div class="p-2 flex-fill tags-container">
                                        <label class="mr-3">* {{_('Ετικέτες')}}:</label>
                                        @tags([
                                            'tags' => isset($quote) && $quote->tags ? $quote->tags : [],
                                            'query_fields' => ['type' => 'App\\Quote,App\\Booking,App\\Rental']
                                        ])
                                        @endtags
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card col-6">


                            <div class="card-header">
                                <h3 class="float-left m-0">{{ _('Πληροφορίες πελάτη') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Εταιρία') }}</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1" id="company_block">
                                                @companySelector([
                                                    'name' => 'company_id',
                                                    'companies' => isset($quote) && $quote->company ? [$quote->company] : [],
                                                    'addBtn' => true,
                                                    'editBtn' => true
                                                ])
                                                @endcompanySelector
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="quote_drivers" id="quote_drivers">
                                    <div class="d-flex" id="driver1">
                                        <div class="p-2 flex-fill">
                                            <div class="input-group input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ _('Οδηγός') }}:</span>
                                                </div>
                                                <div class="input-group-prepend flex-grow-1" id="drivers_block">
                                                    @typingSelector([
                                                        'id' => 'customer_id',
                                                        'name' => 'customer',
                                                        'searchUrl' => 'searchDriverUrl',
                                                        'value_field' => 'id',
                                                        'text_field' => 'full_name',
                                                        'option' => isset($quote) && $quote->customer ? $quote->customer : null,
                                                        'text' => isset($quote) ? $quote->customer_text : null,
                                                        'extra_fields' => ['phone']
                                                    ])
                                                    @endtypingSelector
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="quote_driver">
                                    <div class="d-flex" id="driver1">
                                        <div class="p-2 flex-fill">
                                            <div class="input-group input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ _('Τηλέφωνο') }}:</span>
                                                </div>
                                                <div class="input-group-prepend flex-grow-1">
                                                    <input id="phone" name="phone" pattern="\+?[0-9]{0,}" class="form-control" type="tel" @if(isset($quote)) value="{{ $quote->phone }}" @endif />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card col-6">

                            <div class="card-header">
                                <h3 class="float-left m-0">{{ _('Πληροφορίες συνεργάτη') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Συνεργάτης') }}</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1" id="agent_block">
                                                @agentSelector([
                                                    'id' => 'agent_id',
                                                    'name' => 'agent_id',
                                                    'extra_fields' => ['brand_id'],
                                                    'agents' => isset($quote) && $quote->agent ? [$quote->agent] : (!isset($quote) && $defSource->agent ? [$defSource->agent] : []),
                                                    'addBtn' => true,
                                                    'editBtn' => true,
                                                    'depends' => ['booking_source' => 'booking_source']
                                                ])
                                                @endagentSelector
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Πωλητής') }}</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1" id="agent_block">
                                                @subaccountSelector([
                                                    'id' => 'sub_account_id',
                                                    'name' => 'sub_account_id',
                                                    'sub_accounts' => isset($quote) && $quote->sub_account_normalized ? [$quote->sub_account_normalized] : [],
                                                    'addBtn' => true,
                                                    'editBtn' => true,
                                                    'depends' => ['parent_agent' => 'agent_id'],
                                                    'searchUrl' => 'searchSubAccountWithAgentUrl'
                                                ])
                                                @endsubaccountSelector
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="p-2 flex-fill d-none">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Πρόγραμμα') }}:</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1">
                                                <select name="program_id" id="program_id" class="form-control">
                                                    @foreach (\App\Program::all() as $program)
                                                        <option @if((isset($quote) && $quote->program_id ==$program->id) || (!isset($quote) && $defSource->program_id == $program->id )) selected @endif value="{{ $program->id }}">{{ $program->profile_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 flex-fill d-none">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Conf #') }}:</span>
                                            </div>
                                            <input type="text" class="form-control" name="confirmation_number" id="confirmation_number"
                                            @if(isset($quote) && $quote->confirmation_number)value="{{ $quote->confirmation_number }}"@endif/>
                                        </div>
                                    </div>
                                    <div class="p-2 flex-fill d-none">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Voucher') }}:</span>
                                            </div>
                                            <input type="text" class="form-control" name="agent_voucher" id="agent_voucher"
                                                @if(isset($quote) && $quote->agent_voucher)value="{{ $quote->agent_voucher }}"@endif/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card col-6">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Πληροφορίες Παράδοσης') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-inline {{$mb}}">
                                    <div class="transfer_info_title checkout_datetime text-right">
                                        <label
                                            class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        @datetimepicker([
                                            'id' => 'checkout_datetime',
                                            'name' => 'checkout_datetime',
                                            'datetime' => isset($quote) ? $quote->checkout_datetime : now(),
                                            'required' => true
                                        ])
                                        @enddatetimepicker
                                    </div>
                                </div>

                                <div class="form-inline {{$mb}}">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3"
                                            for="checkout_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        @stationSelector([
                                            'id' => 'checkout_station_id',
                                            'name' => 'checkout_station_id',
                                            'stations' => isset($quote) && $quote->checkout_station ? [$quote->checkout_station] : [],
                                            'required' => true
                                        ])
                                        @endstationSelector
                                    </div>
                                </div>

                                <div class="form-row {{$mb}}">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3" for="checkout_place_id">{{ __('Τόπος') }}
                                            :</label>
                                    </div>
                                    <div class="col">
                                        @placesSelector([
                                            'id' => 'checkout_place',
                                            'name' => 'checkout_place',
                                            'option' => isset($quote) && isset($quote->checkout_place) ? $quote->checkout_place : null,
                                            'text' => isset($quote) && $quote->checkout_place_text ? $quote->checkout_place_text : null,
                                            'addBtn' => true,
                                            'depends' => ['stations' => 'checkout_station_id']
                                        ])
                                        @endplacesSelector
                                    </div>
                                    <div class="col-md-3 input-group d-none">
                                        <input id="checkout_station_fee" name="checkout_station_fee"
                                            type="text" class="form-control float-input"
                                            value="@if(isset($quote)){{$quote->checkout_station_fee}}@else{{'0'}}@endif">
                                        <div class="input-group-append">
                                            <div class="input-group-text">€</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="col-6 d-flex {{$mb}}">
                                        <div class="text-right">
                                            <label class="float-right mr-3 text-nowrap" for="flight">{{ __('Πτήση') }}
                                                :</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            <input name="flight" id="flight"
                                                    class="form-control" @if(isset($quote))value="{{$quote->flight}}"@endif />
                                        </div>
                                    </div>
                                    <div class="col-6 d-flex {{$mb}}">
                                        <div class="text-right">
                                            <label class="float-right mr-3 text-nowrap" for="checkout_notes">{{ __('Σημειώσεις') }}
                                                :</label>
                                        </div>
                                        <div class="transfer_info_option">
                                            <textarea name="checkout_notes" id="checkout_notes"
                                                    class="form-control">@if(isset($quote)){{$quote->checkout_comments}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card col-6">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Πληροφορίες Παραλαβής') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-inline {{$mb}}">
                                    <div class="transfer_info_title checkin_datetime text-right">
                                        <label
                                            class="float-right mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        @datetimepicker([
                                            'id' => 'checkin_datetime',
                                            'name' => 'checkin_datetime',
                                            'datetime' => isset($quote) ? $quote->checkin_datetime : now()->addDay(),
                                            'required' => true
                                        ])
                                        @enddatetimepicker
                                    </div>
                                </div>

                                <div class="form-inline {{$mb}}">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3"
                                            for="checkin_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        @stationSelector([
                                            'id' => 'checkin_station_id',
                                            'name' => 'checkin_station_id',
                                            'stations' => isset($quote) && $quote->checkin_station ? [$quote->checkin_station] : [],
                                            'required' => true
                                        ])
                                        @endstationSelector
                                    </div>
                                </div>

                                <div class="form-row {{$mb}}">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3" for="checkin_place_id">{{ __('Τόπος') }}
                                            :</label>
                                    </div>
                                    <div class="col">
                                        @placesSelector([
                                            'id' => 'checkin_place',
                                            'name' => 'checkin_place',
                                            'option' => isset($quote) && isset($quote->checkin_place) ? $quote->checkin_place : null,
                                            'text' => isset($quote) && $quote->checkin_place_text ? $quote->checkin_place_text : null,
                                            'addBtn' => true,
                                            'depends' => ['stations' => 'checkin_station_id']
                                        ])
                                        @endplacesSelector
                                    </div>
                                    <div class="col-md-3 input-group d-none">
                                        <input id="checkin_station_fee" name="checkin_station_fee"
                                            type="text" class="form-control float-input"
                                            value="@if(isset($quote)){{$quote->checkin_station_fee}}@else{{'0'}}@endif">
                                        <div class="input-group-append">
                                            <div class="input-group-text">€</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-inline {{$mb}}">
                                    <div class="transfer_info_title text-right">
                                        <label class="float-right mr-3" for="checkin_notes">{{ __('Σημειώσεις') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                <textarea name="checkin_notes" id="checkin_notes"
                                        class="form-control">@if(isset($quote)){{$quote->checkin_comments}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card col-6">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Πληροφορίες οχήματος') }}</h3>
                            </div>
                            <div class="card-body">

                                <div class="{{ $mb }}">
                                    <div class="transfer_info_title">
                                        <label class="mr-3"
                                            for="booking_group_id"><strong>*&nbsp;</strong>{{ __('Group') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        @groupSelector([
                                            'id' => 'booking_group_id',
                                            'name' => 'type_id',
                                            'groups' => isset($quote) && $quote->type ? [$quote->type] : []
                                        ])
                                        @endgroupSelector
                                    </div>
                                </div>

                                <div class="{{ $mb }}">
                                    <div class="transfer_info_title">
                                        <label class="mr-3"
                                            for="excess">{{ __('Excess') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        <input id="excess" name="excess"
                                            class="form-control float-input" @if(isset($quote) && $quote->excess)value="{{ $quote->excess }}"@endif>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="card col-6">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Πληροφορίες διάρκειας') }}</h3>
                            </div>
                            <div class="card-body d-flex flex-wrap">

                                <div class="col-6 {{ $mb }}">
                                    <div class="transfer_info_title">
                                        <label class="mr-3"
                                            for="duration">{{ __('Διάρκεια') }}
                                            :</label>
                                    </div>
                                    <div class="d-flex">
                                        <div class="transfer_info_option">
                                            <div class="input-group">
                                                @duration([
                                                    'id' => 'duration',
                                                    'name' => 'duration',
                                                    'value' => isset($quote) ? $quote->duration : 1,
                                                    'from' => '#checkout_datetime',
                                                    'to' => '#checkin_datetime',
                                                    'extra_day' => true
                                                ])
                                                @endduration
                                            </div>
                                        </div>
                                        @php
                                            if (isset($quote)) {
                                                $checkin_date = \Carbon\Carbon::parse(formatDate($quote->checkin_datetime, 'Y-m-d'));
                                                $checkout_date = \Carbon\Carbon::parse(formatDate($quote->checkout_datetime, 'Y-m-d'));
                                                $checkin_datetime = \Carbon\Carbon::parse($quote->checkin_datetime);
                                                $checkout_datetime = \Carbon\Carbon::parse($quote->checkout_datetime);
                                                $days = $checkin_date->diffInDays($checkout_date);
                                                $minutes = $checkin_datetime->diffInMinutes($checkout_datetime);
                                            }
                                            $extra_day = false;
                                            if (isset($days) && $days*24*60 < $minutes - config('preferences.checkin_free_minutes')) {
                                                $extra_day = true;
                                            }
                                        @endphp
                                        <div class="form-check-inline extra_day @if(!$extra_day) d-none @endif">
                                            <div class="form-check-inline">
                                                    <input id="extra_day" name="extra_day" type="checkbox" class="form-control mr-1 ml-1"
                                                        @if(isset($quote) && $quote->extra_day!=0){{"checked"}}@endif
                                                    >
                                                <label for="extra_day" class="form-check-label text-nowrap"> {{ _('+ ημέρα') }} </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 {{ $mb }}">
                                    <div class="transfer_info_title">
                                        <label class="mr-3" for="extension_rate">{{ __('Βασικό Μίσθωμα') }}:</label>
                                    </div>
                                    <div class="input-group">
                                        <input value="@if(isset($quote)){{ $quote->rental_fee }}@else{{ old('rental_fee', 0) }}@endif" id="rental_fee_info" name="rental_fee_info" type="text" class="form-control text-right float-input">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 {{ $mb }}">
                                    <div class="transfer_info_title">
                                        <label class="mr-3" for="extension_rate">{{ __('Κόστος παράτασης') }}:</label>
                                    </div>
                                    <div class="transfer_info_option input-group">
                                        <input required id="extension_rate" name="extension_rate" type="text" class="form-control float-input"
                                            value="@if(isset($quote)){{$quote->extension_rate }}@else{{ old('extension_rate', 0) }}@endif" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 {{ $mb }}">
                                    <div class="form-check-inline may-extend-container">
                                            <input id="may_extend" name="may_extend" type="checkbox" class="form-control mr-2"
                                                @if(isset($quote) && $quote->may_extend!=0){{"checked"}}@else{{ old('may_extend', 0) }}@endif
                                            >
                                        <label for="may_extend" class="form-check-label text-nowrap"> {{ _('Ισως επεκταθεί') }} </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card col-12">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Σημειώσεις') }}</h3>
                            </div>
                            <div class="card-body">
                                <textarea id="notes" name="notes" class="form-control">@if(isset($quote)){{$quote->comments}}@endif</textarea>
                            </div>
                        </div>

                        <div class="card col-12">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Επισυναπτόμενα έγγραφα') }}</h3>
                            </div>
                            <div class="card-body">
                                <input type="file" class="form-control" id="files" name="files[]" multiple>
                            </div>
                            <div class="card-footer">
                                <fieldset>
                                    <div class="form-inline {{$mb}}">
                                        <div class="transfer_info_title text-right">
                                            <label class="float-right mr-3">{{ __('Αρχεία') }}:</label>
                                        </div>
                                        <div class="transfer_info_option text-center">
                                            @if(isset($quote))
                                                @foreach($quote->documents as $file)
                                                    @document(['file' => $file, 'document_link_id' => $quote->id, 'document_link_type' => get_class($quote) ])
                                                    @enddocument
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $args = [];
                    if (isset($quote)) {
                        $args['model'] = $quote;
                    }
                    $args['model_type'] = 'quote';
                    $args['duplicate'] = $duplicate;
                @endphp
                @include('template-parts.summary', $args)
            </div>

        @include('template-parts.summary-charges', $args)
        </form>
    </div>
    <script>
        @if(isset($quote))
        print_mail_url = "{{route('mail_quote_pdf', ['id'=>$quote->id, 'locale'=>$lng])}}";
        @endif
    </script>
    @include('template-parts.printer')
@endsection

@include('template-parts.bookingModal')
