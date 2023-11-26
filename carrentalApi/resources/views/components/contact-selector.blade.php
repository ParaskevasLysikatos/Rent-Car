@php
$params = [
    'name' => $name,
    'data' => $contacts,
    'value' => 'id',
    'text' => 'full_name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#contact-form',
    'tooltip' => __('Προσθήκη νέας επαφής'),
    'searchUrl' => 'searchContactUrl',
    'class' => 'App\Contact',
    'modal' => 'contacts.form',
    'model' => 'contact'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($query_fields)): $params['query_fields'] = $query_fields; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
@endphp

@component('components.selector', $params)
@endcomponent
