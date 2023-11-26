@php
    if (!function_exists('split_name')) {
        function split_name($name) {
            $name = trim($name);
            $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
            return array($first_name, $last_name);
        }
    }
@endphp
<div class="card-header">
    {{ (isset($contact))?__('Επεξεργασία Επαφής'): __('Προσθήκη Επαφής') }}
</div>
<form method="POST" action="{{ route('create_contact', $lng ?? 'el') }}">
    @csrf
    @if(isset($parent_agent) && $parent_agent)
        <input type="hidden" name="agent_id"  value="{{ $parent_agent->id }}"/>
    @endif
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#infotab">{{__('Πληροφορίες')}}</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active" id="infotab">

                @if(isset($contact))
                    <input type="hidden" name="id" value="{{$contact->id}}">
                @endif

                @if(isset($user))
                    <input type="number" class="hide" id="user_id" name="user_id"
                        value="{{ $user->id }}" hidden>
                @endif

                <label for="last_name">{{__('Επώνυμο')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ $contact->lastname }}">
                    @else
                        @if(isset($user))
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ split_name($user->name)[1] }}">
                        @else
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ old('last_name') }}">
                        @endif
                    @endif
                </div>

                <label for="first_name">{{__('Όνομα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="{{ $contact->firstname }}">
                    @else
                        @if(isset($user))
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ split_name($user->name)[0] }}">
                        @else
                            <input type="text" class="form-control" id="first_name" name="first_name"
                            value="{{ old('first_name') }}">
                        @endif
                    @endif
                </div>

                <label for="phone">{{__('Τηλέφωνο')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ $contact->phone }}">
                    @else
                        @if(isset($user))
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ $user->phone }}">
                        @else
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone') }}">
                        @endif
                    @endif
                </div>

                <label for="mobile">{{__('Τηλέφωνο 2')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="mobile" name="mobile"
                            value="{{ $contact->mobile }}">
                    @else
                        <input type="text" class="form-control" id="mobile" name="mobile"
                            value="{{ old('mobile') }}">
                    @endif
                </div>

                <label for="email">{{__('E-mail')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ $contact->email }}">
                    @else
                        @if(isset($user))
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{ $user->email }}">
                        @else
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{ old('email') }}">
                        @endif
                    @endif
                </div>

                <label for="country">{{__('Χώρα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ $contact->country }}">
                    @else
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ old('country') }}">
                    @endif
                </div>

                <label for="city">{{__('Πόλη')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ $contact->city }}">
                    @else
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city') }}">
                    @endif
                </div>

                <label for="address">{{__('Διεύθυνση')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ $contact->address }}">
                    @else
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address') }}">
                    @endif
                </div>

                <label for="zip">{{__('Τ.Κ.')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="zip" name="zip"
                            value="{{ $contact->zip }}">
                    @else
                        <input type="text" class="form-control" id="zip" name="zip"
                            value="{{ old('zip') }}">
                    @endif
                </div>

                <label>{{__('Ημερομηνία γέννησης')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="datepicker form-control" id="birthday" name="birthday"
                            value="{{ formatDate($contact->birthday) }}">
                    @else
                        <input type="text" class="datepicker form-control" id="birthday" name="birthday"
                            value="{{ formatDate(old('birthday')) }}">
                    @endif
                </div>

                <label for="afm">{{__('ΑΦΜ')}}</label>
                <div class="input-group mb-3">
                    @if(isset($contact))
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ $contact->afm }}">
                    @else
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ old('afm') }}">
                    @endif
                </div>

                <label for="identification_number">{{__('Αρ. Ταυτότητας ή Διαβατηρίου')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($contact))
                            <input type="text" class="form-control" id="identification_number" name="identification_number"
                                value="{{ $contact->identification_number }}">
                        @else
                            <input type="text" class="form-control" id="identification_number" name="identification_number"
                                value="{{ old('identification_number') }}">
                        @endif
                    </div>

                    <label for="identification_created">{{__('Ημ. Έκδοσης')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($contact))
                            <input type="text" class="datepicker form-control" id="identification_created" name="identification_created"
                                value="{{ formatDate($contact->identification_created) }}">
                        @else
                            <input type="text" class="form-control" id="identification_created" name="identification_created"
                                value="{{ formatDate(old('identification_created')) }}">
                        @endif
                    </div>

                    <label for="identification_expire">{{__('Ημ. Λήξης')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($contact))
                            <input type="text" class="datepicker form-control" id="identification_expire" name="identification_expire"
                                value="{{ formatDate($contact->identification_expire) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="identification_expire" name="identification_expire"
                                value="{{ formatDate(old('identification_expire')) }}">
                        @endif
                    </div>

                    <label for="identification_country">{{__('Χώρα/Πόλη Έκδοσης')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($contact))
                            <input type="text" class="form-control" id="identification_country" name="identification_country"
                                value="{{ $contact->identification_country }}">
                        @else
                            <input type="text" class="form-control" id="identification_country" name="identification_country"
                                value="{{ old('identification_country') }}">
                        @endif
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
