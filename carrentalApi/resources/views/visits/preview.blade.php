@extends ('layouts.tablePreview', [
'route' => route('visits', $lng ?? 'el'),
'data' => $visits,
'addButtonText' => 'Επίσκεψης'
])
@section ('title')
    {{ __('Επισκέψεις') }}
@endsection
@section ('thead')
    <tr>
        <th class="text-center"><input type="checkbox" class="form-check-inline"
                                       id="select_all"/></th>
        <th>{{__('#')}}</th>
        <th>{{__('Πινακίδα')}}</th>
        <th>{{__('Αμάξι')}}</th>
        <th>{{__('Χιλιόμετρα')}}</th>
        <th>{{__('Ημερομηνία')}}</th>
        @foreach($details as $detail)
            <th>{{ __($detail->title) }}</th>
        @endforeach
        <th class="text-right">{{__('Ενέργειες')}}</th>
    </tr>
@endsection
@section ('tbody')
    @foreach($visits as $index => $visit)
        <tr id="index_{{$visit->id}}">
            <td class="text-center"><input type="checkbox"
                                           class="data_checkbox form-check-inline"
                                           data-id="{{$visit->id}}"/></td>
            <td>{{ ( ( $visits->perPage() * $visits->currentPage() )- $visits->perPage() )+$index+1 }}</td>
            <td class="license">
                @if(!is_null($visit->vehicle) && !is_null($visit->vehicle->license_plates))
                    @php
                        $many = '';
                        if (count($visit->vehicle->license_plates) > 1) {
                        $many = 'many';
                        }
                    @endphp
                @else
                    {{$many = ''}}
                @endif
                <ul class="{{ $many }} plates">
                    @if(!is_null($visit->vehicle) && !is_null($visit->vehicle->license_plates))
                        @foreach ($visit->vehicle->license_plates as $plate)
                            <li class="plate">{{ $plate->licence_plate }}</li>
                        @endforeach
                    @endif
                </ul>
            </td>
            <td>
                @if(!is_null($visit->vehicle))
                    @if(!is_null($visit->vehicle->getProfileByLanguageId($lng)))
                        {{$visit->vehicle->getProfileByLanguageId($lng)->title}}
                    @else
                        {{ $visit->vehicle->make }} {{ $visit->vehicle->model }}
                    @endif
                @else
                    {{__('Deleted')}}
                @endif
            </td>
            <td>{{$visit->km}}</td>
            <td>{{ date('d-m-Y', strtotime($visit->date_start)) }}</td>
            @foreach($details as $detail)
                <td>
                    @foreach($visit->visit_details as $vd)
                        @if($detail->id == $vd->service_details_id)
                            @if( !is_null($vd->status))
                                <span class="visit-check" data-title="{{ mb_substr($vd->status->title, 0, 1) }}">
                                    {{-- {{ mb_substr(__($vd->status->title), 0, 1, 'UTF-8') }} --}}
                                </span>

                            @else
                                {{ __('Πρόβλημα') }}
                            @endif
                        @endif
                    @endforeach
                </td>
            @endforeach
            <td class="actions">
                @if(Auth::user()->role->id !='service' || $visit->user_id == Auth::id())
                    @include ('template-parts.actions', [
                    'route' => route('visits', $lng ?? 'el'),
                    'data' => $visit
                    ])
                @endif
            </td>
        </tr>
    @endforeach
@endsection
