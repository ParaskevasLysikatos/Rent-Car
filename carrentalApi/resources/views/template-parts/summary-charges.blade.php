@php $i = -1; @endphp
@modal([
    'name' => 'rental-modal',
    'class' => 'prices-modal',
    'modal_class' => 'modal-xl',
    'disable_click' => true
    ])

    <?php
        $hidden_columns = (isset($model_type) && !in_array($model_type, ['quote', 'booking']))?true:false;
    ?>
    <div class="edit-content">
        <div class="card-header">
            Ανάλυση Μισθώματος
        </div>
        <table class="table options-table">
            <thead>
                <tr>
                    <th>{{ __('Τμχ') }}</th>
                    <th class="title-column">{{ __('Χρέωση') }}</th>
                    <th>{{ __('Είσπραξη από') }}</th>
                    <th class="price-column">{{ __('Ημερήσια Τιμή') }}</th>
                    <th class="price-column">{{ __('Καθαρή Αξία') }}</th>
                    <th class="price-column">{{ __('Τελικό Σύνολο') }}</th>
                    @if($hidden_columns)
                        <th>{{ __('Ημερομηνία έναρξης') }}</th>
                        <th>{{ __('Ημερομηνία λήξης') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $options_ids = []; @endphp
                @if (isset($model))
                    @foreach ($model->rental_charges as $option)
                        @php $i++; @endphp
                        <tr data-daily="{{ $option->option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->option->slug }}">
                            @if(!isset($duplicate) || !$duplicate)
                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}" />
                            @endif
                            <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->option_id }}" />
                            <input class="duration" type="hidden" name="options[{{ $i }}][duration]" value="{{ $option->duration }}" />
                            <td>
                                <input @if($option->option->active_daily_cost) type="checkbox" @if($option->quantity > 0) checked @endif @else hidden @endif
                                    @if(Auth::user()->role->id != 'root' && Auth::user()->role->id != 'administrator' && $option->option->slug != 'additional-rental') disabled @endif/>
                                <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="{{ $option->quantity }}" />
                            </td>
                            <td>{{ $option->option->profile_title }}</td>
                            <td>
                                <select name="options[{{ $i }}][payer]" class="form-control payers">
                                    <option value="{{ $option->payer }}"

                                        selected>{{ $option->payer == 'driver' ? $model->customer_text : ($option->payer == 'company' ? $model->company->name : $model->agent->name) }}</option>
                                </select>
                            </td>
                            <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->rate }}" /></td>
                            <td><input readonly class="form-control net float-input" value="{{ $option->net }}" /></td>
                            <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="{{ $option->gross }}" /></td>
                            @if($hidden_columns)
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][start]',
                                    'class' => 'start',
                                    'datetime' => $option->start
                                ])
                                @enddatetimepicker
                            </td>
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][end]',
                                    'class' => 'end',
                                    'datetime' => $option->end
                                ])
                                @enddatetimepicker
                            </td>
                            @endif
                            {{-- <td><input name="options['.$option->option_id.'][start]" class="form-control datepicker" value="{{ $option->start }}" /></td>
                            <td><input name="options['.$option->option_id.'][end]" class="form-control datetimepicker" value="{{ formatDatetime($option->end) }}" /></td> --}}
                        </tr>
                    @endforeach
                    @php $options_ids = $model->options->pluck('option_id'); @endphp
                @endif
                @foreach (\App\Option::where("option_type", 'rental_charges')->orderBy('options.order')->whereNotIn('id', $options_ids)->get() as $option)
                    @php $i++; @endphp
                    <tr data-daily="{{ $option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->slug }}">
                        <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->id }}"/>
                        <input class="duration" type="hidden" name="options[{{ $i }}][duration]"/>
                        <input class="quantity" type="hidden" name="options[{{ $i }}][quantity]" value="@if($option->slug == 'rental') 1 @else 0 @endif" />
                        <td>
                            <input @if($option->active_daily_cost) type="checkbox" @if($option->slug == 'rental') checked @endif @else hidden @endif
                                @if(Auth::user()->role->id != 'root' && Auth::user()->role->id != 'administrator' && $option->slug != 'additional-rental') disabled @endif/>
                            <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" @if($option->slug != 'additional-mileage' && $option->slug != 'additional-rental') value="1"@else value="0" @endif />
                        </td>
                        <td>{{ $option->profile_title }}</td>
                        <td><select name="options[{{ $i }}][payer]" class="form-control payers"></select></td>
                        <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->cost_daily ?? 0 }}" /></td>
                        <td><input readonly class="form-control net float-input" value="0" /></td>
                        <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="0" /></td>
                        @if($hidden_columns)
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][start]',
                                'class' => 'start'
                            ])
                            @enddatetimepicker
                        </td>
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][end]',
                                'class' => 'end'
                            ])
                            @enddatetimepicker
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(!isset($model_type) || $model_type != 'rental')
            <hr/>
            <div class="pl-3 pr-3">
                <div class="{{ $mb }} form-inline">
                    <label for="vat_included" class="pr-2">Συμπεριλαμβάνει ΦΠΑ</label>
                    <input id="vat_included" name="vat_included" type="checkbox" @if(isset($model)){{$model->vat_included ? 'checked' : ''}}@else{{old('vat_included', true) ? 'checked' : ''}}@endif />
                </div>
            </div>
        @endif
    </div>
    <div class="card-footer mt-5">
        <button type="button" class="btn btn-warning btn-cancel text-left">{{ __('Ακύρωση') }}</button>
        <button type="button" class="btn btn-success float-right">{{ __('Αποθήκευση') }}</button>
    </div>
