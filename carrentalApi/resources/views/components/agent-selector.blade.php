@php
$params = [
    'name' => $name,
    'data' => $agents,
    'value' => 'id',
    'text' => 'name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#agents-form',
    'tooltip' => __('Προσθήκη νέου agent'),
    'searchUrl' => 'searchAgentUrl',
    'class' => 'App\Agent',
    'modal' => 'agents.form',
    'model' => 'agent'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($except)): $params['except'] = $except; endif;
if (isset($searchUrl)): $params['searchUrl'] = $searchUrl; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
if (isset($query_fields)): $params['query_fields'] = $query_fields; endif;
if (Auth::user()->role_id != 'administrator' && Auth::user()->role_id != 'root') {
    $params['addBtn'] = false;
    $params['editBtn'] = false;
}
@endphp
@component('components.selector', $params)
@endcomponent
