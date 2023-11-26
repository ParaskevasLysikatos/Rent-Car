$(document).ready(function() {
    const selects = $('select[multiple]');
    for (let select of selects) {
        $("<input type='hidden' name='"+ $(select).attr('name').replace('[]', '') +"' value='' />").insertBefore($(select));
    }
});

$('document').ready(function() {
    $('.nav-tabs a').click(function () {
        $($(this).data("second-tab")).click();
    });
    $(window.location.hash).click();
    $('.nav-link').on('shown.bs.tab', function (e) {
        const $this = $(this);
        const id = $this.attr('id');
        if (id) {
            window.location.hash = id;
        }
    });
});

$(window).keydown(function(event) {
    if(event.keyCode == 13 && event.target.type != 'textarea' ) {
        event.preventDefault();
        return false;
    }
});

$(document).on('click', '.selectr-label a', function() {
    window.open($(this).attr('href'), '_blank');
});

$(document).on('click', '.datepicker, .timepicker', function () {
    this.select();
});

$(document).on('click', '.booking-status-btn[data-status="cancelled"]', function (){
    $("#status-cancel-modal").modal('show');
});

$(document).on('click', '#status-cancel-modal .btn-success', function () {
    const modal = $('#status-cancel-modal');
    modal.modal('hide');

    $('#cancel-reason').val(modal.find('select').val());
});
