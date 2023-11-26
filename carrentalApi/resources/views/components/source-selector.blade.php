@php
$params = [
    'name' => $name,
    'data' => $sources,
    'value' => 'id',
    'text' => 'profile_title',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#source-form',
    'tooltip' => __('Προσθήκη νέας πηγής'),
    'searchUrl' => 'searchSourceUrl',
    'class' => 'App\BookingSource',
    'modal' => 'booking_source.form',
    'model' => 'booking_source'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
if (Auth::user()->role_id != 'administrator' && Auth::user()->role_id != 'root') {
    $params['addBtn'] = false;
    $params['editBtn'] = false;
}
@endphp

@component('components.selector', $params)
@endcomponent
