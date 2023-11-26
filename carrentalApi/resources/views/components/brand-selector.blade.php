@php
$params = [
    'name' => $name,
    'data' => $brands,
    'value' => 'id',
    'text' => 'full_name',
    'multiple' => isset($multiple) ? $multiple : false,
    'addBtn' => isset($addBtn) ? $addBtn : false,
    'editBtn' => isset($editBtn) ? $editBtn : false,
    'addModal' => '#brands-form',
    'tooltip' => __('Προσθήκη νέου brand'),
    'searchUrl' => 'searchBrandUrl',
    'class' => "App\Brand",
    'modal' => 'brands.form',
    'model' => 'brand'
];
if (isset($id)): $params['id'] = $id; endif;
if (isset($required)): $params['required'] = $required; endif;
@endphp

@component('components.selector', $params)
@endcomponent
