@php
    $params = [
        'color' => 'primary',
        'modal' => $modal,
        'referer' => $referer,
        'add_fields' => $add_fields,
        'referer_type' => $referer_type
    ];
    if (isset($tabindex)) $params['tabindex'] = $tabindex;
    if (isset($class)) $params['class'] = $class;
    if(isset($tooltip) && $tooltip != false) $params['tooltip'] = $tooltip;
@endphp
@btnModal($params)
    <i class="fas fa-plus"></i> {{ $slot }}
@endbtnModal
