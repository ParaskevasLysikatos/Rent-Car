$(document).on('submit', 'form', function(e) {
    const $this = $(this);
    const sub_account = $($this.find('.sub_account'));
    const options = sub_account.find('option');

    const inputs = $this.find('input[name^="sub_accounts"]');
    for (const input of inputs) {
        $(input).remove();
    }

    for (let option of options) {
        option = $(option);
        let name = 'sub_account_type';
        let val = option.data('model');
        if (option.parent().attr('multiple')) {
            name = "sub_accounts["+option.data('model')+"][]";
            val = option.val();
        }
        if (val) {
            $this.append('<input type="hidden" name="'+name+'" value="'+val+'" />');
        }
    }
});
