@php
$params = [
    'name' => $name,
    'data' => $vehicles,
    'value' => 'id',
    'text' => 'licence_plate',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#vehicle-form',
    'tooltip' => __('Προσθήκη νέου οχήματος'),
    'searchUrl' => 'searchVehicleUrl',
    'class' => 'App\Vehicle',
    'modal' => 'cars.form',
    'model' => 'car'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($query_fields)): $params['query_fields'] = $query_fields; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
@endphp

@component('components.selector', $params)
@endcomponent
