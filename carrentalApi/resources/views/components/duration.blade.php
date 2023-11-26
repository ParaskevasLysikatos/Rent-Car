<div class="input-group">
    <input @if(isset($id)) id="{{ $id }}" @endif type="number" name="{{ $name }}" class="duration form-control" data-from="{{ $from }}"
        data-to="{{ $to }}" @if($value) value="{{ $value }}" @endif @if($extra_day) data-extra_day="true" @endif/>
    <div class="input-group-prepend">
        <label class="form-control">{{_('ημέρες')}}</label>
    </div>
</div>
