@php $rand = rand(); @endphp
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="driver" data-toggle="tab" href="#driver{{ $rand }}"
        role="tab" aria-controls=""
        aria-selected="true">{{__('Οδηγός')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#contact{{ $rand }}">{{__('Επαφή')}}</a>
    </li>
</ul>
<div class="tab-content">
    <div id="driver{{ $rand }}" class="tab-pane fade show active">
        @include('drivers.form')
    </div>
    <div id="contact{{ $rand }}" class="tab-pane fade">
        @include('contacts.form')
    </div>
</div>
