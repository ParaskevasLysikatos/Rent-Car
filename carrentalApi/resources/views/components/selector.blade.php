<div class="d-flex w-100">
    @php
        $string_class = $class ?? '';
        if(!isset($id)): $id = $name . rand(); endif;
        if (is_array($data) && empty($data) && old($name) && isset($class)){
            $old_vals = old($name);
            if (!is_array($old_vals)) {
                $old_vals = [$old_vals];
            }
            foreach ($old_vals as $old_val) {
                if (is_string($class)) {
                    $class = new $class;
                }
                $single = $class::where('id', $old_val)->first();
                $data[] = $single;
            }
        }

    @endphp
    <select data-search="{{ $searchUrl }}" data-value="{{ $value }}" data-text="{{ $text }}"
        @if(isset($editBtn))
            data-modal="{{ $modal }}"
            data-model="{{ isset($model) ? $model : '' }}"
            data-class="{{ isset($string_class) ? $string_class : '' }}"
        @endif
        @if(isset($extra_fields)) data-extra_fields="{{ json_encode($extra_fields) }}" @endif
        @if(isset($query_fields)) data-query_fields="{{ json_encode($query_fields) }}" @endif
        @if(isset($depends)) data-depends="{{ json_encode($depends) }}" @endif
        @if(isset($except)) data-except="{{ $except }}" @endif
        @if(isset($required) && $required != false) required @endif id="{{ $id }}" class="form-control ajax-selector"
        @if (isset($multiple) && $multiple == true) multiple name="{{ $name }}[]" @else name="{{ $name }}" @endif>
        @if(!(isset($data) && ((is_array($data) && count($data) > 0) || (!is_array($data) && $data->count() > 0))))
            <option value="-" disabled>-</option>
        @endif
    </select>

    @if (isset($addBtn) && $addBtn == true)
        @btnAddModal([
            'modal' => $modal,
            'tooltip' => isset($tooltip) ? $tooltip : false,
            'referer' => $id,
            'referer_type' => 'select',
            'selectr' => $id,
            'value' => $value,
            'text' => $text,
            'add_fields' => isset($add_fields) ? $add_fields : [],
            'tabindex' => -1
        ])
        @endbtnAddModal
    @endif
</div>

{{-- @push('scripts') --}}
    <script>
        var data{{ $id }} = {
            customClass: "ajax-selectr",
            allowDeselect: true,
            renderOption: renderSelectrOption
        };
        @if(!isset($disabled) || !$disabled)
            @if (isset($multiple) && $multiple == true)
                data{{ $id }}.multiple = true;
                @if(isset($editBtn) && $editBtn == true)
                    data{{ $id }}.renderSelection = editableMutliSelectr;
                @endif
            @else
                data{{ $id }}.renderSelection = deselectSelectr;
                @if(isset($editBtn) && $editBtn == true)
                    data{{ $id }}.renderSelection = editableSingleSelectr;
                @elseif (isset($link))
                    data{{ $id }}.renderSelection = linkSelectr("{{ $link }}");
                @endif
            @endif
            @if (isset($taggable) && $taggable === true)
                data{{ $id }}.taggable = true;
                delete data{{ $id }}.renderSelection;
            @endif
        @endif
        var {{ $id }} = new Selectr("#{{ $id }}", data{{ $id }});
		//Gerasimos "Hack" for mobile devices search
		{{ $id }}.mobileDevice = false;
        @if(isset($disabled) && $disabled)
            {{ $id }}.disable(true);
        @endif
        @if(isset($html_class)) $("#{{ $id }}").addClass("{{ $html_class }}"); @endif

        @foreach($data as $single)
            selectr_data = {};
            @php
                $extra_fields_data = '';
                // $selectr_data = [];
                if (isset($extra_fields)) {
                    foreach ($extra_fields as $field) {
                        @endphp
                        selectr_data['data-'+'{{ $field }}'] =  "{{ addslashes($single[$field]) }}";
                        @php
                    }
                }
                $textArr = explode('.', $text);
                $newText = $single;
                foreach ($textArr as $txt) {
                    $newText = $newText->{$txt};
                }
            @endphp
            selectr_data.value = "{{ $single[$value] }}";
            selectr_data.text = "{!! $newText !!}";
            selectr_data.selected = true;
            {{ $id }}.add(selectr_data, false);
        @endforeach
    </script>
{{-- @endpush --}}
