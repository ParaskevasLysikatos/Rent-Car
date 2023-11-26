@php
$params = [
    'name' => $name,
    'data' => $options,
    'value' => 'id',
    'text' => 'profile_title',
    'extra_fields' => [
        'active_daily_cost',
        'cost_daily',
        'cost_max',
        'cost'
    ],
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#options-form',
    'tooltip' => __('Προσθήκη νέου χαρακτηριστικού'),
    'searchUrl' => 'searchExtrasUrl',
    'class' => 'App\Option',
    'modal' => 'options.form',
    'model' => 'option'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($searchUrl)): $params['searchUrl'] = $searchUrl; endif;
@endphp

@component('components.selector', $params)
@endcomponent
