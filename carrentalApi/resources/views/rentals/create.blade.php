@php
    $args = [];
    if (isset($rental)) {
        $args['model'] = $rental;
    }
@endphp
@extends('layouts.booking-with-payments', $args)

@section('title')
    {{ (isset($rental) && get_class($rental) != \App\Booking::class && !$duplicate)?__('Επεξεργασία Μίσθωσης'): __('Προσθήκη Μίσθωσης') }}
@endsection

@if (isset($rental) && get_class($rental) != \App\Booking::class && !$duplicate)
    @section('sub-title')
        - <span id="status-text" class="text-danger">{{ $rental->status_text }}</span>
        - <span id="ref-text" class="text-secondary">@if(isset($rental)){{$rental->sequence_number}}@if($rental->modification_number > 0) - {{ $rental->modification_number }}@endif @endif</span>
    @endsection
@endif


@section('extra_tabs')
    @if (isset($rental) && $rental->invoices && $rental->invoices->count() > 0 && !$duplicate)
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#invoices">{{__('Παραστατικά')}}</a>
        </li>
    @endif
    @if (isset($rental) && $rental->exchanges && $rental->exchanges->count() > 0 && !$duplicate)
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#exchanges">{{__('Αντικαταστάσεις Οχημάτων')}}</a>
        </li>
    @endif
@endsection

