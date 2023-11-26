@extends ('layouts.tablePreview', [
'route' => route('transfers', $lng ?? 'el'),
'data' => $transfers,
'addButtonText' => 'Μετακίνησης'
])

@section ('title')
    {{ __('Μετακινήσεις') }}
@endsection

@section ('thead')
    <tr>
        <th class="text-center">
            <div class="d-flex">
                <input type="checkbox" class="form-check-inline" id="select_all" />A/A
            </div>
        </th>
        <th>{{ __('Πινακίδα') }}</th>
        <th>{{ __('Χρήστης') }}</th>
        <th>{{ __('Τύπος') }}</th>
        <th>{{ __('Εφυγε από') }}</th>
        <th>{{ __('Πήγε σε') }}</th>
        <th class="text-right">{{ __('Ενέργειες') }}</th>
    </tr>
@endsection

@section ('tbody')
    @foreach ($transfers as $index =>$transfer)

        @if(!is_null($transfer->car))

            <tr id="index_{{ $transfer->id }}">
                <td class="text-center">
                    <div class="d-flex">
                        <input type="checkbox" class="data_checkbox form-check-inline"
                            data-id="{{ $transfer->id }}" />
                            {{ ( ( $transfers->perPage() * $transfers->currentPage() )- $transfers->perPage() )+$index+1 }}
                    </div>
                </td>
                <td>
                    @if(!is_null($transfer->car))
                        @if(!is_null($transfer->car->getPlate()))
                            {{ $transfer->car->getPlate()->licence_plate ?? __('Μη μεταφρασμένο') }}
                        @endif
                    @endif
                </td>

                <td>
                    @if(!is_null($transfer->external_party))
                        {{$transfer->external_driver_name}} <small>({{ _("Εξωτερικός συνεργάτης") }})</small>
                    @else
                        {{$transfer->driver->full_name ?? ''}} <small>({{ _("Συνεργάτης") }})</small>
                    @endif
                </td>
                <td>{{ $transfer->type->title ?? '-' }}</td>
                <td>
                    {{$transfer->co_station->getProfileByLanguageId($lng)->title}}
                </td>

                <td>
                    @if(!is_null($transfer->ci_station) && !is_null($transfer->ci_station->getProfileByLanguageId($lng)))
                        {{$transfer->ci_station->getProfileByLanguageId($lng)->title}}
                    @endif
                </td>
                <td class="actions text-right">
                    @if(!$transfer->checkedIn())
                        <a href="{{route('create_transfer_view', ['locale' => $lng, 'cat_id'=>$transfer->id])}}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-calendar-check"> Check-in</i>
                        </a>
                    @endif
                    @include ('template-parts.actions', [
                    'route' => route('transfers', $lng ?? 'el'),
                    'data' => $transfer
                    ])
                </td>
            </tr>

        @endif
    @endforeach
@endsection
