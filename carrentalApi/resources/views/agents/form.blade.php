<div class="card-header">
    {{ (isset($agent))?__('Επεξεργασία'): __('Προσθήκη') }}
</div>
<form class="agent-container" method="POST" action="{{ route('create_agent', $lng ?? 'el') }}"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="agent_id" @if(isset($parent_agent) && $parent_agent) value="{{ $parent_agent->id }}"@elseif(isset($agent)) value="{{ $agent->agent_id }}" @endif />
    <div class="card-body">
        @php $rand = rand(); @endphp
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#info{{ $rand }}"
                role="tab"
                aria-selected="true">{{__('Πληροφορίες')}}</a>
            </li>
            @if(isset($agent) && $agent->agents->count() > 0)
                <li class="nav-item">
                    <a class="nav-link" id="subagents-tab" data-toggle="tab" href="#subagents{{ $rand }}"
                    role="tab"
                    aria-selected="false">{{__('Υποπράκτορες')}}</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="info{{ $rand }}" class="tab-pane fade show active">
                @if(isset($agent))
                    <input type="hidden" name="id" value="{{$agent->id}}">
                @endif


                <label for="name">{{__('Όνομα')}}</label>
                <div class="input-group mb-3">
                    @if(isset($agent))
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $agent->name }}">
                    @else
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}">
                    @endif
                </div>

                <label for="agent_source">{{__('Εταιρεία')}}</label>
                @companySelector([
                    'name' => 'company_id',
                    'companies' => isset($agent) && $agent->company ? [$agent->company] : [],
                    'addBtn' => true
                ])
                @endcompanySelector

                <label for="agent_source">{{__('Επαφές')}}</label>
                @contactSelector([
                    'name' => 'contacts',
                    'contacts' => isset($agent) && $agent->contacts ? $agent->contacts : [],
                    'addBtn' => true,
                    'editBtn' => true,
                    'multiple' => true
                ])
                @endcontactSelector

                <label for="commission">{{__('Προμήθεια')}}</label>
                <div class="input-group mb-3">
                    @if(isset($agent))
                        <input type="text" class="form-control" id="commission" name="commission"
                            value="{{ $agent->commission }}">
                    @else
                        <input type="text" class="form-control" id="commission" name="commission"
                            value="{{ old('commission') }}">
                    @endif
                </div>

                <label for="agent_source">{{__('Πηγή')}}</label>
                @sourceSelector([
                    'name' => 'booking_source_id',
                    'sources' => isset($agent) && $agent->booking_source ? [$agent->booking_source] : []
                ])
                @endsourceSelector

                @php $defSource = \App\BookingSource::find(config('preferences.booking_source_id')); @endphp
                <label for="agent_source">{{__('Επωνυμία')}}</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">-</option>
                    @foreach(App\Brand::all() as $brand)
                        <option
                            value="{{$brand->id}}" @if((isset($agent) && $brand->id == $agent->brand_id)
                                || (!isset($agent) && $brand->id == $defSource->brand_id)){{'selected'}}@endif>
                            @if(!is_null($brand->getProfileByLanguageId($lng)))
                                {{$brand->getProfileByLanguageId($lng)->title}}
                            @endif
                        </option>
                    @endforeach
                </select>

                <label for="agents">{{__('Υποπράκτορες')}}</label>
                @agentSelector([
                    'name' => 'agents',
                    'agents' => isset($agent) && $agent->agents ? $agent->agents : [],
                    'editBtn' => true,
                    'multiple' => true
                ])
                @endagentSelector

                <label for="sub_contacts">{{__('Πωλητές')}}</label>
                @contactSelector([
                    'name' => 'sub_contacts',
                    'contacts' => isset($agent) && $agent->sub_contacts ? $agent->sub_contacts : [],
                    'addBtn' => true,
                    'editBtn' => true,
                    'multiple' => true
                ])
                @endcontactSelector

                <label for="program_id">{{__('Πρόγραμμα')}}</label>
                <div class="input-group mb-3">
                    <select name="program_id" id="program_id" class="form-control">
                        <option @if(!isset($agent) || !$agent->program_id) selected @endif label="-"></option>
                        @foreach (\App\Program::all() as $program)
                            <option @if(isset($agent) && $agent->program_id ==$program->id ) selected @endif value="{{ $program->id }}">{{ $program->profile_title }}</option>
                        @endforeach
                    </select>
                </div>

                <label for="notes">{{__('Σημειώσεις')}}</label>
                <div class="input-group mb-3">
                    <textarea id="notes" name="notes" class="form-control">@if(isset($agent)){{$agent->comments}}@endif</textarea>
                </div>
                @documents([
                    'model' => isset($agent) ? $agent : null
                ])
                @enddocuments
                <div class="card-footer">
                    <a href="{{route('agents', $lng ?? 'el')}}"
                    class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                    <button type="submit" class="btn btn-success float-right">{{ (isset($agent))? __('Ενημέρωση') : __('Προσθήκη') }}</button>
                </div>
            </div>
            @if(isset($agent) && $agent->agents->count() > 0)
                <div id="subagents{{ $rand }}" class="tab-pane fade">
                    <table class="table table-hover table-striped listing-table">
                        <thead>
                            <tr>
                                <th>{{ __('Όνομα') }}</th>
                                <th>{{ __('Προμήθεια') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agent->agents as $sub_agent)
                                <tr>
                                    <td>{{ $sub_agent->name }}</td>
                                    <td>{{ $sub_agent->commission }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</form>