@endmodal
@modal([
    'name' => 'transport-modal',
    'class' => 'prices-modal',
    'modal_class' => 'modal-xl',
    'disable_click' => true
    ])
    <div class="edit-content">
        <div class="card-header">
            Υπηρεσίες
        </div>
        <table class="table options-table">
            <thead>
                <tr>
                    <th class="price-column">{{ __('Τμχ') }}</th>
                    <th class="title-column">{{ __('Υπηρεσία') }}</th>
                    <th>{{ __('Είσπραξη από') }}</th>
                    <th class="price-column">{{ __('Ημερήσια Τιμή') }}</th>
                    <th class="price-column">{{ __('Καθαρή Αξία') }}</th>
                    <th class="price-column">{{ __('Τελικό Σύνολο') }}</th>
                    @if($hidden_columns)
                    <th>{{ __('Ημερομηνία έναρξης') }}</th>
                    <th>{{ __('Ημερομηνία λήξης') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $options_ids = []; @endphp
                @if (isset($model))
                    @foreach ($model->transports as $option)
                        @php $i++; @endphp
                        <tr data-daily="{{ $option->option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->option->slug }}">
                            @if(!isset($duplicate) || !$duplicate)
                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}" />
                            @endif
                            <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->option_id }}" />
                            <input class="duration" type="hidden" name="options[{{ $i }}][duration]" value="{{ $option->duration }}" />
                            <td>
                                <input type="checkbox" @if($option->quantity > 0) checked @endif />
                                <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="{{ $option->quantity }}" />
                            </td>
                            <td>{{ $option->option->profile_title }}</td>
                            <td>
                                <select name="options[{{ $i }}][payer]" class="form-control payers">
                                    <option value="{{ $option->payer }}"
                                        selected>{{ $option->payer == 'driver' ? $model->customer_text : ($option->payer == 'company' ? $model->company->name : $model->agent->name) }}</option>
                                </select>
                            </td>
                            <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->rate }}" /></td>
                            <td><input readonly class="form-control net float-input" value="{{ $option->net }}" /></td>
                            <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="{{ $option->gross }}" /></td>
                            @if($hidden_columns)
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][start]',
                                    'class' => 'start',
                                    'datetime' => $option->start
                                ])
                                @enddatetimepicker
                            </td>
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][end]',
                                    'class' => 'end',
                                    'datetime' => $option->end
                                ])
                                @enddatetimepicker
                            </td>
                            @endif
                        </tr>
                        @php $i++; @endphp
                    @endforeach
                    @php $options_ids = $model->options->pluck('option_id'); @endphp
                @endif
                @foreach (\App\Option::where("option_type", 'transport')->orderBy('options.order')->whereNotIn('id', $options_ids)->get() as $option)
                    @php $i++; @endphp
                    <tr data-daily="{{ $option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->slug }}">
                        <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->id }}"/>
                        <input class="duration" type="hidden" name="options[{{ $i }}][duration]"/>
                        <td>
                            <input type="checkbox" @if($option->default_on) checked @endif />
                            <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="@if($option->default_on){{ 1 }}@else{{ 0 }}@endif" />
                        </td>
                        <td>{{ $option->profile_title }}</td>
                        <td><select name="options[{{ $i }}][payer]" class="form-control payers"></select></td>
                        <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->cost_daily }}" /></td>
                        <td><input readonly class="form-control net float-input" value="0" /></td>
                        <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="0" /></td>
                        @if($hidden_columns)
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][start]',
                                'class' => 'start'
                            ])
                            @enddatetimepicker
                        </td>
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][end]',
                                'class' => 'end'
                            ])
                            @enddatetimepicker
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer mt-5">
        <button type="button" class="btn btn-warning btn-cancel text-left">{{ __('Ακύρωση') }}</button>
        <button type="button" class="btn btn-success float-right">{{ __('Αποθήκευση') }}</button>
    </div>
