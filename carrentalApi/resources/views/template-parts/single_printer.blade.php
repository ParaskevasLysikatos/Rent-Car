<div class="modal fade" id="printModalFile">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="height: 85vh;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('Εκτύπωση')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row h-100">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email_sent_to">{{__('Αποστολή σε')}}:</label>
                            <input type="text" id="email_sent_to" name="email_sent_to" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email_sent_to">{{__('Θέμα')}}:</label>
                            <input type="text" id="email_subject" name="email_subject" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email_sent_to">{{__('Σημειώσεις')}}:</label>
                            <textarea type="text" id="email_notes" name="email_notes" class="form-control"></textarea>
                        </div>
                        <button type="button" id="email_submit" class="btn btn-info mb-3" data-title="{{__('Αποστολή')}}">{{__('Αποστολή')}}</button>
                        <button type="button" disabled id="print_submit" class="btn btn-secondary mb-3" data-title="{{__('Εκτύπωση')}}">{{__('Εκτύπωση')}}</button>

                        <div id="mailer_response_alert"></div>
                    </div>
                    <div class="col-md-6">
                        <iframe id="popup_iframe" name="popup_iframe" class="h-100 w-100" src="{{ isset($pdf_src) ? $pdf_src : '' }}" frameborder="0"></iframe>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
