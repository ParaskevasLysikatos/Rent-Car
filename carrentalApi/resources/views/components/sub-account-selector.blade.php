@php
$params = [
    'name' => $name,
    'data' => $sub_accounts,
    'value' => 'account_id',
    'text' => 'name',
    'extra_fields' => [
        'model'
    ],
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#subaccount-form',
    'multiple' => isset($multiple) ? $multiple : false,
    'tooltip' => __('Προσθήκη νέου πωλητή/πράκτορα'),
    'modal' => 'agents.subaccount',
    'searchUrl' => 'searchSubAccountUrl',
    'html_class' => 'sub_account'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($query_fields)): $params['query_fields'] = $depends; endif;
if (isset($searchUrl)): $params['searchUrl'] = $searchUrl; endif;
if (isset($extra_fields)): $params['extra_fields'] = $extra_fields; endif;
@endphp

@component('components.selector', $params)
@endcomponent
