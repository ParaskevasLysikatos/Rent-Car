$(document).on('submit', 'form', function(e) {
    const $this = $(this);
    const datetimepickers = $this.find('.datetimepicker');
    for (let datetimepicker of datetimepickers) {
        datetimepicker = $(datetimepicker);
        const name = datetimepicker.attr('name');
        let datepickerVal = datetimepicker.find('.datepicker-submit').val();
        if (datepickerVal != 'Invalid date') {
            let val = datepickerVal + ' ';
            if (datetimepicker.find('.timepicker').val()) {
                val += datetimepicker.find('.timepicker').val();
            } else {
                val += '00';
            }
            val = val == ' ' ? '' : val + ':00';
            $this.append('<input type="hidden" name="'+name+'" value="'+val+'" />');
        }
    }
});

$(document).on('change', '.datetimepicker .timepicker', function () {
    $(this).closest('.datetimepicker').trigger('change');
});

$(document).on('dp.change', '.datetimepicker .datepicker', function (e) {
    if (e.oldDate) {
        $(this).closest('.datetimepicker').trigger('change');
    }
});
