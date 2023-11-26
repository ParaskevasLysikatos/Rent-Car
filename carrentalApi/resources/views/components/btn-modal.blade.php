<button @if(isset($tabindex)) tabindex="{{ $tabindex }}" @endif type="button" class="btn btn-{{ $color }} btn-open-modal @if(isset($class)) {{ $class }} @endif" data-modal="{{ $modal }}" @if($referer) data-referer="{{ $referer }}" @endif
    data-add_fields='{!! json_encode($add_fields) !!}' @if($referer_type) data-referer_type="{{ $referer_type }}" @endif
    @if(isset($tooltip)) data-toggle="tooltip" data-placement="bottom" title="{{ __($tooltip) }}@endif">{{ $slot }}</button>
