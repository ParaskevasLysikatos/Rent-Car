<!-- ============================================================== -->
<!-- Info box -->
<!-- ============================================================== -->
<div class="card-group">
    @php
        if(Auth::guard('web')->check()) {
            $link = route('superuser-doctors');
            $patients_link = route('superuser-patients');

        } else {
            $link = route('doctor-patients');
            $patients_link = route('doctor-patients');
        }
    @endphp
    <!-- Card -->
    @auth('web')
    <a class="card" href="{{ $link }}">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <span class="btn btn-circle btn-lg bg-danger">
                        <i class="fas fa-user-md text-white"></i>
                    </span>
                </div>
                <div>
                    {{ __('Γιατροί') }}
                </div>
                <div class="ml-auto">
                    <h2 class="m-b-0 font-light">{{$drs ?? ' '}}</h2>
                </div>
            </div>
        </div>
    </a>
    @endauth
    <!-- Card -->
    <!-- Card -->
    <a class="card" href="{{ $patients_link }}">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <span class="btn btn-circle btn-lg btn-info">
                        <i class="fas fa-user-injured"></i>
                    </span>
                </div>
                <div>
                    {{ __('Ασθενείς') }}
                </div>
                <div class="ml-auto">
                    <h2 class="m-b-0 font-light">{{ $pts ?? ' ' }}</h2>
                </div>
            </div>
        </div>
    </a>
    <!-- Card -->
    <!-- Card -->
    <a class="card" href="{{ $link }}">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <span class="btn btn-circle btn-lg bg-success">
                        <i class="fas fa-hospital text-white"></i>
                    </span>
                </div>
                <div>
                    {{ __('Επισκέψεις') }}
                </div>
                <div class="ml-auto">
                    <h2 class="m-b-0 font-light">{{ $visits ?? ' ' }}</h2>
                </div>
            </div>
        </div>
    </a>
    <!-- Card -->
    <!-- Card -->
    <a class="card break-card" href="{{ $link }}">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <span class="btn btn-circle btn-lg bg-warning">
                        <i class="fas fa-stethoscope text-white"></i>
                    </span>
                </div>
                <div>
                    {{ __('Εξετάσεις') }}
                </div>
                <div class="ml-auto">
                    <h2 class="m-b-0 font-light">{{ $exam ?? ' ' }}</h2>
                </div>
            </div>
        </div>
    </a>
    <!-- Card -->
    <a class="card" href="{{ $link }}">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="m-r-10">
                    <span class="btn btn-circle btn-lg bg-cyan">
                        <i class="fas fa-diagnoses text-white"></i>
                    </span>
                </div>
                <div>
                    {{ __('Γνωματεύσεις') }}
                </div>
                <div class="ml-auto">
                    <h2 class="m-b-0 font-light">{{ $results ?? ' ' }}</h2>
                </div>
            </div>
        </div>
    </a>
    <!-- Card -->
    <!-- Column -->


</div>
