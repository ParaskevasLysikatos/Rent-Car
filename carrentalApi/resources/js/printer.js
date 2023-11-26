$(document).on('click', '#print_files', function (e) {
    $('#printModalFiles').modal('toggle');
});
$(document).on('click', '#print_files2', function (e) {
    $('#printModalFiles2').modal('toggle');
});

$(document).on('click', '#print-selected-files', function (e) {
    const files = $('#printings').find(':checked');
    for (const file of files) {
        $.get(singlePrinterUrl + '?pdf_src=' + $(file).val()).done(function (modal) {
            modal = $(modal);
            modal.appendTo('.page-wrapper');
            modal.modal('show');
        });
    }
});

$(document).ready(function () {
    $(document).on('click', '#print_file', function (e) {
        $('#printModalFile').modal('toggle');
    });
    $(document).on('click', '#print_submit', function (e) {
        window.frames.popup_iframe.print();
    });
    $(document).on('load', '#popup_iframe', function() {
        $('#print_submit').prop('disabled', false);
    });
    $(document).on('click', '#email_submit', function (e) {
        var submit_button = $(this);
        submit_button.html('<div class="spinner-border"></div>');
        $.post(print_mail_url, {
            mail_to:$('#email_sent_to').val(),
            mail_subject:$('#email_subject').val(),
            mail_notes:$('#email_notes').val()
        }, function (data) {
            $("#mailer_response_alert").html('<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data+'</div>');
            submit_button.html(submit_button.data('title'));
        });
    });
});
