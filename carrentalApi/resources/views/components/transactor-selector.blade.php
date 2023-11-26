@php
$params = [
    'name' => $name,
    'data' => $transactors,
    'value' => 'transactor_id',
    'text' => 'name',
    'extra_fields' => [
        'transactor_type',
        'debit',
    ],
    'multiple' => isset($multiple) ? $multiple : false,
    'tooltip' => __('Προσθήκη νέου οδηγού'),
    'searchUrl' => 'searchTransactorUrl',
    'html_class' => 'transactor'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
if (isset($query_fields)): $params['query_fields'] = $query_fields; endif;
if (isset($searchUrl)): $params['searchUrl'] = $searchUrl; endif;
if (isset($extra_fields)): $params['extra_fields'] = array_merge($params['extra_fields'],$extra_fields); endif;
if (isset($html_class)): $params['html_class'] = $html_class; endif;
@endphp

@component('components.selector', $params)
@endcomponent
