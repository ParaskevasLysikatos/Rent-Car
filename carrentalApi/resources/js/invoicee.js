$(document).on('submit', 'form', function(e) {
    const $this = $(this);
    const sub_account = $($this.find('.invoicee_selectr'));
    const options = sub_account.find('option:selected');

    const inputs = $this.find('input[name^="invoicee_selectrs"]');
    for (const input of inputs) {
        $(input).remove();
    }

    for (let option of options) {
        option = $(option);
        let name = 'invoicee_type';
        let val = option.data('transactor_type');
        if (option.parent().attr('multiple')) {
            name = "invoicee_selectrs["+option.data('model')+"][]";
            val = option.val();
        }
        if (val) {
            $this.append('<input type="hidden" name="'+name+'" value="'+val+'" />');
        } else if ($this.find('input[name="'+name+'"]').length > 0) {
            console.log('removed');
            $this.find('input[name="'+name+'"]').val('');
        }
    }
});
