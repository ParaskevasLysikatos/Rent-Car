@php
    $params = [
        'name' => $name,
        'searchUrl' => 'searchPlaceUrl',
        'value_field' => 'id',
        'text_field' => 'profile_title',
        'option' => $option,
        'text' => $text
    ];
    if (isset($id)): $params['id'] = $id; endif;
    if (isset($depends)): $params['depends'] = $depends; endif;
    if (!isset($without_default) || $without_default !== true) {
        if (!$params['option'] && !$params['text']):
            $params['option'] = \App\Place::find(config('preferences.place_id')) ?? null;
            $params['text'] = $params['option'] ? $params['option']->{$params['text_field']} : null;
        endif;
    }
@endphp

@typingSelector($params)
@endtypingSelector
