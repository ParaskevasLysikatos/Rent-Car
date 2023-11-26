@php
$params = [
    'name' => $name,
    'data' => $groups,
    'value' => 'id',
    'text' => 'international_title',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#types-form',
    'tooltip' => __('Προσθήκη νέου group'),
    'searchUrl' => 'searchGroupUrl',
    'class' => 'App\Type',
    'modal' => 'types.form',
    'model' => 'type'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;

@endphp

@component('components.selector', $params)
@endcomponent
