@php
$params = [
    'name' => isset($name) ? $name : 'tags',
    'data' => $tags,
    'value' => 'title',
    'text' => 'title',
    'searchUrl' => 'searchTagUrl',
    'class' => 'App\Tag',
    'taggable' => true,
    'multiple' => true
];
if (isset($query_fields)) {
    $params['query_fields'] = $query_fields;
}
@endphp

@component('components.selector', $params)
@endcomponent
