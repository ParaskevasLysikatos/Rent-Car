@php if (!isset($id)): $id = $name.rand(); endif; @endphp
<div class="form-inline datetimepicker @if(isset($class)){{ $class }}@endif" name="{{ $name }}" id="{{ $id }}">
    <input @if(isset($required) && $required == true) required @endif
        type="text" class="form-control datepicker"
        value="{{ isset($datetime) ? formatDate($datetime) : ''}}">
    <input @if(isset($required) && $required == true) required @endif class="form-control timepicker" type="text" value="{{ isset($datetime) ? formatTime($datetime) : '' }}" />
</div>
