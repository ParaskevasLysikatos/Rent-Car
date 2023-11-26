<div class="card-header">
    {{ (isset($_GET['cat_id']))?__('Επεξεργασία'): __('Προσθήκη') }}
</div>

<div class="card-body">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#companyinfotab">{{__('Πληροφορίες')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#drivertab">{{__('Οδηγοί')}}</a>
        </li>
    </ul>
    <form method="POST" action="{{ route('create_company', $lng ?? 'el') }}">
        @csrf
        <div class="tab-content">
            <div class="tab-pane container active" id="companyinfotab">
                @if(isset($company))
                    <input type="hidden" name="id" value="{{$company->id}}">
                @endif

                <label for="name">{{__('Επωνυμία')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $company->name }}">
                    @else
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}">
                    @endif
                </div>

                <label for="title">{{__('Διακριτικός Τίτλος')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $company->title }}">
                    @else
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title') }}">
                    @endif
                </div>

                <label for='job'>{{__('Δραστηριοτητα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="job" name="job"
                            value="{{ $company->job }}">
                    @else
                        <input type="text" class="form-control" id="job" name="job"
                            value="{{ old('job') }}">
                    @endif
                </div>

                <label for="afm">{{__('Α.Φ.Μ.')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ $company->afm }}">
                    @else
                        <input type="text" class="form-control" id="afm" name="afm"
                            value="{{ old('afm') }}">
                    @endif
                </div>

                <label for="foreign_afm">{{ __('Ξένο Α.Φ.Μ.') }}</label>
                <div class="input-group mb-3">
                    <input type="checkbox" id="foreign_afm" name="foreign_afm"
                        value="1" @if((isset($company) && $company->foreign_afm) || old('afm')) checked @endif>
                </div>

                <label for="doy">{{__('Δ.Ο.Υ.')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="doy" name="doy"
                            value="{{ $company->doy }}">
                    @else
                        <input type="text" class="form-control" id="doy" name="doy"
                            value="{{ old('doy') }}">
                    @endif
                </div>

                <label for="address">{{__('Διεύθυνση')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ $company->address }}">
                    @else
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ old('address') }}">
                    @endif
                </div>

                <label for="zip_code">{{__('Τ.Κ.')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                            value="{{ $company->zip_code }}">
                    @else
                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                            value="{{ old('zip_code') }}">
                    @endif
                </div>

                <label for="city">{{__('Πόλη')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ $company->city }}">
                    @else
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city') }}">
                    @endif
                </div>

                <label for="country">{{__('Χώρα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ $company->country }}">
                    @else
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ old('country') }}">
                    @endif
                </div>

                <label for='phone'>{{__('Τηλέφωνο')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ $company->phone }}">
                    @else
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone') }}">
                    @endif
                </div>

                <label for='phone_2'>{{__('Τηλέφωνο')}} 2</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="phone_2" name="phone_2"
                            value="{{ $company->phone_2 }}">
                    @else
                        <input type="text" class="form-control" id="phone_2" name="phone_2"
                            value="{{ old('phone_2') }}">
                    @endif
                </div>

                <label for='email'>{{__('E-mail')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ $company->email }}">
                    @else
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ old('email') }}">
                    @endif
                </div>

                <label for='website'>{{__('Ιστότοπος')}}</label>
                <div class="input-group mb-3">
                    @if(isset($company))
                        <input type="text" class="form-control" id="website" name="website"
                            value="{{ $company->website }}">
                    @else
                        <input type="text" class="form-control" id="website" name="website"
                            value="{{ old('website') }}">
                    @endif
                </div>

                <label for="checkout_notes">{{ __('Σχόλια') }}:</label>
                <div class="input-group mb-3">
                    <textarea name="checkout_notes" id="checkout_notes"
                            class="form-control">@if(isset($booking)){{$booking->checkout_notes}}@endif</textarea>
                </div>

            </div>
            <div class="tab-pane container" id="drivertab">
                <div class="company_drivers" id="company_drivers">
                    <div class="d-flex" id="driver1">
                        <div class="p-2 flex-fill">
                            <div class="input-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ _('Οδηγοί') }}:</span>
                                </div>
                                <div class="input-group-prepend flex-grow-1" id="drivers_block">
                                    @driverSelector([
                                        'name' => 'drivers',
                                        'drivers' => isset($company) ? $company->drivers : [],
                                        'multiple' => true
                                    ])
                                    @enddriverSelector
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{route('companies', $lng ?? 'el')}}"
                class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                <button type="submit"
                        class="btn btn-success float-right">{{ (isset($company))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
            </div>
        </form>
    </div>
</div>
