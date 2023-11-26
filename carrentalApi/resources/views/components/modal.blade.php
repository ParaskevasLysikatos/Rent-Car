@php
    if (isset($disable_close) && $disable_close == true) {
        $disable_click = true;
    }
@endphp
<div id="{{ str_replace('.', '-', $name) }}" class="modal fade @if(isset($class)) {{ $class }} @endif" role="dialog" tabindex="-1"
    @if(isset($disable_click) && $disable_click === true) data-backdrop="static" data-keyboard="false" @endif>
    <div class="modal-dialog @if(isset($modal_class)) {{ $modal_class }} @else modal-lg @endif">
        <div class="modal-content">
            @if (!isset($disable_close) || $disable_close != true)
                <button type="button" class="close absolute" data-dismiss="modal">&times;
                </button>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
