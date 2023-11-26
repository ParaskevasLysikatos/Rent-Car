function loadDatepickers(field = document) {
    const datepickers = $(field).find('.datepicker');
    for (let datepick of datepickers) {
        datepick = $(datepick);
        const name = datepick.attr('name');
        let value = "";
        if (datepick.val()) {
            value = moment(datepick.val(), momentFormat).format(defaultFormat);
        }
        $('<input type="hidden" class="datepicker-submit" name="'+name+'" value="'+value+'"/>').insertAfter(datepick);
        datepick.removeAttr('name');
    }
}

window.loadDatepickers = loadDatepickers;

$(document).ready(function () {
    $.extend(true, $.fn.datetimepicker.defaults, {
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });

    loadDatepickers();

    $(document).on('dp.change', '.datepicker', function (e) {
        var submitDateString = '';
        if ($(this).val()) {
            const dateVal = moment($(this).val(), momentFormat);
            if (dateVal) {
                submitDateString = dateVal.format(defaultFormat);
            }
        }
        if (e.oldDate || $(this).parent().find('.datepicker-submit').val() == '') {
            $(this).parent().find('.datepicker-submit').val(submitDateString);
            $(this).parent().find('.datepicker-submit').trigger('change');
        }
    });

    $(document).on('focus', '.datepicker', (e) => {
        const dt = $(e.target);
        dt.datetimepicker({
            format: datepickerFormat
        });
    });

    $('.datepicker').on("change", function (e) {
        var submitDateString = '';
        if ($(this).val()) {
            const dateVal = moment($(this).val(), momentFormat);
            if (dateVal) {
                submitDateString = dateVal.format(defaultFormat);
            }
        }
        $(this).parent().find('.datepicker-submit').val(submitDateString);
        $(this).parent().find('.datepicker-submit').trigger('change');
    });
});
