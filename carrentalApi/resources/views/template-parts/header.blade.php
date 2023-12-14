@php
$name = Auth::user()->name ?? ' ';
$surname = ' ';
$email = Auth::user()->email ?? ' ';
@endphp


<div id="main-wrapper" class="" data-layout="vertical" data-navbarbg="skin1" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header">
                <!-- This is for the sidebar toggle which is visible on mobile only -->
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                    <i class="ti-menu ti-close"></i>
                </a>
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <a class="navbar-brand" href="{{ URL::to('/') }}">
                    <!-- Logo icon -->
                    <b class="logo-icon">
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <img width="39px" src="{{ URL::to('/') }}/images/logo-sima.svg" alt="Logo"
                            class="dark-logo">
                        <!-- Light Logo icon -->
                        <img width="39px" src="{{ URL::to('/') }}/images/logo-sima.svg" alt="Logo"
                            class="light-logo">
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span class="logo-text">
                        <!-- dark Logo text -->
                        <img width="180px" src="{{ URL::to('/') }}/images/logo-lektiko-white.svg" alt="Text logo"
                            class="dark-logo">
                        <!-- Light Logo text -->
                        <img width="180px" src="{{ URL::to('/') }}/images/logo-lektiko-white.svg"
                            class="light-logo" alt="Text logo">
                    </span>
                </a>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                    data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ti-more"></i>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav float-left mr-auto">
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                            data-sidebartype="mini-sidebar">
                            <i class="sl-icon-menu font-20"></i>
                        </a>
                    </li>
                    <div class="nav-link search-input">
                        <i class="fas fa-search icon"></i>
                        <input type="text" placeholder="Search.."/>
                    </div>

                </ul>
                <!-- ============================================================== -->
                <!-- Right side toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav float-right">
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item">
                        <a href="{{route('scanner', $lng?? 'el')}}" class="nav-link waves-effect waves-dark btn d-inline-flex align-items-center" role="button">
                            <i class="fas fa-qrcode font-20 text-light"></i>
                            <span class="nav-link-text">{{ __('QR Code') }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark btn d-inline-flex align-items-center"
                            id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-settings font-20 text-light"></i><span
                                class="nav-link-text">{{ __(' Ρυθμίσεις') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout', $lng ?? 'el') }}"
                            class="nav-link d-inline-block">
                            @csrf
                            <button type="submit"
                                class="btn nav-link dropdown-toggle waves-effect waves-dark d-inline-flex align-items-center"
                                title="Logout" class="btn btn-circle btn-sm">
                                <i class="ti-power-off font-20 text-light"></i><span
                                    class="nav-link-text">{{ __(' Αποσύνδεση') }}</span>
                            </button>
                        </form>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <span class="with-arrow">
                                <span class="bg-secondary"></span>
                            </span>
                            <div class="d-flex no-block align-items-center p-15 bg-secondary text-white m-b-10">
                                <div class="ml-2 mr-2">
                                    @if (@isset($img))
                                    <img src="{{ $img }}" alt="user" class="img-circle" width="60">
                                    @else
                                    <p class="m-b-0 text-uppercase">
                                        {{ mb_substr($name, 0, 1) . ' ' . mb_substr($surname, 0, 1) }}</p>
                                    @endif
                                </div>
                                <div class="m-l-10">
                                    <h4 class="m-b-0">{{ $name. ' ' .$surname }}</h4>
                                    <p class=" m-b-0">{{ $email }}</p>
                                </div>
                            </div>
                            {{-- <a class="dropdown-item"
                                    href="{{route('edit_profile')}}">
                            <i class="ti-settings m-r-5 m-l-5"></i>{{ __('Ρυθμίσεις λογαριασμού') }}</a> --}}
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout', $lng ?? 'el') }}"
                                class="dropdown-item d-inline-block">
                                @csrf
                                <button type="submit" class="dropdown-item" title="Logout" class="">
                                    <i class="ti-power-off m-r-5 m-l-5"></i>
                                    {{ __(' Αποσύνδεση') }}
                                </button>
                            </form>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
