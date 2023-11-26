@php

@endphp
<div class="card-header">
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
</div>
<form method="POST" action="{{ route('create_user', $lng ?? 'el' ) }}"
      enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        @if(isset($user))
            <input type="hidden" name="id" value="{{$user->id}}">
        @endif

        <label for="name">{{__('Όνομα')}}</label>
        <div class="input-group mb-3">
            @if(isset($user))
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ $user->name }}">
            @else
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name') }}">
            @endif
        </div>

        <label for="username">{{__('Username')}}</label>
        <div class="input-group mb-3">
            @if(isset($user))
                <input type="text" class="form-control" id="username" name="username"
                       value="{{ $user->username }}">
            @else
                <input type="text" class="form-control" id="username" name="username"
                       value="{{ old('username') }}">
            @endif
        </div>

        <label for="email">{{__('E-mail')}}</label>
        <div class="input-group mb-3">
            @if(isset($user))
                <input type="text" class="form-control" id="email" name="email"
                       value="{{ $user->email }}">
            @else
                <input type="text" class="form-control" id="email" name="email"
                       value="{{ old('email') }}">
            @endif
        </div>

        <label for="phone">{{__('Τηλέφωνο')}}</label>
        <div class="input-group mb-3">
            @if(isset($user))
                <input type="text" class="form-control" id="phone" name="phone"
                       value="{{ $user->phone }}">
            @else
                <input type="text" class="form-control" id="phone" name="phone"
                       value="{{ old('phone') }}">
            @endif
        </div>

        <label for="role">{{__('Ρόλος')}}</label>
        <div class="input-group mb-3">
            @php $roles = App\UserRole::all(); @endphp
            @if($roles)
                <select class="form-control @error('role') is-invalid @enderror" id="role"
                        name="role">
                    <option value="-1">{{__('Επιλέξτε ρόλο')}}</option>
                    @foreach($roles as $role)

                        <option
                            {{ (isset($user) && $user->role_id == $role->id)? "selected":'' }}
                            value="{{$role->id}}" {{ (old("role") == $role->id ? "selected":"") }}>{{__($role->title)}}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <label for="password">{{__('Κωδικός')}}</label>
        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" autocomplete="current-password">
            <div class="input-group-prepend">
                <span id="trigger-password-view" class="input-group-text" data-status="disabled"><em class="fa fa-eye"></em></span>
            </div>
        </div>

        <label for="password">{{__('Συνδεδεμένος Οδηγός')}}</label>
        @driverSelector([
            'name' => 'driver_id',
            'drivers' => isset($user) && $user->driver ? [$user->driver] : [],
            'addBtn' => true,
            'query_fields' => [
                'role' => 'employee'
            ]
        ])
        @enddriverSelector

        <label for="password">{{__('Προεπιλεγμένος Σταθμός')}}</label>
        @stationSelector([
            'name' => 'station_id',
            'stations' => isset($user) && $user->station ? [$user->station] : []
        ])
        @endstationSelector

    </div>
    <div class="card-footer">
        <a href="{{route('users', $lng ?? 'el')}}"
           class="btn btn-warning text-left">{{__('Ακύρωση')}}</a>
        <button type="submit" class="btn btn-success float-right">{{ (isset($user))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
    </div>
</form>
