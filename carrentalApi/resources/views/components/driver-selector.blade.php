@php
$params = [
    'name' => $name,
    'data' => $drivers,
    'value' => 'id',
    'text' => 'full_name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#drivers-form',
    'tooltip' => __('Προσθήκη νέου οδηγού'),
    'searchUrl' => 'searchDriverUrl',
    'class' => 'App\Driver',
    'modal' => 'drivers.form',
    'model' => 'driver',
    'disabled' => isset($disabled) && is_bool($disabled) ? $disabled : false,
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($query_fields)): $params['query_fields'] = $query_fields; endif;
if (isset($add_fields)): $params['add_fields'] = $add_fields; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
if (isset($disabled) && $disabled) {
    $params['addBtn'] = false;
}
@endphp

@component('components.selector', $params)
@endcomponent
