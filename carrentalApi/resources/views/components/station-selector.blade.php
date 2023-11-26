@php
$params = [
    'name' => $name,
    'data' => $stations,
    'value' => 'id',
    'text' => 'profile_title',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#stations-form',
    'tooltip' => __('Προσθήκη νέου σταθμού'),
    'searchUrl' => 'searchStationUrl',
    'class' => 'App\Station',
    'modal' => 'stations.form',
    'model' => 'station'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (!isset($without_default) || $without_default !== true) {
    if (empty($params['data'])) {
        $station = !is_null(Auth::user()->station) ? Auth::user()->station : \App\Station::find(config('preferences.station_id'));
        if ($station) {
            $params['data'] = [$station];
        }
    }
}
@endphp

@component('components.selector', $params)
@endcomponent