@section('forms')
    <div class="row">
        <div class="col-sm-12">
            <div class="d-flex rental-statuses fixed-bottom-menu">
                <div class="basic-buttons d-flex justify-content-between">
                    @php
                        $save = isset($rental) && !$duplicate ? 'Αποθήκευση' : 'Δημιουργία'
                    @endphp
                    <button type="submit" name="save" class="btn btn-success  mr-3 trigger-activator" value="save">{{ __($save) }}</button>
                    <button type="submit" name="save" class="btn btn-primary  mr-3 trigger-activator" value="save_and_close">{{ __("$save και Κλείσιμο") }}</button>
                    <button type="submit" name="save" class="btn btn-secondary  mr-3 trigger-activator" value="save_and_new">{{ __("$save και Νέο") }}</button>
                </div>
                @if(isset($rental) && get_class($rental) != \App\Booking::class && !$duplicate)

                    <button type="button" id="print_files" class="print_file float-left btn-sm btn-info fa fa-print">{{__('Εκτύπωση')}}</button>
                    <button type="button" id="print_files2" class="print_file float-left btn-sm btn-info fa fa-print ml-3">{{__('Εκτύπωση Υπογραφή')}}</button>
                    @if ($rental->status != \App\Rental::STATUS_CLOSED || Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                        @if ($rental->status != \App\Rental::STATUS_CHECKED_IN && $rental->status != \App\Rental::STATUS_PRE_CHECKED_IN)
                            <button type="butΗμερομηνία γέννησηςton" data-status="{{ \App\Rental::STATUS_PRE_CHECKED_IN }}" class="rental-status-btn check-in-btn float-left btn btn-sm btn-secondary ml-3
                                @if($rental->status == \App\Rental::STATUS_CLOSED) d-none @endif">{{ _('Pre-Check in') }}</button>
                        @endif
                        @if ($rental->status != \App\Rental::STATUS_CHECKED_IN)
                            <button type="button" data-status="{{ \App\Rental::STATUS_CHECKED_IN }}" class="rental-status-btn check-in-btn float-left btn btn-sm btn-secondary ml-3
                                @if($rental->status == \App\Rental::STATUS_CLOSED) d-none @endif">{{ _('Check in') }}</button>
                        @endif
                        @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                            @if ($rental->status != \App\Rental::STATUS_CLOSED)
                                <button type="button" data-status="{{ \App\Rental::STATUS_CLOSED }}" class="rental-status-btn check-in-btn float-left btn btn-sm btn-secondary ml-3">{{ _('Close') }}</button>
                                <button type="button" data-status="{{ \App\Rental::STATUS_CANCELLED }}" class="rental-status-btn float-left btn btn-sm btn-danger ml-3
                                    @if($rental->status == \App\Rental::STATUS_CANCELLED) d-none @endif">{{ _('Cancel') }}</button>
                            @endif
                            <button type="button" data-status="{{ \App\Rental::STATUS_ACTIVE }}" class="rental-status-btn float-left btn btn-sm btn-primary ml-3
                                @if($rental->status == \App\Rental::STATUS_ACTIVE) d-none @endif">{{ _('Reactivate') }}</button>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('booking')
    <style>
        .selectr-selected {
            border: 1px solid #e9ecef !important;
        }
    </style>
    @php
        $mb = "mb-2";
    @endphp
    {{-- {{ dd($rental->vehicle->color_type) }} --}}
    <div class="container-fluid booking-container rental-container @if(!isset($rental)) new-rental @endif">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('message') }}
            </p>
        @endif

        @if(isset($rental) && !$duplicate)
            <input id="rental-status" type="hidden" name="status" @if(isset($rental) && get_class($rental) != \App\Booking::class) value="{{ $rental->status }}" @endif/>
            @if(get_class($rental) != \App\Booking::class)
                <input type="hidden" name="id" value="{{$rental->id}}" />
            @else
                <input type="hidden" name="booking_id" value="{{$rental->id}}" />
            @endif
        @endif
        {{-- {{ dd($rental->company) }} --}}
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="row no-gutters">
                    <div class="card col-12">
                        <div class="card-header">
                            <h3 class="float-left">{{ _('Γενικές Πληροφορίες') }}</h3>
                            <div class="d-flex float-right">
                                <input type="text" class="form-control" value="@if(isset($rental)  && get_class($rental) != \App\Booking::class && !$duplicate){{$rental->sequence_number}}@if($rental->modification_number > 0) - {{ $rental->modification_number }}@endif @endif"
                                    disabled>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-sm-block d-md-block d-lg-block d-xl-flex">
                                @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                                    <div class="p-2 flex-fill">
                                        <label class="mr-3">* {{_('Ημ. Δημιουργίας')}}:</label>
                                        <input type="text" class="datepicker form-control mb-2"
                                            value="@if(isset($rental) && !$duplicate){{ formatDate($rental->created_at) }}@else{{ formatDate(now()) }}@endif"
                                            disabled>
                                    </div>
                                @endif
                                <div class="p-2 flex-fill">
                                    <label class="mr-3">* {{_('Πηγή')}}:</label>
                                    @php $defSource = \App\BookingSource::find(config('preferences.rental_source_id')); @endphp
                                    @sourceSelector([
                                        'id' => 'booking_source',
                                        'name' => 'source_id',
                                        'extra_fields' => ['brand_id', 'program_id', 'agent_id'],
                                        'sources' => isset($rental) && $rental->source ? [$rental->source] : [$defSource],
                                        'addBtn' => true
                                    ])
                                    @endsourceSelector
                                </div>
                                <div class="p-2 flex-fill">
                                    <label class="mr-3">* {{_('Επωνυμία')}}:</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        @foreach(App\Brand::all() as $brand)
                                            <option
                                                value="{{$brand->id}}" @if((isset($rental) && $brand->id == $rental->brand_id) ||
                                                (!isset($rental) && $defSource->brand_id == $brand->id)){{'selected'}}@endif>
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
                                            @if(isset($rental))
                                                <option selected
                                                        value="{{ $rental->user->id }}">{{ $rental->user->name }}</option>
                                            @else
                                                <option value="{{Auth::id()}}">{{Auth::user()->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif
                                <div class="p-2 flex-fill" id="create_by">
                                    <label class="mr-3">* {{_('Ετικέτες')}}:</label>
                                    @tags([
                                        'tags' => isset($rental) && $rental->tags ? $rental->tags : [],
                                        'query_fields' => ['type' => 'App\\Quote,App\\Booking,App\\Rental']
                                    ])
                                    @endtags
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">
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
                                                'id' => 'company_id',
                                                'name' => 'company_id',
                                                'companies' => isset($rental) && $rental->company ? [$rental->company] : [],
                                                'addBtn' => true,
                                                'editBtn' => true
                                            ])
                                            @endcompanySelector
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (isset($rental) && (!$rental->customer || get_class($rental->customer) != \App\Driver::class))
                                @php
                                    $add_fields = [];
                                    $add_fields['name'] = $rental->customer_text;
                                    if ($rental->phone) {
                                        $add_fields['phone'] = $rental->phone;
                                    }
                                    if ($rental->customer && get_class($rental->customer) != \App\Driver::class) {
                                        $add_fields['contact_id'] = $rental->customer_id;
                                    }
                                @endphp
                                <div class="d-flex">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Κράτηση από') }}: <span id="customer_text" >{{ $rental->customer_text }}</span></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                            <div class="booking_driver" id="booking_driver">
                                <div class="d-flex" id="driver1">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Οδηγός') }}:</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1" id="drivers_block">
                                                @driverSelector([
                                                    'id' => 'driver_id',
                                                    'name' => 'driver_id',
                                                    'drivers' => isset($rental) && $rental->customer
                                                        && get_class($rental->customer) == \App\Driver::class ? [$rental->customer] : [],
                                                    'addBtn' => true,
                                                    'required' => true,
                                                    'editBtn' => true,
                                                    'add_fields' => isset($add_fields) ? $add_fields : [],
                                                    'extra_fields' => ['phone']
                                                ])
                                                @enddriverSelector
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="booking_driver">
                                <div class="d-flex" id="driver1">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Τηλέφωνο') }}:</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1">
                                                <input id="phone" disabled pattern="\+?[0-9]{0,}" class="form-control" type="tel"
                                                    @if(isset($rental) && get_class($rental) == \App\Rental::class) value="{{ $rental->customer->phone }}"
                                                    @elseif(isset($rental) && get_class($rental) == \App\Booking::class) value="{{ $rental->phone }}"
                                                    @endif />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rental_drivers" id="rental_drivers">
                                <div class="d-flex" id="driver1">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('+Οδηγοί') }}:</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1" id="drivers_block">
                                                @php
                                                    $drivers = [];
                                                    if (isset($rental)) $drivers = $rental->drivers;
                                                @endphp
                                                @driverSelector([
                                                    'name' => 'drivers',
                                                    'drivers' => $drivers,
                                                    'multiple' => true,
                                                    'addBtn' => true,
                                                    'editBtn' => true
                                                ])
                                                @enddriverSelector
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">

                        <div class="card-header">
                            <h3 class="float-left m-0">{{ _('Πληροφορίες συνεργάτη') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap">
                                <div class="col-12 p-2 flex-fill">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ _('Συνεργάτης') }}</span>
                                        </div>
                                        <div class="input-group-prepend flex-grow-1" id="agent_block">
                                            @agentSelector([
                                                'id' => 'agent_id',
                                                'name' => 'agent_id',
                                                'extra_fields' => ['program_id', 'brand_id'],
                                                'agents' => isset($rental) && $rental->agent ? [$rental->agent] : (!isset($rental) && $defSource->agent ? [$defSource->agent] : []),
                                                'addBtn' => true,
                                                'editBtn' => true,
                                                'depends' => ['booking_source' => 'booking_source']
                                            ])
                                            @endagentSelector
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 p-2 flex-fill">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ _('Πωλητής') }}</span>
                                        </div>
                                        <div class="input-group-prepend flex-grow-1" id="agent_block">
                                            @subaccountSelector([
                                                'id' => 'sub_account_id',
                                                'name' => 'sub_account_id',
                                                'sub_accounts' => isset($rental) && $rental->sub_account_normalized ? [$rental->sub_account_normalized] : [],
                                                'addBtn' => true,
                                                'editBtn' => true,
                                                'depends' => ['parent_agent' => 'agent_id'],
                                                'searchUrl' => 'searchSubAccountWithAgentUrl'
                                            ])
                                            @endsubaccountSelector
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 p-2 flex-fill">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ _('Πρόγραμμα') }}:</span>
                                        </div>
                                        <div class="input-group-prepend flex-grow-1">
                                            <select name="program_id" id="program_id" class="form-control">
                                                @foreach (\App\Program::all() as $program)
                                                    <option @if((isset($rental) && $rental->program_id ==$program->id) || (!isset($rental) && $defSource->program_id == $program->id)) selected @endif value="{{ $program->id }}">{{ $program->profile_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 p-2 flex-fill">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ _('Conf #') }}:</span>
                                        </div>
                                        <input type="text" class="form-control" name="confirmation_number" id="confirmation_number"
                                        @if(isset($rental) && $rental->confirmation_number)value="{{ $rental->confirmation_number }}"@endif/>
                                    </div>
                                </div>
                                <div class="col-6 p-2 flex-fill">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ _('Voucher') }}:</span>
                                        </div>
                                        <input type="text" class="form-control" name="agent_voucher" id="agent_voucher"
                                            @if(isset($rental) && $rental->agent_voucher)value="{{ $rental->agent_voucher }}"@endif/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card col-lg-12 col-xl-6"  id="checkin-container">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Πληροφορίες Παράδοσης') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title checkout_datetime ">
                                    <label
                                        class=" mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @php
                                        if (isset($rental) && get_class($rental) != \App\Booking::class) {
                                            $checkout_datetime = $rental->checkout_datetime;
                                        } else {
                                            $checkout_datetime = \Carbon\Carbon::now()->toDateTimeString();
                                        }
                                    @endphp
                                    @datetimepicker([
                                        'id' => 'checkout_datetime',
                                        'name' => 'checkout_datetime',
                                        'datetime' => $checkout_datetime,
                                        'required' => true
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3"
                                        for="checkout_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @stationSelector([
                                        'id' => 'checkout_station_id',
                                        'name' => 'checkout_station_id',
                                        'stations' => isset($rental) ? [$rental->checkout_station] : []
                                    ])
                                    @endstationSelector
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class="mr-3" for="checkout_place_id">{{ __('Τόπος') }}
                                        :</label>
                                </div>
                                <div class="">
                                    @placesSelector([
                                        'id' => 'checkout_place',
                                        'name' => 'checkout_place',
                                        'option' => isset($rental) && isset($rental->checkout_place) ? $rental->checkout_place : null,
                                        'text' => isset($rental) && $rental->checkout_place_text ? $rental->checkout_place_text : null,
                                        'addBtn' => true,
                                        'depends' => ['stations' => 'checkout_station_id']
                                    ])
                                    @endplacesSelector
                                </div>
                                <div class="col-md-3 input-group d-none">
                                    <input id="checkout_station_fee" name="checkout_station_fee"
                                        type="text" class="form-control float-input"
                                        value="@if(isset($rental)){{$rental->checkout_station_fee}}@else{{'0'}}@endif">
                                    <div class="input-group-append">
                                        <div class="input-group-text">€</div>
                                    </div>
                                </div>
                            </div>


                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3 text-nowrap" for="flight">{{ __('Πτήση') }}
                                        :</label>
                                </div>
                                <div class="">
                                    <input name="flight" id="flight"
                                           class="form-control" @if(isset($rental))value="{{$rental->flight}}"@endif />
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3 text-nowrap" for="checkout_notes">{{ __('Σημειώσεις') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                     <textarea name="checkout_notes" id="checkout_notes"
                                               class="form-control">@if(isset($rental)){{$rental->checkout_comments}}@endif</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Πληροφορίες Παραλαβής') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title checkin_datetime ">
                                    <label
                                        class=" mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @php
                                        $checkin_datetime = isset($rental) && ((get_class($rental) == \App\Booking::class &&
                                            time() < strtotime($rental->checkin_datetime)) || get_class($rental) == \App\Rental::class) ? $rental->checkin_datetime : \Carbon\Carbon::now()->addDay()->toDateTimeString();
                                    @endphp
                                    @datetimepicker([
                                        'id' => 'checkin_datetime',
                                        'name' => 'checkin_datetime',
                                        'datetime' => $checkin_datetime,
                                        'required' => true
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3"
                                        for="checkin_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @stationSelector([
                                        'id' => 'checkin_station_id',
                                        'name' => 'checkin_station_id',
                                        'stations' => isset($rental) ? [$rental->checkin_station] : []
                                    ])
                                    @endstationSelector
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3" for="checkin_place_id">{{ __('Τόπος') }}
                                        :</label>
                                </div>
                                <div class="">
                                    @placesSelector([
                                        'id' => 'checkin_place',
                                        'name' => 'checkin_place',
                                        'option' => isset($rental) && isset($rental->checkin_place) ? $rental->checkin_place : null,
                                        'text' => isset($rental) && $rental->checkin_place_text ? $rental->checkin_place_text : null,
                                        'addBtn' => true,
                                        'depends' => ['stations' => 'checkin_station_id']
                                    ])
                                    @endplacesSelector
                                </div>
                                <div class="input-group d-none">
                                    <input id="checkin_station_fee" name="checkin_station_fee"
                                        type="text" class="form-control float-input"
                                        value="@if(isset($rental)){{$rental->checkin_station_fee}}@else{{'0'}}@endif">
                                    <div class="input-group-append">
                                        <div class="input-group-text">€</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3" for="checkin_notes">{{ __('Σημειώσεις') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                            <textarea name="checkin_notes" id="checkin_notes"
                                    class="form-control">@if(isset($rental)){{$rental->checkin_comments}}@endif</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Πληροφορίες οχήματος') }}</h3>
                        </div>
                        <div class="card-body d-flex flex-wrap">

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="type_id"><strong>*&nbsp;</strong>{{ __('Group') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @groupSelector([
                                        'id' => 'booking_group_id',
                                        'name' => 'type_id',
                                        'groups' => isset($rental) && isset($rental->type) && (get_class($rental) != \App\Booking::class || !is_null($rental->vehicle)) ? [$rental->type] : [],
                                        'required' => true
                                    ])
                                    @endgroupSelector
                                </div>
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="vehicle-model">{{ __('Μοντέλο Αυτοκινήτου') }}
                                        :</label>
                                </div>
                                <input id="vehicle-model" class="form-control" @if(isset($rental) && $rental->vehicle)value="{{ $rental->vehicle->make." ".$rental->vehicle->model }}"@endif disabled />
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="vehicle_id">{{ __('Πινακίδα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option d-flex @if(isset($rental) && get_class($rental) == \App\Rental::class && ($rental->exchanges->count() > 1 ||
                                        ($rental->exchanges->count() > 0 && $rental->exchanges[0]->status == \App\VehicleExchange::STATUS_COMPLETED))) exchanged @endif">
                                    @php
                                        $query_fields = [
                                            'from' => $checkout_datetime,
                                            'to' => $checkin_datetime
                                        ];
                                        if (isset($rental) && get_class($rental) == \App\Rental::class && (!isset($duplicate) || !$duplicate)) {
                                            $query_fields['rental_id'] = $rental->id;
                                        }
                                    @endphp
                                    @vehicleSelector([
                                        'id' => 'vehicle_id',
                                        'name' => 'vehicle_id',
                                        'vehicles' => isset($rental) && isset($rental->vehicle) && !$duplicate ? [$rental->vehicle] : [],
                                        'required' => true,
                                        'depends' => ['group' => 'booking_group_id'],
                                        'query_fields' => $query_fields
                                    ])
                                    @endvehicleSelector
                                    @if (isset($rental) && get_class($rental) == \App\Rental::class)
                                        <a href="{{ route('exchange_view', ['locale' => $lng, 'rental_id' => $rental->id]) }}" class="btn btn-primary" data-toggle="tooltip" title="Αντικατάσταση Οχήματος"><i class="fas fa-exchange-alt"></i></a>
                                    @endif
                                </div>

                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="">
                                    <label class=" mr-3" for="checkout_km">{{ __('Χιλιόμετρα') }}
                                        :</label>
                                </div>
                                <div class="input-group-prepend flex-grow-1">
                                    <input required class="form-control" id="checkout_km" name="checkout_km" type="number" @if(isset($rental)) value="{{ $rental->checkout_km ?? $rental->vehicle->km ?? 0 }}" @endif />
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="">
                                    <label class=" mr-3" for="checkout_fuel_level">{{ __('Στάθμη καυσίμου:') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    <div class="input-group">
                                        <input min="0" max="8" required class="form-control" id="checkout_fuel_level" name="checkout_fuel_level" type="number" @if(isset($rental)) value="{{ $rental->checkout_fuel_level ?? $rental->vehicle->fuel_level ?? 0 }}" @endif />
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">/ 8</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="{{ $mb }}">
                                <div class="transfer_info_title text-left">
                                    <label class=" mr-3"
                                        for="options">{{ __('Παροχές') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option" id="vehicles_block">
                                    @optionSelector([
                                        'id' => 'booking_extras',
                                        'name' => 'extras',
                                        'options' => isset($rental) && $rental->extras ? $rental->extras : [],
                                        'multiple' => true
                                    ])
                                    @endoptionSelector
                                </div>
                            </div> --}}

                            {{-- <div class="{{ $mb }}">
                                <div class="transfer_info_title text-left">
                                    <label class=" mr-3"
                                        for="options">{{ __('Ασφάλειες') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option" id="vehicles_block">
                                    @optionSelector([
                                        'id' => 'booking_insurances',
                                        'name' => 'insurances',
                                        'options' => isset($rental) && $rental->insurances ? $rental->insurances : [],
                                        'searchUrl' => 'searchInsurancesUrl',
                                        'multiple' => true
                                    ])
                                    @endoptionSelector
                                </div>
                            </div> --}}

                            <div class="col-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="excess">{{ __('Excess') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    <input id="excess" name="excess"
                                        class="form-control float-input" @if(isset($rental) && $rental->excess)value="{{ $rental->excess }}"@endif >
                                </div>
                            </div>

                            <div class="d-lg-block p-2">
                                <div class="">
                                    <label class=" mr-3" for="checkout_driver_id">{{ __('Υπάλληλος') }}
                                        :</label>
                                </div>
                                <div class="input-group-prepend flex-grow-1">
                                    @driverSelector([
                                        'name' => 'checkout_driver_id',
                                        'drivers' => isset($rental) && isset($rental->checkout_driver) && $rental->checkout_driver ? [$rental->checkout_driver] : [Auth::user()->driver],
                                        'addBtn' => true,
                                        'required' => true,
                                        'query_fields' => [
                                            'role' => 'employee'
                                        ],
                                        'disabled' => Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root' ? false : true
                                    ])
                                    @enddriverSelector
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">
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
                                        @duration([
                                            'id' => 'duration',
                                            'name' => 'duration',
                                            'value' => isset($rental) ? $rental->duration : 1,
                                            'from' => '#checkout_datetime',
                                            'to' => '#checkin_datetime',
                                            'extra_day' => true
                                        ])
                                        @endduration
                                    </div>
                                    <div class="transfer_info_option">
                                        @php
                                            if (isset($rental)) {
                                                $checkin_date = \Carbon\Carbon::parse(formatDate($rental->checkin_datetime, 'Y-m-d'));
                                                $checkout_date = \Carbon\Carbon::parse(formatDate($rental->checkout_datetime, 'Y-m-d'));
                                                $checkin_datetime = \Carbon\Carbon::parse($rental->checkin_datetime);
                                                $checkout_datetime = \Carbon\Carbon::parse($rental->checkout_datetime);
                                                $days = $checkin_date->diffInDays($checkout_date);
                                                $minutes = $checkin_datetime->diffInMinutes($checkout_datetime);
                                            }
                                            $extra_day = false;
                                            if (isset($days) && $days*24*60 < $minutes - config('preferences.checkin_free_minutes')) {
                                                $extra_day = true;
                                            }
                                        @endphp
                                        <div class="form-check-inline extra_day @if(!$extra_day) d-none @endif">
                                                <input id="extra_day" name="extra_day" type="checkbox" class="form-control mr-1 ml-1"
                                                    @if(isset($rental) && $rental->extra_day!=0){{"checked"}}@endif
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
                                    <input required value="@if(isset($rental)){{ $rental->rental_fee }}@else{{ old('rental_fee', 0) }}@endif" id="rental_fee_info" name="rental_fee_info" type="text" class="form-control  float-input">
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
                                        value="@if(isset($rental)){{$rental->extension_rate }}@else{{ old('extension_rate',0) }}@endif" >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 {{ $mb }}">
                                <div class="form-check-inline may-extend-container">
                                    <input id="may_extend" name="may_extend" type="checkbox" class="form-control mr-2"
                                    @if(isset($rental) && $rental->may_extend!=0){{"checked"}}@endif
                                    >
                                    <label for="may_extend" class="form-check-label text-nowrap"> {{ _('Ισως επεκταθεί') }} </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if (isset($rental) && get_class($rental) == \App\Rental::class)
                        <div id="checkin-km-container" class="card col-12 @if (($rental->status != \App\Rental::STATUS_CHECKED_IN && $rental->status != \App\Rental::STATUS_PRE_CHECKED_IN && $rental->status != \App\Rental::STATUS_CLOSED) || $duplicate) d-none @endif">
                            <div class="card-header">
                                <h3 class="m-0">{{ _('Πληροφορίες Παραλαβής οχήματος') }}</h3>
                            </div>
                            <div class="card-body d-flex">
                                <div class="d-lg-block d-xl-flex p-2">
                                    <div class="">
                                        <label class=" mr-3" for="checkin_km">{{ __('Χιλιόμετρα') }}
                                            :</label>
                                    </div>
                                    <div class="input-group-prepend flex-grow-1">
                                        <input class="form-control" id="checkin_km" name="checkin_km" type="number" value="@if(isset($rental)){{ $rental->checkin_km }}@else{{ old('checkin_km') }}@endif" />
                                    </div>
                                </div>
                                <div class="d-lg-block d-xl-flex p-2">
                                    <div class="">
                                        <label class=" mr-3" for="checkin_fuel_level">{{ __('Στάθμη καυσίμου:') }}
                                            :</label>
                                    </div>
                                    <div class="transfer_info_option">
                                        <div class="input-group">
                                            <input min="0" max="8" class="form-control" id="checkin_fuel_level" name="checkin_fuel_level" type="number" @if(isset($rental)) value="{{ $rental->checkin_fuel_level }}" @endif />
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">/ 8</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-lg-block d-xl-flex p-2">
                                    <div class="">
                                        <label class=" mr-3" for="checkin_driver_id">{{ __('Υπάλληλος') }}
                                            :</label>
                                    </div>
                                    <div class="input-group-prepend flex-grow-1">
                                        @driverSelector([
                                            'name' => 'checkin_driver_id',
                                            'drivers' => isset($rental) && isset($rental->checkin_driver) && $rental->checkin_driver ? [$rental->checkin_driver] : [Auth::user()->driver],
                                            'addBtn' => true,
                                            'query_fields' => [
                                                'role' => 'employee'
                                            ],
                                            'disabled' => Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root' ? false : true
                                        ])
                                        @enddriverSelector
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card col-12">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Σημειώσεις') }}</h3>
                        </div>
                        <div class="card-body">
                            <textarea id="notes" name="notes" class="form-control">@if(isset($rental)){{$rental->comments}}@endif</textarea>
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
                                <div class="d-lg-block d-xl-flex p-2">
                                    <div class="transfer_info_title ">
                                        <label class=" mr-3">{{ __('Αρχεία') }}:</label>
                                    </div>
                                    <div class="transfer_info_option text-center">
                                        @if(isset($rental))
                                            @foreach($rental->documents as $file)
                                                @document(['file' => $file, 'document_link_id' => $rental->id, 'document_link_type' => get_class($rental) ])
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
                $args = [
                    'action' => route('create_rental', $lng)
                ];
                if (isset($rental)) {
                    $args['model'] = $rental;
                }
                $args['model_type'] = 'rental';
            @endphp
            @include('template-parts.summary', $args)
        </div>
        @php
            $summary_charges_args = $args;
            $summary_charges_args['duplicate'] = isset($rental) && get_class($rental) == \App\Rental::class
                && !$duplicate ? false : true;
        @endphp
        @include('template-parts.summary-charges', $summary_charges_args)
        </form>
    </div>
@endsection

@section('extra_content')
    @if (isset($rental) && $rental->invoices && $rental->invoices->count() > 0)
        <div id="invoices" class="tab-pane fade container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Αριθμός Σειράς') }}:</th>
                        <th>{{ __('Τιμολόγηση σε') }}:</th>
                        <th>{{ __('Ποσό') }}</th>
                        <th>{{ __('Ημερομηνία') }}</th>
                        <th>{{ __('Τύπος') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($rental->invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->sequence_number }}</td>
                        <td>{{ $invoice->invoicee->name ?? $invoice->invoicee->full_name ?? __('Άγνωστο') }}</td>
                        <td>{{ $invoice->final_total }}</td>
                        <td>{{ formatDate($invoice->date) }}</td>
                        <td>{{ $invoice->type }}</td>
                        <td><a target="_blank" class="btn btn-primary" href="{{ route('edit_invoice_view', ['locale' => $lng, 'cat_id' => $invoice->id]) }}"><i class="fas fa-eye"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if (isset($rental) && $rental->exchanges && $rental->exchanges->count() > 0)
        <div id="exchanges" class="tab-pane fade container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Όχημα που αντικαταστάθηκε') }}:</th>
                        <th>{{ __('Διανυθέντα Χιλιόμετρα') }}</th>
                        <th>{{ __('Νέο όχημα') }}:</th>
                        <th>{{ __('Διανυθέντα Χιλιόμετρα') }}</th>
                        <th>{{ __('Ημερομηνία') }}</th>
                        <th>{{ __('Μέρος') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($rental->exchanges as $exchange)
                    <tr>
                        <td>{{ $exchange->old_vehicle->licence_plate }}</td>
                        <td>{{ $exchange->old_vehicle_km }}</td>
                        <td>{{ $exchange->new_vehicle->licence_plate ?? '' }}</td>
                        <td>{{ $exchange->new_vehicle_km }}</td>
                        <td>{{ formatDateTime($exchange->datetime) }}</td>
                        <td>{{ $exchange->place_text }}</td>
                        <td><a target="_blank" href="{{ route('edit_exchange_view', ['locale' => $lng, 'cat_id' => $exchange->id]) }}"><i class="fas fa-eye"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        @if(isset($rental))
            print_mail_url = "{{route('mail_rental_pdf', ['id'=>$rental->id, 'locale'=>$lng])}}";
            var duration = {{ $rental->duration }};
            @if($rental->checkin_duration)
                var checkin_duration = {{ $rental->checkin_duration }};
            @endif
            var booking_options
            @if($rental->booking)
                booking_options = {!! json_encode($rental->booking->options()->with('option')->get()) !!}
            @elseif(get_class($rental) == \App\Booking::class)
                booking_options = {!! json_encode($rental->options()->with('option')->get()) !!}
            @endif
        @endif

        $('.check-in-btn').on('click', function () {
            // const status = $(this).data('status');
            // // if (status == "{{ \App\Rental::STATUS_CHECKED_IN }}") {
            // //     $('.check-in-btn').addClass('d-none');
            // // } else {
            // //     $(this).addClass('d-none');
            // // }
            // $('#rental-status').val(status);
            $('#checkin-km-container').removeClass('d-none');
            $('html, body').animate({
                scrollTop: $("#checkin-container").offset().top - 70
            }, 500);
            const inputs = $('#checkin-km-container').find(':input');
            for (let input of inputs) {
                input = $(input);
                if (!input.hasClass('selectr-input')) {
                    input.prop('required', true);
                }
            }
        });

        const status_texts = {!! json_encode(\App\Rental::getStatusTexts()) !!}

        $('.rental-status-btn').on('click', function() {
            const statuses = $(this).closest('.rental-statuses').find('.rental-status-btn').each(function(i) {
                $(this).removeClass('d-none');
            });
            $(this).addClass('d-none');
            const status = $(this).data('status');
            $('#rental-status').val(status);
            $('#status-text').text(status_texts[status]);
            $('#rental-status').trigger('change');
        });

        @if(!isset($rental) || get_class($rental) == \App\Booking::class)
            $(document).ready(function() {
                $('#checkout_datetime').find('.datepicker').trigger('dp.change');
            })
        @endif

        @if (isset($rental) && get_class($rental) == \App\Rental::class)
            var booked_checkin_datetime = moment("{{ $rental->booked_checkin_datetime }}");
            // var booked_checkin_duration = 0;
            var has_extension = {{ $rental->checkin_datetime > $rental->booked_checkin_datetime ? 1 : 0 }} ? true : false;
            // console.log("");
        @endif

        driver_id.on('selectr.select', function(option) {
            const phone = $(option).data('phone');
            $('#phone').val(phone);
        });

        @if (isset($rental) && $rental->status == \App\Rental::STATUS_CLOSED && !(Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root'))
            $(document).ready(function () {
                $('[type="submit"]').addClass('d-none');
                $(':input:not(.preview_fee,.close,.btn-default,.btn-prices,.btn-cancel,#print_files,#print_files2)').prop('disabled', true);
                $('#printModalFiles').find(':input').prop('disabled', false);
                $('#printModalFile').find(':input').prop('disabled', false);
                const selects = $('select.ajax-selector');
                for (const select of selects) {
                    select.selectr.disable();
                }
            });
        @endif
    </script>
@endpush
