@php
    $args = ['action' => route('create_booking', $lng)];
    if (isset($booking)) {
        $args['model'] = $booking;
    }
    $args['duplicate'] = $duplicate;
@endphp
@extends('layouts.booking-with-payments', $args)

@section('title')
    {{ (isset($booking) && get_class($booking) != \App\Quote::class && !$duplicate)?__('Επεξεργασία Κράτησης'): __('Προσθήκη Κράτησης') }}
@endsection

@if (isset($booking) && get_class($booking) != \App\Quote::class && !$duplicate)
    @section('sub-title')
        - <span id="status-text" class="text-danger">{{ $booking->status_text }}</span>
    @endsection
@endif

@section('forms')
    <div class="row">
        <div class="col-sm-12">
            <div class="d-flex booking-statuses fixed-bottom-menu ">
                <div class="basic-buttons d-flex justify-content-between">
                    @php
                        $save = isset($booking) ? 'Αποθήκευση' : 'Δημιουργία'
                    @endphp
                    <button type="submit" name="save" class="btn btn-success  mr-3 trigger-activator" value="save">{{ __($save) }}</button>
                    <button type="submit" name="save" class="btn btn-primary  mr-3 trigger-activator" value="save_and_close">{{ __("$save και Κλείσιμο") }}</button>
                    <button type="submit" name="save" class="btn btn-secondary  mr-3 trigger-activator" value="save_and_new">{{ __("$save και Νέο") }}</button>
                </div>
                @if(isset($booking) && get_class($booking) != \App\Quote::class && !$duplicate)
                    <button type="submit" id="print_files" class="print_file float-left btn-sm btn-info fa fa-print">{{__('Εκτύπωση')}}</button>
                    @if ($booking->status != \App\Booking::STATUS_RENTAL && $booking->status != \App\Booking::STATUS_COMPLETED)
                        <button type="button" data-status="{{ \App\Booking::STATUS_CANCELLED }}" class="booking-status-btn float-left btn btn-sm btn-danger ml-3
                            @if($booking->status == \App\Booking::STATUS_CANCELLED) d-none @endif">{{ _('Cancel') }}</button>
                        <button type="button" data-status="{{ \App\Booking::STATUS_PENDING }}" class="booking-status-btn float-left btn btn-sm btn-primary ml-3
                            @if($booking->status == \App\Booking::STATUS_PENDING) d-none @endif">{{ _('Reactivate') }}</button>
                    @endif

                    @if($booking->rental)
                        <a href="{{ route('edit_rental_view', ['locale' => $lng, 'cat_id' => $booking->rental->id]) }}" class="btn btn-sm btn-dark ml-3 mb-3">{{ __('Δες την αντίστοιχη μίσθωση') }}</a>
                    @elseif($booking->status != \App\Booking::STATUS_CANCELLED)
                        <form id="create_next-form" action="{{route('create_rental_view', ['locale' => $lng])}}" method="get">
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                            <input type="submit" id="create_next-btn" class="btn btn-sm btn-dark ml-3" value="{{__('Δημιουργία Μίσθωσης')}}" />
                        </form>
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
    <div class="container-fluid booking-container">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session::get('message') }}
            </p>
        @endif
        @csrf


        <input id="cancel-reason" type="hidden" name="cancel_reason_id" @if(isset($booking) && get_class($booking) != \App\Quote::class) value="{{ $booking->cancel_reason_id }}" @endif/>
        <input id="booking-status" type="hidden" name="status" @if(isset($booking) && get_class($booking) != \App\Quote::class) value="{{ $booking->status }}" @endif/>

        @if(isset($booking) && !$duplicate && get_class($booking) != \App\Quote::class)
            <input type="hidden" name="id" value="{{$booking->id}}" />
        @elseif (isset($booking) && get_class($booking) == \App\Quote::class)
            <input type="hidden" name="quote_id" value="{{$booking->id}}" />
        @endif
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <div class="row no-gutters">
                    <div class="card col-12">
                        <div class="card-header">
                            <h3 class="float-left">{{ _('Γενικές Πληροφορίες') }}</h3>
                            <div class="d-flex float-right">
                                <input type="text" class="form-control" value="@if(isset($booking)){{$booking->sequence_number}}@if($booking->modification_number > 0) - {{ $booking->modification_number }}@endif @endif"
                                    disabled>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-sm-block d-md-block d-lg-block d-xl-flex">
                                @if (Auth::user()->role_id == 'administrator' || Auth::user()->role_id == 'root')
                                    <div class="p-2 flex-fill">
                                        <label class="mr-3">* {{_('Ημ. Δημιουργίας')}}:</label>
                                        <input type="text" class="datepicker form-control mb-2"
                                            value="@if(isset($booking)){{ formatDate($booking->created_at) }}@else{{ formatDate(now()) }}@endif"
                                            disabled>
                                    </div>
                                @endif
                                <div class="p-2 flex-fill">
                                    <label class="mr-3">* {{_('Πηγή')}}:</label>
                                    @php $defSource = \App\BookingSource::find(config('preferences.booking_source_id')); @endphp
                                    @sourceSelector([
                                        'id' => 'booking_source',
                                        'name' => 'source_id',
                                        'extra_fields' => ['brand_id', 'program_id', 'agent_id'],
                                        'sources' => isset($booking) && $booking->source ? [$booking->source] : [$defSource],
                                        'addBtn' => true
                                    ])
                                    @endsourceSelector
                                </div>
                                <div class="p-2 flex-fill">
                                    <label class="mr-3">* {{_('Επωνυμία')}}:</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        @foreach(App\Brand::all() as $brand)
                                            <option
                                                value="{{$brand->id}}" @if((isset($booking) && $brand->id == $booking->brand_id)
                                                    || (!isset($booking) && $brand->id == $defSource->brand_id)){{'selected'}}@endif>
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
                                            @if(isset($booking))
                                                <option selected
                                                        value="{{ $booking->user->id }}">{{ $booking->user->name }}</option>
                                            @else
                                                <option value="{{Auth::id()}}">{{Auth::user()->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif
                                <div class="p-2 flex-fill">
                                    <label class="mr-3">* {{_('Ετικέτες')}}:</label>
                                    @tags([
                                        'tags' => isset($booking) && $booking->tags ? $booking->tags : [],
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
                                                'companies' => isset($booking) && $booking->company ? [$booking->company] : [],
                                                'addBtn' => true,
                                                'editBtn' => true
                                            ])
                                            @endcompanySelector
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="booking_driver" id="booking_driver">
                                <div class="d-flex" id="driver1">
                                    <div class="p-2 flex-fill">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ _('Οδηγός') }}:</span>
                                            </div>
                                            <div class="input-group-prepend flex-grow-1">
                                                @typingSelector([
                                                    'id' => 'customer_id',
                                                    'name' => 'customer',
                                                    'searchUrl' => 'searchDriverAndContactUrl',
                                                    'value_field' => 'customer_id',
                                                    'text_field' => 'full_name',
                                                    'option' => isset($booking) && $booking->customer ? $booking->customer : null,
                                                    'text' => isset($booking) ? $booking->customer_text : null,
                                                    'addBtn' => true,
                                                    'modal' => 'drivers.contact',
                                                    'required' => true,
                                                    'extra_fields' => ['phone']
                                                ])
                                                @endtypingSelector
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
                                                <input id="phone" name="phone" pattern="\+?[0-9]{0,}" class="form-control" type="tel" @if(isset($booking)) value="{{ $booking->phone }}" @endif />
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
                        <div class="card-body d-flex flex-wrap">
                            <div class="p-2 flex-fill">
                                <div class="input-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ _('Συνεργάτης') }}</span>
                                    </div>
                                    <div class="input-group-prepend flex-grow-1" id="agent_block">
                                        @agentSelector([
                                            'id' => 'agent_id',
                                            'name' => 'agent_id',
                                            'agents' => isset($booking) && $booking->agent ? [$booking->agent] : (!isset($booking) && $defSource->agent ? [$defSource->agent] : []),
                                            'extra_fields' => ['program_id', 'brand_id'],
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
                                            'sub_accounts' => isset($booking) && $booking->sub_account_normalized ? [$booking->sub_account_normalized] : [],
                                            'addBtn' => true,
                                            'editBtn' => true,
                                            'depends' => ['parent_agent' => 'agent_id'],
                                            'searchUrl' => 'searchSubAccountWithAgentUrl'
                                        ])
                                        @endsubaccountSelector
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 flex-fill">
                                <div class="input-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ _('Πρόγραμμα') }}:</span>
                                    </div>
                                    <div class="input-group-prepend flex-grow-1">
                                        <select name="program_id" id="program_id" class="form-control">
                                            @foreach (\App\Program::all() as $program)
                                                <option @if((isset($booking) && $booking->program_id ==$program->id) || (!isset($booking) && $defSource->program_id == $program->id)) selected @endif value="{{ $program->id }}">{{ $program->profile_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 flex-fill col-lg-12 col-xl-6">
                                <div class="input-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ _('Conf #') }}:</span>
                                    </div>
                                    <input type="text" class="form-control" name="confirmation_number" id="confirmation_number"
                                        @if(isset($booking) && $booking->confirmation_number)value="{{ $booking->confirmation_number }}"@endif/>
                                </div>
                            </div>
                            <div class="p-2 flex-fill col-lg-12 col-xl-6">
                                <div class="input-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ _('Voucher') }}:</span>
                                    </div>
                                    <input type="text" class="form-control" name="agent_voucher" id="agent_voucher"
                                        @if(isset($booking) && $booking->agent_voucher)value="{{ $booking->agent_voucher }}"@endif/>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card col-lg-12 col-xl-6">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Πληροφορίες Παράδοσης') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title checkout_datetime">
                                    <label
                                        class="mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @datetimepicker([
                                        'id' => 'checkout_datetime',
                                        'name' => 'checkout_datetime',
                                        'datetime' => isset($booking) ? $booking->checkout_datetime : \Carbon\Carbon::now()->toDateTimeString(),
                                        'required' => true
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="checkout_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @stationSelector([
                                        'id' => 'checkout_station_id',
                                        'name' => 'checkout_station_id',
                                        'stations' => isset($booking) ? [$booking->checkout_station] : []
                                    ])
                                    @endstationSelector
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3" for="checkout_place_id">{{ __('Τόπος') }}
                                        :</label>
                                </div>
                                <div class="">
                                    @placesSelector([
                                        'id' => 'checkout_place',
                                        'name' => 'checkout_place',
                                        'option' => isset($booking) && isset($booking->checkout_place) ? $booking->checkout_place : null,
                                        'text' => isset($booking) && $booking->checkout_place_text ? $booking->checkout_place_text : null,
                                        'addBtn' => true,
                                        'depends' => ['stations' => 'checkout_station_id']
                                    ])
                                    @endplacesSelector
                                </div>
                                <div class="col-md-3 input-group d-none">
                                    <input id="checkout_station_fee" name="checkout_station_fee"
                                        type="text" class="form-control float-input"
                                        value="@if(isset($booking)){{$booking->checkout_station_fee}}@else{{'0'}}@endif">
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
                                <div class="transfer_info_option">
                                    <input name="flight" id="flight"
                                           class="form-control" @if(isset($booking))value="{{$booking->flight}}"@endif />
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title ">
                                    <label class=" mr-3 text-nowrap" for="checkout_notes">{{ __('Σημειώσεις') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                     <textarea name="checkout_notes" id="checkout_notes"
                                               class="form-control">@if(isset($booking)){{$booking->checkout_comments}}@endif</textarea>
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
                                <div class="transfer_info_title checkin_datetime">
                                    <label
                                        class="mr-3"><strong>*&nbsp;</strong>{{ __('Ημερομηνία & ώρα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @datetimepicker([
                                        'id' => 'checkin_datetime',
                                        'name' => 'checkin_datetime',
                                        'datetime' => isset($booking) ? $booking->checkin_datetime : \Carbon\Carbon::now()->addDay()->toDateTimeString(),
                                        'required' => true
                                    ])
                                    @enddatetimepicker
                                </div>
                            </div>

                            <div class="d-lg-block d-xl-flex p-2">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="checkin_station_id"><strong>*&nbsp;</strong>{{ __('Σταθμός') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @stationSelector([
                                        'id' => 'checkin_station_id',
                                        'name' => 'checkin_station_id',
                                        'stations' => isset($booking) ? [$booking->checkin_station] : []
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
                                        'option' => isset($booking) && isset($booking->checkin_place) ? $booking->checkin_place : null,
                                        'text' => isset($booking) && $booking->checkin_place_text ? $booking->checkin_place_text : null,
                                        'addBtn' => true,
                                        'depends' => ['stations' => 'checkin_station_id']
                                    ])
                                    @endplacesSelector
                                </div>
                                <div class="col-md-3 input-group d-none">
                                    <input id="checkin_station_fee" name="checkin_station_fee"
                                        type="text" class="form-control float-input"
                                        value="@if(isset($booking)){{$booking->checkin_station_fee}}@else{{'0'}}@endif">
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
                                    class="form-control">@if(isset($booking)){{$booking->checkin_comments}}@endif</textarea>
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
                                        for="booking_group_id"><strong>*&nbsp;</strong>{{ __('Group') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    @groupSelector([
                                        'id' => 'booking_group_id',
                                        'name' => 'type_id',
                                        'groups' => isset($booking) && $booking->type ? [$booking->type] : [],
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
                                <input id="vehicle-model" class="form-control" @if(isset($booking) && $booking->vehicle)value="{{ $booking->vehicle->make." ".$booking->vehicle->model }}"@endif disabled />
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="vehicle_id">{{ __('Πινακίδα') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option" id="vehicles_block">
                                    @vehicleSelector([
                                        'id' => 'vehicle_id',
                                        'name' => 'vehicle_id',
                                        'vehicles' => isset($booking) && isset($booking->vehicle) ? [$booking->vehicle] : [],
                                        'required' => false,
                                        'depends' => ['group' => 'booking_group_id'],
                                        'query_fields' => [
                                            'from' => isset($checkout_datetime) ? $checkout_datetime : now(),
                                            'to' => isset($checkin_datetime) ? $checkin_datetime : now()->addDay()
                                        ]
                                    ])
                                    @endvehicleSelector
                                </div>
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3"
                                        for="excess">{{ __('Excess') }}
                                        :</label>
                                </div>
                                <div class="transfer_info_option">
                                    <input id="excess" name="excess"
                                        class="form-control float-input" @if(isset($booking) && $booking->excess)value="{{ $booking->excess }}"@endif>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="card col-lg-12 col-xl-6">
                        <div class="card-header">
                            <h3 class="m-0">{{ _('Πληροφορίες διάρκειας') }}</h3>
                        </div>
                        <div class="card-body d-flex flex-wrap">

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
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
                                            'value' => isset($booking) ? $booking->duration : 1,
                                            'from' => '#checkout_datetime',
                                            'to' => '#checkin_datetime',
                                            'extra_day' => true
                                        ])
                                        @endduration
                                    </div>
                                    <div class="transfer_info_option">
                                        @php
                                            if (isset($booking)) {
                                                $checkin_date = \Carbon\Carbon::parse(formatDate($booking->checkin_datetime, 'Y-m-d'));
                                                $checkout_date = \Carbon\Carbon::parse(formatDate($booking->checkout_datetime, 'Y-m-d'));
                                                $checkin_datetime = \Carbon\Carbon::parse($booking->checkin_datetime);
                                                $checkout_datetime = \Carbon\Carbon::parse($booking->checkout_datetime);
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
                                                    @if(isset($booking) && $booking->extra_day!=0){{"checked"}}@endif
                                                >
                                            <label for="extra_day" class="form-check-label text-nowrap"> {{ _('+ ημέρα') }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3" for="extension_rate">{{ __('Βασικό Μίσθωμα') }}:</label>
                                </div>
                                <div class="input-group">
                                    <input value="@if(isset($booking)){{ $booking->rental_fee }}@else{{ old('rental_fee', 0) }}@endif" id="rental_fee_info" name="rental_fee_info" type="text" class="form-control  float-input">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="transfer_info_title">
                                    <label class="mr-3" for="extension_rate">{{ __('Κόστος παράτασης') }}:</label>
                                </div>
                                <div class="transfer_info_option input-group">
                                    <input required id="extension_rate" name="extension_rate" type="text" class="form-control float-input"
                                        value="@if(isset($booking)){{$booking->extension_rate }}@else{{ old('extension_rate',0) }}@endif" >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6 {{ $mb }}">
                                <div class="form-check-inline may-extend-container">
                                    <input id="may_extend" name="may_extend" type="checkbox" class="form-control mr-2"
                                    @if(isset($booking) && $booking->may_extend!=0){{"checked"}}@endif
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
                            <textarea id="notes" name="notes" class="form-control">@if(isset($booking)){{$booking->comments}}@endif</textarea>
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
                                        @if(isset($booking))
                                            @foreach($booking->documents as $file)
                                                @document(['file' => $file, 'document_link_id' => $booking->id, 'document_link_type' => get_class($booking) ])
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
                if (isset($booking)) {
                    $args['model'] = $booking;
                }
                $args['model_type'] = 'booking';
                $args['duplicate'] = $duplicate;
            @endphp
            @include('template-parts.summary', $args)
        </div>
        @php
            $summary_charges_args = $args;
            $summary_charges_args['duplicate'] = isset($booking) && get_class($booking) == \App\Booking::class
                && !$duplicate ? false : true;
        @endphp
        @include('template-parts.summary-charges', $args)
    </div>
    @push('scripts')
        <script>
            @if(isset($booking) && !$duplicate)
                print_mail_url = "{{route('mail_booking_pdf', ['id'=>$booking->id, 'locale'=>$lng])}}";
            @endif

            const status_texts = {!! json_encode(\App\Booking::getStatusTexts()) !!}

            $('.booking-status-btn').on('click', function() {
                const statuses = $(this).closest('.booking-statuses').find('.booking-status-btn').each(function(i) {
                    $(this).removeClass('d-none');
                });
                $(this).addClass('d-none');
                const status = $(this).data('status');
                $('#booking-status').val(status);
                $('#status-text').text(status_texts[status]);
                $('#booking-status').trigger('change');
            });
        </script>
    @endpush
@endsection

@include('template-parts.bookingModal')