@endmodal
@modal([
    'name' => 'extras-modal',
    'class' => 'prices-modal',
    'modal_class' => 'modal-xl',
    'disable_click' => true
    ])
    <div class="edit-content">
        <div class="card-header">
            Παροχές
        </div>
        <table class="table options-table">
            <thead>
                <tr>
                    <th class="price-column">{{ __('Τμχ') }}</th>
                    <th class="title-column">{{ __('Παροχή') }}</th>
                    <th>{{ __('Είσπραξη από') }}</th>
                    <th class="price-column">{{ __('Ημερήσια Τιμή') }}</th>
                    <th class="price-column">{{ __('Καθαρή Αξία') }}</th>
                    <th class="price-column">{{ __('Τελικό Σύνολο') }}</th>
                    @if($hidden_columns)
                    <th>{{ __('Ημερομηνία έναρξης') }}</th>
                    <th>{{ __('Ημερομηνία λήξης') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $options_ids = []; @endphp
                @if (isset($model))
                    @foreach ($model->extras as $option)
                        @php $i++; @endphp
                        <tr data-daily="{{ $option->option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->option->slug }}">
                            @if(!isset($duplicate) || !$duplicate)
                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}" />
                            @endif
                            <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->option_id }}" />
                            <input class="duration" type="hidden" name="options[{{ $i }}][duration]" value="{{ $option->duration }}" />
                            <td><input name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="{{ $option->quantity }}" /></td>
                            <td>{{ $option->option->profile_title }}</td>
                            <td>
                                <select name="options[{{ $i }}][payer]" class="form-control payers">
                                    <option value="{{ $option->payer }}"
                                        selected>{{ $option->payer == 'driver' ? $model->customer_text : ($option->payer == 'company' ? $model->company->name : $model->agent->name) }}</option>
                                </select>
                            </td>
                            <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->rate }}" /></td>
                            <td><input readonly class="form-control net float-input" value="{{ $option->net }}" /></td>
                            <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="{{ $option->gross }}" /></td>
                            @if($hidden_columns)
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][start]',
                                    'class' => 'start',
                                    'datetime' => $option->start
                                ])
                                @enddatetimepicker
                            </td>
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][end]',
                                    'class' => 'end',
                                    'datetime' => $option->end
                                ])
                                @enddatetimepicker
                            </td>
                             @endif
                        </tr>
                    @endforeach
                    @php $options_ids = $model->options->pluck('option_id'); @endphp
                @endif
                @foreach (\App\Option::where("option_type", 'extras')->orderBy('options.order')->whereNotIn('id', $options_ids)->get() as $option)
                    @php $i++; @endphp
                    <tr data-daily="{{ $option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->slug }}">
                        <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->id }}"/>
                        <input class="duration" type="hidden" name="options[{{ $i }}][duration]"/>
                        <td><input name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="@if($option->default_on){{ 1 }}@else{{ 0 }}@endif" /></td>
                        <td>{{ $option->profile_title }}</td>
                        <td><select name="options[{{ $i }}][payer]" class="form-control payers"></select></td>
                        <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->cost_daily }}" /></td>
                        <td><input readonly class="form-control net float-input" value="0" /></td>
                        <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="0" /></td>
                        @if($hidden_columns)
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][start]',
                                'class' => 'start'
                            ])
                            @enddatetimepicker
                        </td>
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][end]',
                                'class' => 'end'
                            ])
                            @enddatetimepicker
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer mt-5">
        <button type="button" class="btn btn-warning btn-cancel text-left">{{ __('Ακύρωση') }}</button>
        <button type="button" class="btn btn-success float-right">{{ __('Αποθήκευση') }}</button>
    </div>
