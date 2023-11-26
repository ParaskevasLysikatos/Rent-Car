$(document).on('focusout', '.timepicker', function () {
    const $this = $(this);
    let time = $this.val().replace(/\D/g, "");
    if (time.length > 4) {
        time = time.substring(0,4);
    }
    let hours = new String(time);
    while (hours > 24 && hours.length > 0) {
        hours = hours.slice(0, -1);
    }
    if (hours.length <= 0 || hours == 24) {
        hours = 0;
    }

    if (hours.length > 2) {
        hours = hours.slice(0, 2);
    }
    let minutes = time.slice(String(hours).length);

    while (minutes >= 60 && minutes.length > 0) {
        minutes = minutes.slice(0, -1);
    }
    hours = String(hours).padStart('2', '0');
    if (minutes > 6) {
        minutes = String(minutes).padStart('2', '0');
    } else {
        minutes = String(minutes).padEnd('2', '0');
    }
    $this.val(hours + ':' + minutes);
    const datetimepicker = $this.closest('.datetimepicker');
    if (datetimepicker.length > 0) {
        datetimepicker.find('.datepicker').trigger('dp.change', [{timepicker: true}]);
    }
});
