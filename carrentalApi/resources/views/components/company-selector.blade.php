@php
$params = [
    'name' => $name,
    'data' => $companies,
    'value' => 'id',
    'text' => 'name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#companies-form',
    'tooltip' => __('Προσθήκη νέου brand'),
    'searchUrl' => 'searchCompanyUrl',
    'class' => 'App\Company',
    'modal' => 'companies.form',
    'model' => 'company'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
if (isset($depends)): $params['depends'] = $depends; endif;
@endphp

@component('components.selector', $params)
@endcomponent
