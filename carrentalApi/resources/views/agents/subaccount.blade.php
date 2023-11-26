@php $rand = rand(); @endphp
<ul class="nav nav-tabs" id="myTab" role="tablist">
    @if (Auth::user()->role_id == 'root')
        <li class="nav-item">
            <a class="nav-link active" id="agent" data-toggle="tab" href="#agent{{ $rand }}"
            role="tab" aria-controls=""
            aria-selected="true">{{__('Πράκτορας')}}</a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link @if(Auth::user()->role_id != 'root') active @endif" data-toggle="tab" href="#contact{{ $rand }}">{{__('Πωλητής')}}</a>
    </li>
</ul>
@php $parent_agent = isset($parent_agent) && $parent_agent ? \App\Agent::find($parent_agent) : ''; @endphp
<div class="tab-content">
    @if (Auth::user()->role_id == 'root')
        <div id="agent{{ $rand }}" class="tab-pane fade show active">
            @include('agents.form', [
                'parent_agent' => $parent_agent
            ])
        </div>
    @endif
    <div id="contact{{ $rand }}" class="tab-pane fade @if(Auth::user()->role_id != 'root') show active @endif">
        @include('contacts.form', [
            'agent' => $parent_agent
        ])
    </div>
</div>
