@php
    $name = Auth::user()->name ?? ' ';
    $surname = Auth::user()->surname ?? ' ';
    $email = Auth::user()->email ?? ' ';
@endphp
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="in">
                <!-- User Profile-->
                <li>
                    <!-- User Profile-->
                    <div class="user-profile dropdown m-t-20">
                        <div class="user-pic row justify-content-center align-items-center">
                            @if ( isset($img) )
                                <a href="">
                                    <img src="{{ URL::to('/files/doctor-') . Auth::id() .'/logo/'. $img }}"
                                         alt="users" class="rounded-circle img-fluid" style="max-width: 80%">
                                </a>
                            @else
                                <div class="no-image-holder">
                                    <div class="dummy"></div>
                                    <a href="">
                                        <p alt="users" class="rounded-circle img-fluid row align-items-center
                                            justify-content-center text-uppercase">
                                            {{ mb_substr($name, 0, 1) . mb_substr($surname, 0, 1) }}
                                        </p>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="user-content hide-menu m-t-10">
                            <a href="">
                                <h5 class="m-b-10 user-name font-medium">
                                    {{ $name. ' ' .$surname }}</h5>
                            </a>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>
                <!-- User Profile-->
                <li class="sidebar-item">
                    <a href="{{ url('/',  $lng ?? 'el') }}"
                       class="sidebar-link {{ request()->route()->named('home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span
                            class="hide-menu">{{ __('Αρχική') }}</span>
                    </a>
                </li>
                @if(Auth::user()->role->id != "service")
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-search-dollar"></i>
                            <span
                                class="hide-menu">{{ __('Συντομεύσεις') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('bookings', ['locale' => $lng ?? 'el', 'checkout_station_id' => Auth::user()->station_id ?? '',
                                    'checkout_datetime[from]' => date('Y-m-d'), 'checkout_datetime[to]' => date('Y-m-d'), 'shortlink' => '1' ,'status[]' => \App\Booking::STATUS_PENDING,
                                        'shortcut' =>'to_checkout', 'orderBy' => 'checkout_datetime', 'orderByType' => 'ASC', 'title' => 'Παραδόσεις - Checkout']) }}"
                                   class="sidebar-link {{ request()->route()->named('bookings') && request()->get('shortcut') == 'to_checkout' ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Παραδόσεις - Checkout')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('rentals', ['locale' => $lng ?? 'el', 'checkout_station_id' => Auth::user()->station_id ?? '',
                                    'checkout_datetime[from]' => date('Y-m-d'), 'checkout_datetime[to]' => date('Y-m-d'), 'shortlink' => '3' , 'status[]' => \App\Rental::STATUS_ACTIVE,
                                        'shortcut' =>'checkouts', 'title' => 'Σημερινές Μισθώσεις']) }}"
                                   class="sidebar-link {{ request()->route()->named('rentals') && request()->get('shortcut') == 'checkouts' ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Σημερινές Μισθώσεις')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('rentals', ['locale' => $lng ?? 'el', 'checkin_station_id' => Auth::user()->station_id ?? '', 'checkin_datetime[from]' => date('Y-m-d'),
                                    'shortlink' => '2' , 'checkin_datetime[to]' => date('Y-m-d'), 'orderBy' => 'checkin_datetime', 'orderByType' => 'ASC', 'shortcut' => 'checkins', 'title' => 'Παραλαβές - Checkin']) }}"
                                   class="sidebar-link {{ request()->route()->named('rentals') && request()->get('shortcut') == 'checkins' ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Παραλαβές - Checkin') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('rentals', ['locale' => $lng ?? 'el', 'status' => ['active'],
                                    'checkin_datetime[to]' => now()->subDay()->format('Y-m-d'), 'shortcut' => 'pending_checkins', 'title' => 'Εκρεμμείς Παραλαβές', 'orderBy' => 'checkin_datetime', 'orderByType' => 'ASC']) }}"
                                   class="sidebar-link {{ request()->route()->named('rentals') && request()->get('shortcut') == 'pending_checkins' ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Εκρεμμείς Παραλαβές') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-search-dollar"></i>
                            <span
                                class="hide-menu">{{ __('Προσφορές') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('quotes', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('quotes') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Προσφορές')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('create_quote_view', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('create_quote_view') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Νέα προσφορά') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-book"></i>
                            <span
                                class="hide-menu">{{ __('Κρατήσεις') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('bookings', ['locale' => $lng ?? 'el', 'orderBy' => 'checkout_datetime', 'orderByType' => 'ASC']) }}"
                                   class="sidebar-link {{ request()->route()->named('bookings') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Κρατήσεις')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('create_booking_view', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('create_booking_view') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Νέα κράτηση') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span
                                class="hide-menu">{{ __('Μισθώσεις') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('rentals', ['locale' => $lng ?? 'el']) }}"
                                   class="sidebar-link {{ request()->route()->named('rentals') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Μισθώσεις')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('create_rental_view', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('create_rental_view') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Νέα μίσθωση') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-euro-sign "></i>
                            <span
                                class="hide-menu">{{ __('Οικονομικά') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('payments', ['locale' => $lng ?? 'el', 'payment_type' => \App\Payment::PAYMENT_TYPE]) }}"
                                   class="sidebar-link {{ request()->is('*payments*') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Πληρωμές')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('create_payment_view', ['locale' => $lng ?? 'el', 'payment_type' => \App\Payment::PAYMENT_TYPE]) }}"
                                   class="sidebar-link {{ request()->route()->named('create_payment_view') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{ __('Νέα Είσπραξη') }}</span>
                                </a>
                            </li>
                            @if(Auth::user()->role->id == "root" || Auth::user()->role->id == 'administrator')
                                <li class="sidebar-item">
                                    <a href="{{ route('invoices', $lng ?? 'el') }}"
                                    class="sidebar-link {{ request()->is('*invoices') ? 'active' : '' }}">
                                    <span class="hide-menu">{{__('Παραστατικά')}}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('create_invoice_view', $lng ?? 'el') }}"
                                    class="sidebar-link {{ request()->route()->named('create_invoice_view') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Νέο παραστατικό') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="sidebar-item">
                                <a href="{{ route('tax-exemption', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('tax-exemption') ? 'active' : '' }}">
                                <span
                                    class="hide-menu">{{ __('Υπολογισμός ΦΠΑ') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-dollar-sign"></i>
                            <span
                                class="hide-menu">{{ __('Πελάτες') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*contacts*') ? 'active' : '' }}"
                                   href="{{ route('contacts', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Επαφές') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*drivers*') ? 'active' : '' }}"
                                   href="{{ route('drivers', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Οδηγοί') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*companies*') ? 'active' : '' }}"
                                   href="{{ route('companies', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Εταιρείες') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-handshake"></i>
                            <span
                                class="hide-menu">{{ __('Συνεργάτες') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*affiliate*') ? 'active' : '' }}"
                                   href="{{ route('agents', $lng ?? 'el') }}">
                                    <span class="hide-menu">{{ __('Πράκτορες') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-toolbox"></i>
                            <span
                                class="hide-menu">{{ __('Λειτουργίες') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('exchanges', ['locale' => $lng ?? 'el']) }}"
                                   class="sidebar-link {{ request()->route()->named('exchanges') ? 'active' : '' }}">

                             <span
                                 class="hide-menu">{{__('Αντικαταστάσεις Οχημάτων')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('transfers', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('transfers') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Μετακινήσεις') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('create_transfer_view', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->route()->named('create_transfer_view') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Νέα Μετακίνηση') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark">
                        <i class="fas fa-car"></i>
                        <span
                            class="hide-menu">{{ __('Στόλος Οχημάτων') }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('cars', $lng ?? 'el') }}"
                               class="sidebar-link {{ request()->is('*cars*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Λίστα Οχημάτων') }}</span>
                            </a>
                        </li>
                        @if(Auth::user()->role->id == "root" || Auth::user()->role->id == 'administrator')
                            <li class="sidebar-item">
                                <a href="{{ route('types', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->is('*/types*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Groups') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('options', ['locale' => $lng ?? 'el', 'option_type' => 'extras']) }}"
                                   class="sidebar-link {{ request()->is('*options/extras*') ? 'active' : '' }}">

                                    <span class="hide-menu">{{ __('Παροχές/Αξεσουάρ') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('characteristics',$lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->is('*characteristics*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Χαρακτηριστικά Αυτοκινήτου') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('categories', $lng ?? 'el') }}"
                                   class="sidebar-link {{ request()->is('*categories*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Κατηγορίες') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('options', ['locale' => $lng ?? 'el', 'option_type' => 'insurances']) }}"
                                   class="sidebar-link {{ request()->is('*options/insurances*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Ασφάλειες') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('options', ['locale' => $lng ?? 'el', 'option_type' => 'transport']) }}"
                                   class="sidebar-link {{ request()->is('*options/transports*') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Υπηρεσίες') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if(Auth::user()->role->id != "editor")
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-angle-double-right "></i>
                            <span
                                class="hide-menu">{{ __('Συνεργείο') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('visits', $lng ?? 'el') }}"
                                class="sidebar-link {{ request()->is('*visits*') ? 'active' : '' }}">
                                <span
                                    class="hide-menu">{{__('Επισκέψεις')}}</span>
                                </a>
                            </li>
                            <li class="sidebar-item d-block d-sm-block d-md-none">
                                <a href="{{ route('scanner', $lng ?? 'el') }}"
                                class="sidebar-link {{ request()->route()->named('scanner') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{ __('Νέα επίσκεψη') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->role->id == "root" || Auth::user()->role->id == 'administrator')
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-warehouse"></i>
                            <span
                                class="hide-menu">{{ __('Εταιρεία') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*company_preferences*') ? 'active' : '' }}"
                                   href="{{ route('company_preferences', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Η Εταιρεία μου') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*locations*') ? 'active' : '' }}"
                                   href="{{ route('locations', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Περιοχές') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*stations*') ? 'active' : '' }}"
                                   href="{{ route('stations', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Σταθμοί') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->is('*places*') ? 'active' : '' }}"
                                   href="{{ route('places', $lng ?? 'el') }}">
                                <span
                                    class="hide-menu">{{ __('Τοποθεσίες') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->role->id == "root" || Auth::user()->role->id == 'administrator')
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark">
                            <i class="fas fa-cogs"></i>
                            <span
                                class="hide-menu">{{ __('Ρυθμίσεις') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @if(Auth::user()->role->id != "service")
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*languages*') ? 'active' : '' }}"
                                    href="{{ route('languages', $lng ?? 'el') }}">
                                    <span
                                        class="hide-menu">{{ __('Γλώσσες') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('booking_sources', $lng ?? 'el') }}"
                                    class="sidebar-link {{ request()->route()->named('booking_sources') ? 'active' : '' }}">

                                <span
                                    class="hide-menu">{{__('Πηγές Κρατήσεων')}}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*rate_codes*') ? 'active' : '' }}"
                                    href="{{ route('rate_codes', $lng ?? 'el') }}">
                                    <span
                                        class="hide-menu">{{ __('Rate Codes') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*users*') ? 'active' : '' }}"
                                    href="{{ route('users', $lng ?? 'el') }}">
                                    <span
                                        class="hide-menu">{{ __('Χρήστες') }}</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*roles*') ? 'active' : '' }}"
                                    href="{{ route('roles', $lng ?? 'el') }}">
                                    <span
                                        class="hide-menu">{{ __('Ρόλοι') }}</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*documentType*') ? 'active' : '' }}"
                                    href="{{ route('documentTypes', $lng ?? 'el') }}">
                                        <span class="hide-menu">{{ __('Document Types') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*document*') ? 'active' : '' }}"
                                    href="{{ route('documents', $lng ?? 'el') }}">
                                        <span class="hide-menu">{{ __('Documents') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*status*') ? 'active' : '' }}"
                                    href="{{ route('status', $lng ?? 'el') }}">
                                        <span class="hide-menu">{{ __('Καταστάσεις Οχήματος') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*brand*') ? 'active' : '' }}"
                                    href="{{ route('brands', $lng ?? 'el') }}">
                                        <span class="hide-menu">{{ __('Brands') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link"
                                    target="_blank"
                                    href="{{ route('qrcode_genaerate_cars', $lng ?? 'el') }}">
                                        <span class="hide-menu">{{ __('QR Codes') }} {{__('Λίστα Οχημάτων')}}</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*photoshoot*') ? 'active' : '' }}"
                                    href="{{ route('photoshoot', ['locale'=> $lng ?? 'el', 'booking' => '10']) }}">
                                        <span class="hide-menu">{{ __('Photoshoot')}} (ID:10)</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->is('*color_types*') ? 'active' : '' }}"
                                    href="{{ route('color_types', ['locale'=> $lng ?? 'el']) }}">
                                        <span class="hide-menu">{{ __('Χρώματα')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
            <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps-scrollbar-y-rail" style="top: 0px; height: 844px; right: 3px;">
            <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 246px;"></div>
        </div>
    </div>
    <!-- End Sidebar scroll-->
</aside>