@endmodal
@modal([
    'name' => 'insurances-modal',
    'class' => 'prices-modal',
    'modal_class' => 'modal-xl',
    'disable_click' => true
    ])
    <div class="edit-content">
        <div class="card-header">
            Ασφάλειες
        </div>
        <table class="table options-table">
            <thead>
                <tr>
                    <th class="price-column">{{ __('Τμχ') }}</th>
                    <th class="title-column">{{ __('Ασφάλεια') }}</th>
                    <th>{{ __('Είσπραξη από') }}</th>
                    <th class="price-column">{{ __('Ημερήσια Τιμή') }}</th>
                    <th class="price-column">{{ __('Καθαρή Αξία') }}</th>
                    <th class="price-column">{{ __('Τελικό Σύνολο') }}</th>
                    @if($hidden_columns)
                    <th>{{ __('Ημερομηνία έναρξης') }}</th>
                    <th>{{ __('Ημερομηνία λήξης') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $option_ids = []; @endphp
                @if (isset($model))
                    @foreach ($model->insurances as $option)
                        @php $i++; @endphp
                        <tr data-daily="{{ $option->option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->option->slug }}">
                            @if(!isset($duplicate) || !$duplicate)
                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}" />
                            @endif
                            <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->option_id }}" />
                            <input class="duration" type="hidden" name="options[{{ $i }}][duration]" value="{{ $option->duration }}" />
                            <td>
                                <input type="checkbox" @if($option->quantity > 0) checked @endif />
                                <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="{{ $option->quantity }}" />
                            </td>
                            <td>{{ $option->option->profile_title }}</td>
                            <td>
                                {{ $option->payer }}
                                <select name="options[{{ $i }}][payer]" class="form-control payers">
                                    <option value="{{ $option->payer }}"
                                        selected
                                        >{{ $option->payer == 'driver' ? $model->customer_text : ($option->payer == 'company' ? $model->company->name : $model->agent->name) }}</option>
                                </select>
                            </td>
                            <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->rate }}" /></td>
                            <td><input readonly class="form-control net float-input" value="{{ $option->net }}" /></td>
                            <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="{{ $option->gross }}" /></td>
                            @if($hidden_columns)
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][start]',
                                    'class' => 'start',
                                    'datetime' => $option->start
                                ])
                                @enddatetimepicker
                            </td>
                            <td>
                                @datetimepicker([
                                    'name' => 'options['.$i.'][end]',
                                    'class' => 'end',
                                    'datetime' => $option->end
                                ])
                                @enddatetimepicker
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    @php $option_ids = $model->insurances->pluck('option_id'); @endphp
                @endif
                @foreach (\App\Option::where("option_type", 'insurances')->whereNotIn('id', $option_ids)->get() as $option)
                    @php $i++; @endphp
                    <tr data-daily="{{ $option->active_daily_cost ? 'true' : 'false' }}" id="option-{{ $option->slug }}">
                        <input type="hidden" name="options[{{ $i }}][option_id]" value="{{ $option->id }}"/>
                        <input class="duration" type="hidden" name="options[{{ $i }}][duration]"/>
                        <td>
                            <input type="checkbox" @if($option->default_on) checked @endif />
                            <input hidden name="options[{{ $i }}][quantity]" class="quantity form-control" type="number" min="0" value="@if($option->default_on){{ 1 }}@else{{ 0 }}@endif" />
                        </td>
                        <td>{{ $option->profile_title }}</td>
                        <td><select name="options[{{ $i }}][payer]" class="form-control payers"></select></td>
                        <td><input name="options[{{ $i }}][cost]" class="cost form-control float-input" value="{{ $option->cost_daily }}" /></td>
                        <td><input readonly class="form-control net float-input" value="0" /></td>
                        <td><input name="options[{{ $i }}][total-cost]" class="total-cost form-control float-input" value="0" /></td>
                        @if($hidden_columns)
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][start]',
                                'class' => 'start'
                            ])
                            @enddatetimepicker
                        </td>
                        <td>
                            @datetimepicker([
                                'name' => 'options['.$i.'][end]',
                                'class' => 'end'
                            ])
                            @enddatetimepicker
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer mt-5">
        <button type="button" class="btn btn-warning btn-cancel text-left">{{ __('Ακύρωση') }}</button>
        <button type="button" class="btn btn-success float-right">{{ __('Αποθήκευση') }}</button>
    </div>
@endmodal
