@php
$params = [
    'name' => $name,
    'data' => $users,
    'value' => 'id',
    'text' => 'name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#users-form',
    'tooltip' => __('Προσθήκη νέου χρήστη'),
    'searchUrl' => 'searchUserUrl',
    'class' => 'App\User',
    'modal' => 'users.form',
    'model' => 'user'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
@endphp

@component('components.selector', $params)
@endcomponent
