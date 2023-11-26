$(document).ready(function () {
    const paymentmodal = $('#payment-add-modal');

    $(document).on('submit', '#payment-add-modal form', function(evt) {
        evt.preventDefault();
        const $this = $(this);
        // const form = $('#selectr-add-modal form');
        const referer_dom = $('#'+paymentmodal.data('referer'));
        $.ajax({
            url: $this.attr('action'),
            data: $this.serializeArray(),
            type: $this.attr('method'),
        }).done(function(element) {
            referer_dom.val(parseFloat(referer_dom.val()) + element.amount);
            referer_dom.trigger('change');
            paymentmodal.modal('hide');
        }).fail(function(xhr, textStatus) {
            alert(xhr.responseJSON);
        });
    });

    $(document).on('click', '.btn-payment-open-modal', function(e) {
        const $this = $(this);
        const referer = $this.data('referer');
        const depends = $this.data('depends');
        const data = {
            view: $this.data('modal'),
            add_fields: $this.data('add_fields'),
            depends: depends
        };
        $.get({
            url: addModalUrl,
            data: data
        }).done(function(response) {
            paymentmodal.find('.edit-content').html(response);
            render_ajax_selectors('.edit-content');
            loadDatepickers('.edit-content');
            paymentmodal.data('referer', referer);
            paymentmodal.modal('show');
        });
    });
});
