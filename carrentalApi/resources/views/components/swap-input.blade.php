@php $id = rand(); @endphp
<div id ="{{ $id }}" class="swap-container w-100">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($items as $item => $section)
            <li class="nav-item">
                <a class="nav-link swap-tab" data-toggle="tab" href="#{{ $section }}"
                role="tab" aria-controls="basic_car_info"
                aria-selected="true">{{__($item)}}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content swap-input">
        {{ $slot }}
    </div>
</div>
