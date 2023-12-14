<div class="col-md-12 col-lg-4 sidebar-summery">
    <div class="card position-sticky">
        <div class="card-header">
            <h3 class="">{{ _('Περίληψη χρεώσεων') }}</h3>
            @php
                $save = isset($model) ? 'Αποθήκευση' : 'Δημιουργία'
            @endphp
            <div class="@if(isset($model_type) && ($model_type == 'rental' || $model_type == 'booking')){{'hide-me trigger-buttons'}}@endif">
                <button type="submit" name="save" class="btn btn-success w-100 mb-1" value="save">{{ __($save) }}</button>
                <div class="d-flex justify-content-between">
                    <button type="submit" name="save" class="btn btn-primary w-100 mr-1" value="save_and_close">{{ __("$save και Κλείσιμο") }}</button>
                    <button type="submit" name="save" class="btn btn-secondary w-100 ml-1" value="save_and_new">{{ __("$save και Νέο") }}</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(isset($model_type) && $model_type == 'rental')
                <div class="d-lg-block d-xl-flex pt-1 px-1">
                    <div class="transfer_info_title ">
                        <label class=" mr-3">{{ __('Man. Agr. #') }}:</label>
                    </div>
                    <div class="transfer_info_option">
                        <div class="form-check-inline">
                            <div class="input-group">
                                <input id="manual_agreement" name="manual_agreement" type="text" class="form-control "
                                @if(isset($model) && $model->manual_agreement)value="{{ $model->manual_agreement }}"@endif />
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($model_type) && $model_type == 'quote')
                <div class="d-lg-block d-xl-flex pt-1 px-1">
                    <div class="transfer_info_title ">
                        <label class=" mr-3">{{ __('CDP') }}:</label>
                    </div>
                    <div class="transfer_info_option">
                        <div class="form-check-inline">
                            <div class="input-group">
                                <input value="0" id="cdp" name="cdp" type="text" class="form-control  float-input">
                                <div class="input-group-prepend">
                                    <label class="form-control">€</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <label class=" mr-3">{{ __('GRP Χρέωσης') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            @groupSelector([
                                'id' => 'charge_type_id',
                                'name' => 'charge_type_id',
                                'groups' => isset($model) && $model->charge_type ? [$model->charge_type] : [],
                                'required' => true
                            ])
                            @endgroupSelector
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="distance">{{ __('Δωρεάν ΧΛΜ') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input id="distance" name="distance" type="number" class="form-control "
                            value="@if(isset($model)){{ $model->distance }}@else{{'0'}}@endif"
                            >
                            <div class="input-group-prepend">
                                <label class="form-control">km</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="distance_rate">{{ __('Κόστος Έξτρα ΧΛΜ') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input id="distance_rate" name="distance_rate" type="text" class="form-control  float-input"
                                   value="@if(isset($model)){{ $model->distance_rate }}@else{{'0'}}@endif">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="transfer_info_option">
                <div class="d-lg-block d-xl-flex pt-1 px-1">
                    <div class="transfer_info_title ">
                        <button type="button" class="btn btn-outline-secondary btn-prices" data-modal="rental-modal">{{ __('Βασικό Μίσθωμα') }}:</button>
                    </div>
                    <div class="input-group">
                        <input class="form-control " required id="rate" name="rate" type="text"
                            value="@if(isset($model)){{$model->rate }}@else{{ old('rate', 0) }}@endif" >
                        <input readonly class="form-control" value="@if(isset($model)){{ $model->rental_fee }}@else{{ old('rental_fee', 0) }}@endif" id="rental_fee" name="rental_fee" type="text">
                        <div class="input-group-prepend">
                            <label class="form-control">€</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <button type="button" class="btn btn-outline-secondary btn-prices" data-modal="transport-modal">{{ __('Υπηρεσίες') }}:</button>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input readonly value="@if(isset($model)){{$model->transport_fee}}@elseif(old('transport_fee')){{old('transport_fee')}}@else{{'0'}}@endif"
                                   id="transport_fee" name="transport_fee" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label for="discounted_price" class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <button type="button" class="btn btn-outline-secondary btn-prices" data-modal="insurances-modal">{{ __('Ασφάλιση') }}:</button>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input readonly value="@if(isset($model)){{$model->insurance_fee}}@elseif(old('insurance_fee')){{old('insurance_fee')}}@else{{'0'}}@endif" id="insurance_fee" name="insurance_fee" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <button type="button" class="btn btn-outline-secondary btn-prices" data-modal="extras-modal">{{ __('Παροχές') }}:</button>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input readonly value="@if(isset($model)){{$model->options_fee}}@elseif(old('options_fee')){{old('options_fee')}}@else{{'0'}}@endif" id="options_fee" name="options_fee" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1 @if(isset($model_type) && $model_type == 'quote') d-none @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="fuel_fee">{{ __('Καύσιμα') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="input-group w-100">
                        <select name="fuel_fee" id="fuel_fee" class="form-control">
                            <option value="-1">{{__('Επιλέξτε...')}}</option>
                            @foreach(config('ea.fuel_level_fees') as $fee =>$val)
                                <option value="{{$val}}"  @if(isset($model) && $model->fuel_fee==$val){{'selected'}}@endif >{{$fee}} - {{number_format($val, 2)}}€</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-1 px-1 @if(!isset($model_type) || $model_type != 'rental' || !isset($model) || !$model->extra_charges) d-none @else d-lg-block d-xl-flex @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="extra_charges">{{ __('Λοιπές χρεώσεις') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input value="@if(isset($model)){{$model->extra_charges}}@elseif(old('extra_charges')){{old('extra_charges')}}@else{{'0'}}@endif"
                                @if(Auth::user()->role_id != 'root') readonly @endif id="extra_charges" name="extra_charges" type="text" class="form-control float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1 @if(isset($model_type) && $model_type == 'quote') d-none @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="subcharges_fee">{{ __('Υποσύνολο') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input value="@if(isset($model)){{$model->subcharges_fee}}@elseif(old('subcharges_fee')){{old('subcharges_fee')}}@else{{'0'}}@endif"
                                readonly id="subcharges_fee" name="subcharges_fee" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="discount">{{ __('Έκπτωση') }}:</label>
                </div>
                <div class="d-flex w-100">
                    <div class="input-group">
                        <input value="@if(isset($model)){{$model->discount}}@elseif(old('discount')){{old('discount')}}@else{{'0'}}@endif"
                               id="discount" name="discount" type="text" class="form-control  float-input">
                        <div class="input-group-prepend">
                            <label for="discount" class="form-control">%</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <input value="0" readonly min="0" max="100" id="discounted_price" name="discounted_price" type="text"
                               class="form-control  float-input">
                        <div class="input-group-prepend">
                            <label for="discounted_price" class="form-control">€</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1 @if(isset($model_type) && $model_type == 'quote') d-none @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="total_net">{{ __('Καθαρό σύνολο') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input
                                value="@if(isset($model)){{$model->total_net}}@elseif(old('total_net')){{old('total_net')}}@else{{'0'}}@endif"
                                readonly id="total_net" name="total_net" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="@if(isset($model_type) && $model_type == 'quote') d-none @else d-lg-block d-xl-flex pt-1 px-1 @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="vat">{{ __('Φ.Π.Α.') }}:</label>
                </div>
               <div class="d-flex w-100">
                   <div class="input-group">
                       <input
                           value="@if(isset($model)){{$model->vat}}@elseif( old('vat') ){{old('vat')}}@else{{config('ea.vat')}}@endif"
                           id="vat" name="vat" type="text" class="form-control  float-input">
                       <div class="input-group-prepend">
                           <label class="form-control">%</label>
                       </div>
                   </div>
                   <div class="input-group">
                       <input readonly value="@if(isset($model)){{$model->vat_fee}}@else{{old('vat_fee', 0)}}@endif" id="vat_fee" name="vat_fee" type="text" class="form-control  float-input">
                       <div class="input-group-prepend">
                           <label class="form-control">€</label>
                       </div>
                   </div>
               </div>
            </div>


            <div class="d-lg-block d-xl-flex pt-1 px-1">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="total">{{ __('Τελικό Σύνολο') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input
                                value="@if(isset($model)){{$model->total}}@elseif(old('total')){{old('total')}}@else{{'0'}}@endif"
                                id="total" name="total" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-lg-block d-xl-flex pt-1 px-1 @if(isset($model_type) && $model_type == 'quote') d-none @endif">
                <div class="transfer_info_title ">
                    <label class=" mr-3" for="voucher">{{ __('Voucher') }}:</label>
                </div>
                <div class="transfer_info_option w-100">
                    <div class="form-check-inline w-100">
                        <div class="input-group">
                            <input readonly
                                value="@if(isset($model)){{$model->voucher}}@elseif(old('voucher')){{old('voucher')}}@else{{'0'}}@endif"
                                id="voucher" name="voucher" type="text" class="form-control  float-input">
                            <div class="input-group-prepend">
                                <label class="form-control">€</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-inline {{ $mb }} @if(isset($model_type) && $model_type == 'quote') d-none @else d-flex @endif">
                <div class="transfer_info_title text-right">
                    <label class="float-right mr-3" for="total_paid">{{ __('Πληρ.') }} / {{__('Υπ.')}}:</label>
                </div>
                <div class="input-group w-25 @if(isset($model_type) && isset($model_type) && $model_type == 'quote') d-none @endif">
                    <input readonly
                        value="@if(!$duplicate)@if(isset($model) && $model_type != 'quote')0 @elseif(old('total_paid')){{old('total_paid')}}@else{{'0'}}@endif @else {{ 0 }} @endif"
                        id="total_paid" name="total_paid" type="text" class="form-control text-right float-input">
                    <div class="input-group-prepend">
                        <label class="form-control">€</label>
                    </div>
                </div>
                <div class="input-group w-25">
                    <input readonly
                        value="@if(isset($model) && isset($model_type) && $model_type != 'quote')
                            @if ($duplicate)
                                {{$model->total}}
                            @else
                                {{ $model->total - ($model->getTotalPaid() ?? 0) }}
                            @endif
                        @elseif(old('balance')){{old('balance')}}@else{{'0'}}@endif"
                        id="balance" name="balance" type="text" class="form-control text-right float-input">
                    <div class="input-group-prepend">
                        <label class="form-control">€</label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        @if(!isset($model))
            $(document).ready(function () {
                $('#duration').trigger('date-change');
            });
        @endif
    </script>
@endpush
