@php if(!isset($id)): $id = $name . rand(); endif; @endphp
<div id="{{ $id }}" class="typing-selector d-flex w-100" data-search="{{ $searchUrl }}"
    data-value="{{ $value_field }}" data-text="{{ $text_field }}" @if(isset($depends)) data-depends="{{ json_encode($depends) }}" @endif>
    <input @if(isset($required) && $required === true) required @endif id="{{ $id }}_option_name" type="text" autocomplete="off" class="option_name form-control" name="{{ $name }}_text" value="{{ $text }}" />
    <input id="{{ $id }}_option_id" class="option_id" name="{{ $name }}_id" type="hidden" @if(isset($option) && $option) value="{{ $option[$value_field] }}" @endif/>
    {{-- <input class="list_search" type="hidden" /> --}}
    <div class="flex-list" id="{{ $id }}_list" @if(isset($extra_fields)) data-extra_fields="{{ json_encode($extra_fields) }}" @endif>
        @if (isset($option) && $option) <span class="flex-option" data-value="{{ $option[$value_field] }}">{{ $option[$text_field] }}</span> @endif
    </div>
</div>

@if (isset($addBtn) && $addBtn == true)
    @btnAddModal([
        'modal' => $modal,
        'tooltip' => isset($tooltip) ? $tooltip : false,
        'referer' => $id,
        'referer_type' => 'typing_selector',
        'value' => $value_field,
        'text' => $text_field,
        'add_fields' => isset($add_fields) ? $add_fields : [],
        'class' => isset($btn_class) ? $btn_class : '',
        'tabindex' => -1
    ])
    @endbtnAddModal
@endif
