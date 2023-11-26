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
@php
    if (isset($name)) {
        $name = explode(' ', $name);
        $lastname = array_shift($name);
        $firstname = implode(' ', $name);
    }
@endphp
<div class="card-header">
    {{ (isset($driver))?__('Επεξεργασία Οδηγού'): __('Προσθήκη Οδηγού') }}
</div>
<form class="driver_form" method="POST" action="{{ route('create_driver', $lng ?? 'el') }}" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#infotab">{{__('Πληροφορίες')}}</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#companytab">{{__('Εταιρείες')}}</a>
            </li> --}}
            @if (isset($driver))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#rentalstab">{{__('Μισθώσεις')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#invoicestab">{{__('Παραστατικά')}}</a>
                </li>
            @endif
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active" id="infotab">

                @if(isset($driver))
                    <input type="hidden" name="id" value="{{$driver->id}}">
                @endif

                @php
                    if (isset($contact_id)) {
                        $driver = \App\Contact::find($contact_id);
                    }
                @endphp

                @if(isset($user))
                    <input type="number" class="hide" id="user_id" name="user_id"
                        value="{{ $user->id }}" hidden>
                @endif

                <label for="lastname">{{__('Επώνυμο')}}*</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="lastname" name="lastname" required
                    @if(isset($driver))
                        value="{{ $driver->lastname }}"
                    @elseif(isset($user))
                        value="{{ split_name($user->name)[1] }}"
                    @elseif(isset($name))
                        value="{{ $lastname }}"
                    @else
                        value="{{ old('lastname') }}"
                    @endif
                    />
                </div>

                <label for="firstname">{{__('Όνομα')}}*</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="firstname" name="firstname" required
                    @if(isset($driver))
                        value="{{ $driver->firstname }}"
                    @elseif(isset($user))
                        value="{{ split_name($user->name)[0] }}"
                    @elseif(isset($name))
                        value="{{ $firstname }}"
                    @else
                        value="{{ old('firstname') }}"
                    @endif
                    />
                </div>

                <label for="email">{{__('E-mail')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ $driver->email }}">
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

                <label for="phone">{{__('Τηλέφωνο')}}</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="phone" name="phone"
                    @if(isset($driver))
                        value="{{ $driver->phone }}"
                    @elseif(isset($user))
                        value="{{ $user->phone }}"
                    @elseif(isset($phone))
                        value="{{ $phone }}"
                    @else
                        value="{{ old('phone') }}"
                    @endif
                    />
                </div>

                <label for="mobile">{{__('Τηλέφωνο 2')}}</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="mobile" name="mobile"
                    @if(isset($driver))
                        value="{{ $driver->mobile }}"
                    @elseif(isset($mobile))
                        value="{{ $mobile }}"
                    @else
                        value="{{ old('mobile') }}"
                    @endif
                    />
                </div>

                 <div class="mb-3">
                     <label>{{__('Ημερομηνία γέννησης')}}*</label>
                     <div class="input-group">
                         @if(isset($driver))
                             <input type="text" class="datepicker form-control" id="birthday" name="birthday"
                                    value="{{ formatDate($driver->birthday) }}">
                         @else
                             <input type="text" class="datepicker form-control" id="birthday" name="birthday"
                                    value="{{ formatDate(old('birthday')) }}">
                         @endif
                         <div class="input-group-append d-flex align-self-center justify-content-center">
                             <small id="age_number" class="ml-2" data-title="{{ __('Χρονών') }}"></small>
                         </div>
                     </div>
                 </div>

                <label for="birth_place">{{__('Τόπος γέννησης')}}*</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="birth_place" name="birth_place"
                            value="{{ $driver->birth_place }}">
                    @else
                        <input type="text" class="form-control" id="birth_place" name="birth_place"
                            value="{{ old('birth_place') }}">
                    @endif
                </div>

                <label for="phone">{{__('Στοιχεία οδηγού')}}</label>
                <div class="card card-body">

                    <label for="licence_number">{{__('Αριθμός Διπλώματος')}}*</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="form-control" id="licence_number" name="licence_number" required
                                value="{{ $driver->licence_number }}">
                        @else
                            <input type="text" class="form-control" id="licence_number" name="licence_number" required
                                value="{{ old('licence_number') }}">
                        @endif
                    </div>

                    <label for="licence_created">{{__('Ημ. Έκδοσης Διπλώματος')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="datepicker form-control" id="licence_created" name="licence_created"
                                value="{{ formatDate($driver->licence_created) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="licence_created" name="licence_created"
                                value="{{ formatDate(old('licence_created')) }}">
                        @endif
                    </div>

                    <label for="licence_expire">{{__('Ημ. Λήξης Διπλώματος')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="datepicker form-control" id="licence_expire" name="licence_expire"
                                value="{{ formatDate($driver->licence_expire) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="licence_expire" name="licence_expire"
                                value="{{ formatDate(old('licence_expire')) }}">
                        @endif
                    </div>

                    <label for="licence_country">{{__('Χώρα/Πόλη Έκδοσης Διπλώματος')}}*</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="form-control" id="licence_country" name="licence_country"
                                value="{{ $driver->licence_country }}">
                        @else
                            <input type="text" class="form-control" id="licence_country" name="licence_country"
                                value="{{ old('licence_country') }}">
                        @endif
                    </div>

                    <hr/>

                    <label for="identification_number">{{__('Αρ. Ταυτότητας ή Διαβατηρίου')}}*</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="form-control" id="identification_number" name="identification_number" required
                                value="{{ $driver->identification_number }}">
                        @else
                            <input type="text" class="form-control" id="identification_number" name="identification_number" required
                                value="{{ old('identification_number') }}">
                        @endif
                    </div>

                    <label for="identification_created">{{__('Ημ. Έκδοσης Ταυτότητας ή Διαβατηρίου')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="datepicker form-control" id="identification_created" name="identification_created"
                                value="{{ formatDate($driver->identification_created) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="identification_created" name="identification_created"
                                value="{{ formatDate(old('identification_created')) }}">
                        @endif
                    </div>

                    <label for="identification_expire">{{__('Ημ. Λήξης Ταυτότητας ή Διαβατηρίου')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="datepicker form-control" id="identification_expire" name="identification_expire"
                                value="{{ formatDate($driver->identification_expire) }}">
                        @else
                            <input type="text" class="datepicker form-control" id="identification_expire" name="identification_expire"
                                value="{{ formatDate(old('identification_expire')) }}">
                        @endif
                    </div>

                    <label for="identification_country">{{__('Χώρα/Πόλη Έκδοσης Ταυτότητας ή Διαβατηρίου')}}</label>
                    <div class="input-group mb-3">
                        @if(isset($driver))
                            <input type="text" class="form-control" id="identification_country" name="identification_country"
                                value="{{ $driver->identification_country }}">
                        @else
                            <input type="text" class="form-control" id="identification_country" name="identification_country"
                                value="{{ old('identification_country') }}">
                        @endif
                    </div>
                </div>

                <label for="address">{{__('Διεύθυνση')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ $driver->address }}">
                    @else
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address') }}">
                    @endif
                </div>

                <label for="zip">{{__('Τ.Κ.')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="zip" name="zip"
                            value="{{ $driver->zip }}">
                    @else
                        <input type="text" class="form-control" id="zip" name="zip"
                            value="{{ old('zip') }}">
                    @endif
                </div>

                <label for="city">{{__('Πόλη')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ $driver->city }}">
                    @else
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city') }}">
                    @endif
                </div>

                <label for="country">{{__('Χώρα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ $driver->country }}">
                    @else
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ old('country') }}">
                    @endif
                </div>

                <label for="afm">{{__('ΑΦΜ')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ $driver->afm }}">
                    @else
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ old('afm') }}">
                    @endif
                </div>

                <label for="role">{{__('Ρόλος')}}</label>
                <div class="input-group mb-3">
                    <select class="form-control" name="role" id="role">
                        @foreach (App\Driver::AVAILABLE_ROLES as $role)
                            <option value="{{ $role }}" @if(isset($driver) && strcasecmp($driver->role, $role) == 0) selected @endif>{{ __($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <label for="notes">{{__('Επιπλέον Πληροφορίες')}}</label>
                <div class="input-group mb-3">
                    @if(isset($driver))
                        <textarea class="form-control" id="notes" name="notes">{{$driver->notes}}</textarea>
                    @else
                        <textarea class="form-control" id="notes" name="notes">{{ old('notes') }}</textarea>
                    @endif
                </div>

                @if(isset($driver) && $driver->users && $driver->users->count()>0)
                    <label for="password">{{__('Συνδεδεμένος Χρήστης')}}</label>
                    @foreach($driver->users as $user)
                        <div class="input-group mb-1">
                            <a class="btn btn-info" href="{{route('edit_user_view', ['locale'=> $lng ?? 'el', 'cat_id'=>$user->id])}}">{{$user->name}}</a>
                        </div>
                        <hr>
                    @endforeach
                @endif

                @documents([
                    'model' => isset($driver) ? $driver : null
                ])
                @enddocuments
            </div>
            <div class="tab-pane container" id="companytab">
                <div class="driver_companies">
                    <div class="d-flex" id="company">
                        <div class="p-2 flex-fill">
                            <div class="input-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ _('Εταιρείες') }}:</span>
                                </div>
                                <div class="input-group-prepend flex-grow-1" id="company_block">
                                    @companySelector([
                                        'name' => 'companies',
                                        'companies' => isset($driver) && $driver->companies ? $driver->companies : [],
                                        'multiple' => true
                                    ])
                                    @endcompanySelector
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (isset($driver) && get_class($driver) == \App\Driver::class)
                <div class="tab-pane container" id="rentalstab">
                    <table class="table">
                        <tr>
                            {{-- <th>Μισθώσεις</th> --}}
                        </tr>
                        @foreach ($driver->rentals as $rental)
                            <tr>
                                <td><a target="_blank" href="{{ route('edit_rental_view', ['locale' => $lng, 'cat_id' => $rental->id]) }}">
                                    {{ $rental->sequence_number }}</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane container" id="invoicestab">
                    <table class="table">
                        <tr>
                            {{-- <th>Μισθώσεις</th> --}}
                        </tr>
                        @foreach ($driver->invoices as $invoice)
                            <tr>
                                <td><a target="_blank" href="{{ route('edit_invoice_view', ['locale' => $lng, 'cat_id' => $invoice->id]) }}">
                                    {{ $invoice->sequence_number }}</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer mt-5">
        <a href="{{route('drivers', $lng ?? 'el')}}"
        class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
        <button type="submit" class="btn btn-success float-right">{{ (isset($driver))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('.driver_form [name="contact"]').on('change', function (option) {
            option = $(this).find("option:selected");
            for (const key of Object.keys(option.data())) {
                const input = $('.driver_form').find('[name="'+key+'"]');
                if (input.length > 0) {
                    input.val(option.data(key));
                }
            }
        });
    });
</script>
