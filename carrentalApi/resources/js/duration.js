$(document).ready(function() {
    const durations = $('.duration');
    for (let duration of durations) {
        duration = $(duration);
        const from = $(duration.data('from'));
        const fromDate = from.find('.datepicker');
        const fromTime = from.find('.timepicker');
        const to = $(duration.data('to'));
        const toDate = to.find('.datepicker');
        const toTime = to.find('.timepicker');
        fromDate.on('dp.change', function(e) {
            const fromVal = fromDate.val() + ' ' + fromTime.val();
            const toVal = toDate.val() + ' ' + toTime.val();
            const fromdateVal = moment(fromVal, momentDatetimeFormat);
            const todateVal = moment(toVal, momentDatetimeFormat);
            if (todateVal.diff(fromdateVal) < 0) {
                toDate.val(fromdateVal.add(1, 'days').format(momentFormat));
                toTime.val(fromTime.val());
                toDate.trigger('change');
            }
            toDate.trigger('dp.change');
        });
        toDate.on('dp.change', function(e, data) {
            let days = 0;
            const fromVal = fromDate.val();
            const toVal = toDate.val();
            const fromdateVal = moment(fromVal, momentDatetimeFormat);
            const todateVal = moment(toVal, momentDatetimeFormat);
            if (fromdateVal && todateVal) {
                days = todateVal.clone().diff(fromdateVal, 'days');
            }
            const timeNow = moment(fromVal + ' ' + toTime.val(), momentDatetimeFormat);
            minutes = timeNow.clone().diff(moment(fromVal + ' ' + fromTime.val(), momentDatetimeFormat), 'minutes');
            if (minutes > extra_time) {
                days++;
            }
            if (duration.data('extra_day')) {
                if (minutes > extra_time && days > 1) {
                    $('#extra_day').prop("checked", true);
                    $('.extra_day').removeClass('d-none');
                } else {
                    $('#extra_day').prop("checked", false);
                    $('.extra_day').addClass('d-none');
                }
            }
            if (days < 0) {
                fromDate.val(todateVal.subtract(1, 'days').format(momentFormat));
                days = 1;
            }
            const old_duration = duration.val();
            duration.val(days);
            if (e.oldDate || (data !== undefined && data.timepicker) || old_duration != days) {
                duration.trigger('date-change');
            }
        });
        duration.on('change', function() {
            const days = duration.val();
            const fromVal = fromDate.val() + ' ' + fromTime.val();
            const todateVal = moment(fromVal, momentDatetimeFormat);
            toDate.val(todateVal.add(days, 'days').format(momentFormat));
            toTime.val(fromTime.val());
            toDate.trigger('dp.change');
            toDate.trigger('change');
        });
    }
})
