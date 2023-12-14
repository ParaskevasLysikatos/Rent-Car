@modal([
    'name' => 'selectr-modal',
    'class' => 'selectr-modal modal-for-edits',
    'disable_click' => true
])
<div class="edit-content">

</div>
@endmodal
@modal([
    'name' => 'selectr-add-modal',
    'class' => 'selectr-add-modal modal-for-edits',
    'disable_click' => true
])
<div class="edit-content">

</div>
@endmodal
@modal([
    'name' => 'status-cancel-modal',
    'disable_close' => true
])
@php
    $cancel_reasons = \App\CancelReason::get();
@endphp
<div class="edit-content">
    <div class="modal-header">
        <h4 class="modal-title">{{__('Αιτιολογία Ακύρωσης')}}</h4>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
        <label for="cancel_reason_id">{{ __('Επιλέξτε λόγο ακύρωσης') }}:</label>
        <select id="cancel_reason_id" name="cancel_reason_id">
            @foreach ($cancel_reasons as $cancel_reason)
                <option value="{{ $cancel_reason->id }}">{{ $cancel_reason->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-success float-right">Ενημέρωση</button>
    </div>
</div>
@endmodal
@include('template-parts.printer')
@include('template-parts.single_printer')
