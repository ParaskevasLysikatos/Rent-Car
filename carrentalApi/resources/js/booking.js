var typingTimer;                //timer identifier
var doneTypingInterval = 700;  //time in ms, 5 second for example

var driver = {
    value: "",
    text: "",
    debt: 0,
    pre_auth: 0
};
var company = {
    value: "",
    text: "",
    debt: 0,
    pre_auth: 0
};
var agent = {
    value: "",
    text: "",
    debt: 0,
    pre_auth: 0
};
var html;

var order = {
    driver: 3,
    company: 2,
    agent: 1
}

var values;

var costs = {};

window.driver = driver;
window.company = company;
window.agent = agent;

var options = [];

function changeDepts() {
    const table = $('.debts-table');
    const driverRow = table.find('tr[data-type="driver"]');
    const companyRow = table.find('tr[data-type="company"]');
    const agentRow = table.find('tr[data-type="agent"]');
    const discount = parseFloat($('#discount').val())/100;
    if (driver.debt > 0) {
        driverRow.find('.name').text(driver.text);
        driverRow.find('.paid').text(driver.paid + ' €');
        driverRow.find('.rest').text(roundTo(driver.debt - driver.debt*discount - driver.paid, 2) + ' €');
        driverRow.find('.total').text(roundTo(driver.debt - driver.debt*discount, 2) + ' €');
        driverRow.removeClass('d-none')
    } else {
        driverRow.addClass('d-none')
    }
    if (company.debt > 0) {
        companyRow.find('.name').text(company.text);
        companyRow.find('.paid').text(company.paid + ' €');
        companyRow.find('.rest').text(roundTo(company.debt - company.debt*discount - company.paid, 2) + ' €');
        companyRow.find('.total').text(roundTo(company.debt - company.debt*discount, 2) + ' €');
        companyRow.removeClass('d-none')
    } else {
        companyRow.addClass('d-none')
    }
    if (agent.debt > 0) {
        agentRow.find('.name').text(agent.text);
        agentRow.find('.paid').text(agent.paid + ' €');
        agentRow.find('.rest').text(roundTo(agent.debt - agent.debt*discount - agent.paid, 2) + ' €');
        agentRow.find('.total').text(roundTo(agent.debt - agent.debt*discount, 2) + ' €');
        agentRow.removeClass('d-none');
    } else {
        agentRow.addClass('d-none')
    }
    values.voucher = agent.debt;
}

function sortPayers(a, b) {
    const aorder = order[$(a).val()];
    const border = order[$(b).val()];
    return (aorder > border ) ? 1 : -1;
};

function changePayersOrder() {
    const list = options;
    list.sort(sortPayers);
    $('.payers').html('').append(list);
    $('.payers').trigger('change');
}

function createPayers() {
    if ($('#customer_text').length > 0) {
        driver.text = $('#customer_text').text();
    } else if ($('#driver_id').length > 0) {
        driver.value = $('#driver_id').val();
        driver.text = $('#driver_id option:selected').text();
    } else if ($('#customer_id').length > 0){
        driver.value = $('#customer_id').find('.option_id').val();
        driver.text = $('#customer_id').find('.option_name').val();
    }
    if ($('#company_id').length > 0) {
        company.value = $('#company_id').val();
        company.text = $('#company_id option:selected').text();
    }
    if ($('#agent_id').length > 0 && $('#agent_id').val()) {
        agent.value = $('#agent_id').val();
        agent.text = $('#agent_id option:selected').text();
    }

    const payersHtml = $('.payers');
    let vals = {};
    for (let select of payersHtml) {
        select = $(select);
        if (select.val()) {
            vals[select.closest('tr').attr('id')] = select.val();
        }
    }
    updatePayers();

    for (select_id in vals) {
        $('#'+select_id).find('select').val(vals[select_id]);
        $('#'+select_id).find('select').trigger('change');
    }
}

function updateDriver() {
    const drivers = $('.payers').find('option[value="driver"]');
    if (drivers.length == 0) {
        updatePayers();
    } else {
        drivers.each(function() {
            $(this).text(driver.text);
        });
    }
}

function updatePayers() {
    const payersHtml = $('.payers');
    for (let select of payersHtml) {
        select = $(select);
        select.empty();
        options = [];
        if (driver.text){
            options.push(`<option value="driver">${driver.text}</option>`);
            // select.append();
        }
        if (company.value) {
            options.push(`<option value="company">${company.text}</option>`);
            // select.append(`<option value="App\\Company-${company.value}" data-payer='company'>${company.text}</option>`);
        }
        if (agent.value){
            options.push(`<option value="agent">${agent.text}</option>`);
            // select.append(`<option value="App\\Agent-${agent.value}" data-payer='agent'>${agent.text}</option>`);
        }
    }

    changePayersOrder();
}

$(document).ready(function () {
    if ($('.booking-container').length == 0) return false;

    $('#extra_day').on('change', function(e) {
        const $this = $(this);
        if ($this.prop('checked')) {
            $('#duration').val(pf($('#duration').val()) + 1);
        } else {
            $('#duration').val(pf($('#duration').val()) - 1);
        }
        $('#duration').trigger('date-change');
    });

    booking_source.on('selectr.select', function(option) {
        option = $(option);
        const brand_id = option.data('brand_id');
        const program_id = option.data('program_id');
        const agent_depend = option.data('agent_id');
        const agent = agent_id.getValue();
        if (brand_id) {
            $('#brand_id').val(brand_id);
        }
        if (program_id) {
            $('#program_id').val(program_id);
        }
        if (agent && !agent_depend) {
            setTimeout(() => {
                agent_id.setValue(agent);
            }, 500);
        }
    });

    agent_id.on('selectr.select', function(option) {
        option = $(option);
        const program_id = option.data('program_id');
        const brand_id = option.data('brand_id');
        if (program_id) {
            $('#program_id').val(program_id);
        }
        if (brand_id) {
            $("#brand_id").val(brand_id);
        }
    });

    var rate_changed = 0;
    $(document).on('input change', '#rate', function (e) {
        $('#option-rental').find('.cost.float-submit').val($(this).val());
        $('#option-rental').find('.cost.float-submit').trigger('change');
        $('#rental-modal .btn-success').click();
        if (rate_changed > 1) {
            const rate = $('#rate').val();
            $('#extension_rate').val(rate);
            $('#extension_rate').trigger('change');
        }
    });

    $(document).on('input', '#extension_rate', function() {
        $('#option-additional-rental').find('.total-cost.float-submit').val($(this).val());

        $('#option-additional-rental').find('.total-cost.float-submit').trigger('change');
        $('#rental-modal .btn-success').trigger('click');
    });

    function computeRentalFee() {
        const duration = $('#duration').val();
        const rate = $('#rate').val();
        if (rate && duration) {
            $('#rental_fee_info').val(pf(duration) * pf(rate));
        } else {
            $('#rental_fee_info').val(0);
        }
        $('#rental_fee_info').trigger('change');
    }

    $(document).on('input', '#rate', function (e) {
        computeRentalFee();
        // bookingCalculator.rental_fee = ;
    });

    const model = $('#vehicle-model');
    const vehicle_selector = $('#vehicle_id');
    vehicle_selector.on('ajax_search_extra_args', function (e) {
        e.detail.depends.km = 'km';
        e.detail.depends.fuel_level = 'fuel_level';
        e.detail.depends.model = 'model';
        e.detail.depends.make = 'make';
    });

    if (vehicle_selector.length > 0) {
        vehicle_id.on('selectr.deselect', function(option) {
            model.val('');
            $('#checkout_km').val('');
            $('#checkout_fuel_level').val('');
        });

        vehicle_selector[0].selectr.on('selectr.select', function (option) {
            option = $(option);
            model.val(option.data('make') + ' ' + option.data('model'));
            const checkout_km = $('#checkout_km');
            if (checkout_km.length > 0) {
                const km = option.data('km');
                const fuel_level = option.data('fuel_level');
                checkout_km.val(km ? km : 0);
                $('#checkout_fuel_level').val(fuel_level ? fuel_level : 0);
            }
        });

        $(document).on('change', '#checkout_datetime .datepicker-submit', function() {
            const fromDate = $(this);
            const fromTime = fromDate.closest('.datetimepicker').find('.timepicker');
            const fromVal = fromDate.val() + ' ' + fromTime.val();
            let query_fields = vehicle_selector.data('query_fields');
            if (query_fields == undefined) {
                query_fields = {};
            }
            query_fields.from = fromVal;
            vehicle_selector.data('query_fields', query_fields);
            selectorSearch(vehicle_selector[0].selectr.getValue(), vehicle_selector[0].selectr, false, true);
            selectorSearch('', vehicle_selector[0].selectr);
        });

        $(document).on('change', '#checkin_datetime .datepicker-submit', function() {
            const toDate = $(this);
            const toTime = toDate.closest('.datetimepicker').find('.timepicker');
            const toVal = toDate.val() + ' ' + toTime.val();
            let query_fields = vehicle_selector.data('query_fields');
            if (query_fields == undefined) {
                query_fields = {};
            }
            query_fields.to = toVal;
            vehicle_selector.data('query_fields', query_fields);
            selectorSearch(vehicle_selector[0].selectr.getValue(), vehicle_selector[0].selectr, false, true);
            selectorSearch('', vehicle_selector[0].selectr);
        });
    }

    checkout_station_id.on('selectr.select', function(option) {
        if(checkin_station_id.getValue() != option.value)
            checkin_station_id.setValue(option.value);
    });

    $(document).on('change', '#checkout_place .option_id', function() {
        const $this = $(this);
        const checkout_place = $this.closest('#checkout_place');
        $('#checkin_place').find('.option_name').val(checkout_place.find('.option_name').val());
        $('#checkin_place').find('.option_id').val(checkout_place.find('.option_id').val());
    });

    $('#charge_type_id').on('ajax_search_extra_args', function (e) {
        e.detail.depends.excess = 'excess';
    });

    booking_group_id.on('selectr.select', function(option) {
        if(charge_type_id.getValue() != option.value) {
            if ($('.rental-container').length < 1 || $('.new-rental').length > 0) {
                charge_type_id.setValue(option.value);
            }
        }

    });

    charge_type_id.on('selectr.select', function(option) {
        $('#excess').val($(option).data('excess'));
        $('#excess').trigger('input');
    });

    $('#fuel_fee').on('change', function (e) {
        const $this = $(this);
        bookingCalculator.fuel = $this.val() == "-1" ? '0' : $this.val()
    });

    $(document).on('input', '#rental_fee', function (e) {
        rate_changed++;
        let rental_fee = pf($('#rental_fee').val());
        if (isNaN(rental_fee) || !rental_fee) {
            rental_fee = 0;
        }
        bookingCalculator.rental_fee = rental_fee;
        const duration = pf($('#duration').val());
        if (!isNaN(duration) && duration) {
            // const rental = $('#option-rental .total-cost.float-submit').val();
            // $('#rate').val(roundTo(rental/duration, 2));
            // $('#rate').val(roundTo(rental_fee/duration, 2));
            // $('#rate').trigger('change');
        }
    });

    $(document).on('change', '#rental_fee', function (e) {
        // $('#rental_fee_info').val(values.rental_fee);
        // $('#rental_fee_info').trigger('change');
    });

    $(document).on('input', '#rental_fee_info', function (e) {
        // $('#rental_fee').val($(this).val());
        // $('#rental_fee').trigger('input');
        const rental_fee = pf($(this).val());
        const duration = pf($('#duration').val());
        if (isNaN(rental_fee) || !rental_fee && (!isNaN(duration) && duration)) {
            rental_fee = 0;
        }
        // $('#rate').val(roundTo(rental_fee/duration, 2));
        // $('#rate').trigger('calculate');
        $('#option-rental').find('.total-cost.float-submit').val($(this).val());
        $('#option-rental').find('.total-cost.float-submit').trigger('change');
        $('#rental-modal .btn-success').click();
    });

    $(document).on('input change', '#discount', function (e) {
        const float = $(this).prev();
        if ($(this).val() > 0) {
            float.addClass('has-discount');
        } else {
            float.removeClass('has-discount')
        }
        changeDepts();
    });

    $(document).on('input', '#discount', function (e) {
        let subcharges = values.subcharges_fee;
        const discount = pf($('#discount').val());
        let discounted_price;
        if (isNaN(subcharges) || !subcharges) {
            discounted_price = 0;
            subcharges = 0;
            $('#discount').val(0);
        } else {
            discounted_price = subcharges * discount/100;
        }
        values.discounted_price = discounted_price;
        if (pricesWithVat) {
            values.total_net = pf(subcharges) - pf(discounted_price);
        } else {
            values.total = pf(subcharges) - pf(discounted_price);
        }
    });

    $(document).on('update', '#subcharges_fee', function (e, data) {
        // values.total_net = values.subcharges_fee - values.discounted_price;
        if (!pricesWithVat) {
            // values.total = values.subcharges_fee;
            // $('#discount').trigger('input');
            values.total = pf(values.total) + pf(data.diff);
            $('#total').trigger('input');
        } else {
            const vat = pf($('#vat').val());
            const vat_fee = roundTo(pf(values.total_net) * vat/100, 2);
            values.vat_fee = vat_fee;
            values.total = pf(values.total_net) + pf(vat_fee);
        }
    });

    $(document).on('change input', '#total_net #vat', function (e) {
        if (pricesWithVat) {
            const vat = pf($('#vat').val());
            const vat_fee = pf(values.total)/(100 + vat)*vat;
            values.vat_fee = vat_fee;
            $('#total_net').val(pf(values.total) - vat_fee);
        } else {
            const vat = pf($('#vat').val());
            const vat_fee = pf(values.total)*vat/100;
            values.vat_fee = vat_fee;
        }
    });

    $(document).on('input', '#vat', function (e) {
        $('#vat_fee').trigger('input');
    });

    $(document).on('input', '#vat_fee', function (e) {
        if (!pricesWithVat) {
            const vat = pf($('#vat').val());
            const total_net = roundTo(pf(values.total)/((100+vat)/100), 2);
            values.vat_fee = roundTo(pf(values.total) - total_net, 2);
            values.total_net = total_net;
        }
    });

    $(document).on('change input', '#total, #voucher, #total_paid', function(e) {
        const total = pf($('#total').val());
        const voucher = pf($('#voucher').val());
        const paid = pf($('#total_paid').val());
        values.balance = total - voucher - paid;
    });

    $(document).on('change input', '#extra_charges', function() {
        values.total = pf(values.subcharges_fee) - pf(values.discounted_price) + pf($(this).val());
        $('#total').trigger('input');
    });

    $(document).on('input', '#total', function(e) {

        let newTotal = pf($('#total').val());
        if (pricesWithVat) {
            const new_net_total = (newTotal/(100 + pf($('#vat').val())) * 100);
            if (new_net_total > 0) {
                const total = pf(values.total_net) - pf(values.discounted_price);
                let discount_fee = total - new_net_total;
                if (discount_fee < 0) {
                    return;
                }
                values.discount = roundTo(discount_fee/values.rental_fee*100, 2);
            }
        } else {
            // Υπολογισμός του μισθώματος

            // let total = values.total;
            // let rental_fee = roundTo(values.rental_fee + (newTotal - total)/(1 - pf($('#discount').val())/100) + 0.004, 2);
            // if (rental_fee < 0) {
            //     rental_fee = 0;
            // }
            // $('#rental_fee').val(rental_fee);
            // $('#rental_fee').trigger('input');

            // Υπολογισμός της έκπτωσης

            if (newTotal > pf(values.subcharges_fee)) {
                const extra_charges = $('#extra_charges').length > 0 ? pf($('#extra_charges').val()) : 0;
                if (newTotal - extra_charges > pf(values.subcharges_fee)) {
                    values.discount = 0;
                    $('#rental_fee_info').val(pf($('#rental_fee_info').val()) + newTotal - extra_charges - values.total);
                    $('#rental_fee_info').trigger('input');
                }
            } else {
                const discounted_price = pf(values.subcharges_fee) - newTotal;
                const discount = discounted_price/pf(values.subcharges_fee)*100;
                values.discount = discount;
                values.discounted_price = discounted_price;
            }
        }
        values.total = newTotal;
    });

    $(document).on('change input', '#total', function(e) {
        if (!pricesWithVat) {
            $('#vat_fee').trigger('input');
        }
    });

    const targetObj = {
        total_net: 0,
        balance: 0
    };

    values = new Proxy(targetObj, {
        set: function (target, key, value) {
            const el = $('#'+key);
            target[key] = value;
            if (el.length > 0) {
                el.val(roundTo(value, 2));
                el.trigger('change');
            }
        },
        get: function(target, key) {
            if (target[key] == undefined) {
                const el = $('#'+key);
                val = el.val();
                if (!(el.length > 0) || isNaN(val)) {
                    val = 0;
                }
                target[key] = val;
            }
            return pf(target[key]);
        }
    });
    values.rental_fee;
    values.rate;
    values.total;

    // window.values = values;

    const bookingCalc = {
        fuel: $('#fuel').val() == '-1' ? 0 : $('#fuel').val(),
        insurance_fee,
        options_fee,
        transport_fee
    };

    for (const key of Object.keys(bookingCalc)) {
        const $this = $('#'+key);
        // let val = pf($this.val());
        // if (isNaN(val)) {
        //     val = 0;
        // }
        // bookingCalc[key] = val;
        $this.on('change', function () {
            bookingCalculator[key] = pf($this.val());
        });
    }

    var bookingCalculator = new Proxy(bookingCalc, {
        set: function (target, key, value) {
            value = pf(value);
            if (isNaN(value)) {
                value = 0;
            }
            diff = value - values[key]
            values.subcharges_fee -= values[key];
            values[key] = value;
            values.subcharges_fee = roundTo(values.subcharges_fee + pf(value), 2);
            $('#subcharges_fee').trigger('update', [{diff: diff}]);
            return true;
        }
    });

    function pf(val) {
        return parseFloat(val);
    }

    $('#checkout_station_fee').on('input', function (e) {
        const $this = $(this);
        let val = $this.val()
        if (isNaN(val)) {
            val = 0;
        }
        $('#option-kostos-paradoshs-1').find('.cost.float-submit').val($(this).val());
        $('#option-kostos-paradoshs-1').find('.cost.float-submit').trigger('change');
        $('#option-kostos-paradoshs-1').find('input[type="checkbox"]').attr('checked', 'checked');
        $('#option-kostos-paradoshs-1').find('input[type="checkbox"]').trigger('change');
        $('#transport-modal .btn-success').trigger('click');
    });

    $('#checkin_station_fee').on('input', function (e) {
        const $this = $(this);
        let val = $this.val()
        if (isNaN(val)) {
            val = 0;
        }
        $('#option-kostos-paralabhs').find('.cost.float-submit').val($(this).val());
        $('#option-kostos-paralabhs').find('.cost.float-submit').trigger('change');
        $('#option-kostos-paralabhs').find('input[type="checkbox"]').attr('checked', 'checked');
        $('#option-kostos-paralabhs').find('input[type="checkbox"]').trigger('change');
        $('#transport-modal .btn-success').trigger('click');
    });

    $('#customer_id .option_name').on("change", function(e) {
        const selectElement = e.target;

        const value = selectElement.value;
        driver.value = undefined;
        driver.text = undefined;
        if (value) {
            driver.value = value;
            driver.text = value;
        }

        updatePayers();
    });

    $('#customer_id_option_id').on('change', function (e) {
        let val = $(this).val();
        if (val) {
            val = val.replace('\\', '\\\\');
            const user = $('#customer_id_list').find('[data-value="'+val+'"]');
            $('#phone').val(user.data('phone'));
        } else {
            $('#phone').val('');
        }
    });

    $('#customer_id.typing-selector').on('change', function (e) {
        const btn_modal = $(this).parent().find('.btn-open-modal');
        const name = $(this).find('.option_name').val();
        const phone = $('#phone').val();

        btn_modal.data('add_fields', {
            name: name,
            phone: phone
        });
    });

    $('#driver_id').on("change", function(e) {
        const selectElement = e.target;

        const value = selectElement.value;

        let updateDr = false;
        if (!driver.value && value) {
            updateDr = true;
        }

        driver.value = undefined;
        driver.text = undefined;
        if (value) {
            driver.value = value;
            driver.text = selectElement.options[selectElement.selectedIndex].text;
        }

        if (updateDr) {
            updateDriver();
            changeDepts();
        } else {
            updatePayers();
        }
    });

    $('#company_id').on("change", function(e) {
        const selectElement = e.target;

        const value = selectElement.value;
        company.value = undefined;
        company.text  = undefined;
        if (value) {
            company.value = value;
            company.text = selectElement.options[selectElement.selectedIndex].text;
        }

        updatePayers();
    });

    $('#agent_id').on("change", function(e) {
        const selectElement = e.target;

        const value = selectElement.value;
        agent.value = undefined;
        agent.text = undefined;
        if (value && value!='-') {
            agent.value = value;
            agent.text = selectElement.options[selectElement.selectedIndex].text;
        }

        updatePayers();
    });

    function computeDays(fromDate, fromTime, toDate, toTime) {
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
        return days;
    }

    function datetimepickerRow (row) {
        if (row.data('daily') == true) {
            const start = row.find('.start');
            const startdate = start.find('.datepicker-submit').val();
            let startdatetime = '';
            if (startdate && startdate != 'Invalid date') {
                startdatetime = startdate + ' ' + start.find('.timepicker').val();
            }
            const end = row.find('.end');
            const enddate = end.find('.datepicker-submit').val();
            let enddatetime = '';
            if (enddate && enddate != 'Invalid date') {
                enddatetime =  + ' ' + end.find('.timepicker').val();
            }

            const oldDuration = row.find('.duration').val();
            let duration = $('#duration').val();
            if (startdatetime || enddatetime) {
                if (startdatetime) {
                    duration = computeDays(start.find('.datepicker'), start.find('.timepicker'),
                        $('#checkin_datetime').find('.datepicker'), $('#checkin_datetime').find('.timepicker'));
                } else if (enddatetime) {
                    duration = computeDays($('#checkout_datetime').find('.datepicker'), $('#checkout_datetime').find('.timepicker'),
                        end.find('.datepicker'), end.find('.timepicker'));
                } else {
                    duration = computeDays(start.find('.datepicker'), start.find('.timepicker'),
                        end.find('.datepicker'), end.find('.timepicker'));
                }
            }
            if (oldDuration != duration) {
                row.find('.duration').val(duration);
                row.find('.total-cost.float-submit').trigger('change');
            }
        }
    }

    $(document).on('change', '.datetimepicker.start, .datetimepicker.end', function() {
        const row = $(this).closest('tr');
        const start = row.find('.start');
        const startdate = start.find('.datepicker-submit').val();
        let startdatetime = '';
        if (startdate && startdate != 'Invalid date') {
            startdatetime = startdate + ' ' + start.find('.timepicker').val();
        }
        const end = row.find('.end');
        const enddate = end.find('.datepicker-submit').val();
        let enddatetime = '';
        if (enddate && enddate != 'Invalid date') {
            enddatetime =  + ' ' + end.find('.timepicker').val();
        }

        if (startdatetime && enddatetime) {
            duration = computeDays(start.find('.datepicker'), start.find('.timepicker'),
                        end.find('.datepicker'), end.find('.timepicker'));
            row.find('.duration').val(duration);
        }
        datetimepickerRow(row);
    })

    $('#duration').on('change date-change', function(e) {
        const rows = $('.options-table').find('tr');

        for (let row of rows) {
            row = $(row);
            datetimepickerRow(row);
        }
        $('#transport-modal .btn-success').trigger('click');
        $('#rental-modal .btn-success').trigger('click');
        $('#insurances-modal .btn-success').trigger('click');
        $('#extras-modal .btn-success').trigger('click');
    });

    function optionEventChange() {
        const row = $(this).closest('tr');
        const quantity = row.find('.quantity').val();
        const cost = normalizeSubmit(row.find('.cost').val(), 2);
        let total = cost;
        if (quantity) {
            total = quantity*total;
        } else {
            total = 0;
        }
        if (row.data('daily') == true) {
            let duration = row.find('.duration').val();
            if (!duration) {
                duration = $('#duration').val();
            }
            total = roundTo(total*duration, 2);
        }

        const net = roundTo(total/1.24, 2);
        row.find('.total-cost.float-submit').val(total);
        row.find('.net.float-submit').val(net);
        row.find('.total-cost.float-submit').trigger('calculate');
        row.find('.net.float-submit').trigger('input');
    }

    $(document).on('change', '.options-table .quantity, .options-table .cost', optionEventChange);

    $(document).on('input change', '.options-table .total-cost.float-submit', function() {
        const row = $(this).closest('tr');
        const quantity = row.find('.quantity').val();
        const total = $(this).val();
        let cost = total;
        const net = roundTo(cost/1.24, 2);
        if ((quantity && quantity > 0) || !quantity) {
            if (quantity) {
                cost = cost/quantity;
            }
            if (row.data('daily') == true) {
                const row_duration = row.find('.duration').val();
                if (row_duration) {
                    cost = roundTo(cost/row_duration, 2);
                } else {
                    cost = 0;
                }

            }
            row.find('.cost.float-submit').val(cost);
            row.find('.net.float-submit').val(net);
            // row.find('.total-cost.float-submit').val(total);
            row.find('.cost.float-submit').trigger('input');
            row.find('.net.float-submit').trigger('input');
            // row.find('.total-cost.float-submit').trigger('input');
        } else {
            $(this).val(0);
            $(this).trigger('calculate');
        }
    });

    $(document).on('change', '.payers', function() {
        const tr = $(this).closest('tr');
        const id = tr.attr('id');
        $(this).find('option:not([value="'+$(this).val()+'"])').removeAttr('selected');
        $(this).find('option[value="'+$(this).val()+'"]').attr('selected', true);
        if ($(this).find('option[value="'+$(this).val()+'"]').length == 0) {
            $(this).val($(this).find('option').first().val());
        }
        if (costs[id] && costs[id].payer && costs[id].payer != $(this).find('option:selected').val()) {
            if (costs[id].payer == 'driver') {
                driver.debt -= costs[id].cost;
            } else if (costs[id].payer == 'company') {
                company.debt -= costs[id].cost;
            } else if (costs[id].payer == 'agent') {
                agent.debt -= costs[id].cost;
            }
            costs[id].cost = 0;
        }
        tr.find('.total-cost.float-submit').trigger('calculate');
    });

    $(document).on('input calculate change', '.options-table .total-cost.float-submit', function() {
        const tr = $(this).closest('tr');
        const id = tr.attr('id');
        const total = !isNaN(pf($(this).val())) ? pf($(this).val()) : 0;
        if (!costs[id]) {
            costs[id] = {
                cost: total
            };
        }
        const payers = tr.find('.payers');
        const option = payers.find('option:selected').attr('selected', true);
        if (option.val() == 'driver') {
            driver.debt -= costs[id].cost;
            driver.debt += total;
            costs[id].payer = 'driver';
        } else if (option.val() == 'company') {
            company.debt -= costs[id].cost;
            company.debt += total;
            costs[id].payer = 'company';
        } else if (option.val() == 'agent') {
            agent.debt -= costs[id].cost;
            agent.debt += total;
            costs[id].payer = 'agent';
        }
        changeDepts();

        costs[id].cost = total;
    });

    $('.btn-prices').on('click', function() {
        const $this = $(this);
        const modal = $('#' + $this.data('modal'));
        html = modal.find('table').html();
        modal.modal('show');
    });

    $('#rental-modal .btn-success').on('click', function() {
        const modal = $(this).closest('.prices-modal');
        const table = modal.find('table');
        table.find('input').each(function() {
            const val = $(this).val();
            $(this).attr('value', val);
        });
        html = table.html();
        let total = 0;
        table.find('tbody tr').each(function() {
            total += pf($(this).find('.total-cost.float-submit').val());
        });
        $('#rate').val($('#option-rental').find('.cost.float-submit').val());
        $('#rate').trigger('calculate');
        $('#rental_fee_info').val($('#option-rental').find('.total-cost.float-submit').val());
        $('#rental_fee_info').trigger('calculate');
        $('#distance_rate').val($('#option-additional-mileage').find('.cost.float-submit').val());
        $('#distance_rate').trigger('calculate');
        bookingCalculator.rental_fee = total;
        modal.modal('hide');
    });

    $('#extras-modal .btn-success').on('click', function() {
        const modal = $(this).closest('.prices-modal');
        const table = modal.find('table');
        table.find('input').each(function() {
            $(this).attr('value',$(this).val())
        });
        html = table.html();
        let total = 0;
        table.find('tbody tr').each(function() {
            total += pf($(this).find('.total-cost.float-submit').val());
        });
        bookingCalculator.options_fee = total;
        modal.modal('hide');
    });

    $('#insurances-modal .btn-success').on('click', function() {
        const modal = $(this).closest('.prices-modal');
        const table = modal.find('table');
        table.find('input:not([type="checkbox"])').each(function() {
            $(this).attr('value',$(this).val())
        });
        html = table.html();
        let total = 0;
        table.find('tbody tr').each(function() {
            total += pf($(this).find('.total-cost.float-submit').val());
        });
        bookingCalculator.insurance_fee = total;
        modal.modal('hide');
    });

    $('#transport-modal .btn-success').on('click', function() {
        const modal = $(this).closest('.prices-modal');
        const table = modal.find('table');
        table.find('input:not([type="checkbox"])').each(function() {
            $(this).attr('value',$(this).val())
        });
        html = table.html();
        let total = 0;
        table.find('tbody tr').each(function() {
            total += pf($(this).find('.total-cost.float-submit').val());
        });
        bookingCalculator.transport_fee = total;
        modal.modal('hide');
    });

    $('.prices-modal').on('hidden.bs.modal', function() {
        $(this).find('table').html(html);
        $('.options-table .total-cost.float-submit').trigger('calculate');
    });

    $(document).on('change input', '#distance_rate', function() {
        $('#option-additional-mileage').find('.cost.float-submit').val($(this).val());
        $('#option-additional-mileage').find('.cost.float-submit').trigger('change');
        $('#option-additional-mileage').closest('table').find('input').each(function() {
            $(this).attr('value',$(this).val());
        });
        $('#rental-modal .btn-success').click();
    });

    $(document).on('change input', '#checkin_km, #distance', function () {
        const checked_out_km = pf($('#checkout_km').val());
        const checked_in_km = pf($('#checkin_km').val());
        let quantity = 0;
        const distance = $('#distance').val();
        if (checked_in_km && checked_in_km - checked_out_km > distance && distance > 0) {
            quantity = checked_in_km - checked_out_km - distance;
        }
        $('#option-additional-mileage').find('.quantity').val(quantity);
        $('#option-additional-mileage').find('.quantity').trigger('change');
        $('#option-additional-mileage').closest('table').find('input').each(function() {
            $(this).attr('value',$(this).val());
        });
        $('#rental-modal .btn-success').click();
    });

    $('#program_id').on('change', function() {
        const val = $(this).val();
        if (val == '1') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
            changePayersOrder();
        } else if (val == '2') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
            changePayersOrder();
        } else if (val == '3') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
            changePayersOrder();
        } else if (val == '4') {
            order = {
                driver: 3,
                company: 2,
                agent: 1
            }
            changePayersOrder();
        } else if (val == '5' && agent.value) {
            if (typeof booking_options !== 'undefined') {
                // const payers = $('#rental-modal').find('.payers');
                // payers.each(function() {
                //     $(this).val(agent.value);
                // });
                values.voucher = 0;
                for (const booking_option of booking_options) {
                    if (booking_option.option.option_type == 'rental_charges') {
                        $('#option-'+booking_option.option.slug).find('.payers').val('agent');
                        $('#option-'+booking_option.option.slug).find('.payers').trigger('change');
                        values.voucher += pf($('#option-'+booking_option.option.slug).find('.total-cost.float-submit').val());
                    } else {
                        let value;
                        if (company.value) {
                            value = 'company'
                        } else if (driver.value) {
                            value = 'driver';
                        }
                        $('#option-'+booking_option.option.slug).find('.payers').val(value);
                        $('#option-'+booking_option.option.slug).find('.payers').trigger('change');
                    }
                }
            } else {
                order = {
                    driver: 3,
                    company: 2,
                    agent: 1
                }
                changePayersOrder();
            }
        } else if (val == '6' && agent.value) {
            if (typeof booking_options !== 'undefined') {
                values.voucher = 0;
                for (const booking_option of booking_options) {
                    $('#option-'+booking_option.option.slug).find('.payers').val('agent');
                    $('#option-'+booking_option.option.slug).find('.payers').trigger('change');
                    values.voucher += pf($('#option-'+booking_option.option.slug).find('.total-cost.float-submit').val());
                }
            } else {
                order = {
                    driver: 3,
                    company: 2,
                    agent: 1
                }
                changePayersOrder();
            }
        }
    });

    $('#paymentModal #payer_id').on('change', function () {
        const val = $(this).val();
        let transactor;
        if (val == 'driver') {
            transactor = driver;
        } else if (val == 'company') {
            transactor = company;
        } else {
            transactor = agent;
        }

        const discount = parseFloat($('#discount').val()/100);

        const payment_type_val = $('#paymentModal').find('#payment_type').val();
        if (payment_type_val == payment_type) {
            const rest = roundTo(transactor.debt - transactor.debt*discount - transactor.paid, 2);
            $('#paymentModal').find('#balance').val(rest);
            $('#paymentModal').find('#balance').trigger('input');
            $('#paymentModal').find('#amount').val(rest);
            $('#paymentModal').find('#amount').trigger('input');
        } else if (payment_type_val == pre_auth_type) {
            const authorization = pf($('#excess').val());
            $('#paymentModal').find('#balance').val(roundTo(authorization - transactor.pre_auth, 2));
            $('#paymentModal').find('#balance').trigger('input');
            $('#paymentModal').find('#amount').val(roundTo(authorization - transactor.pre_auth, 2));
            $('#paymentModal').find('#amount').trigger('input');
        } else if (payment_type_val == refund_type) {
            let debt = 0;
            if (transactor.debt - transactor.paid < 0) {
                debt = transactor.debt - transactor.paid;
            }
            $('#paymentModal').find('#balance').val(roundTo(debt, 2));
            $('#paymentModal').find('#balance').trigger('input');
            $('#paymentModal').find('#amount').val(roundTo(debt, 2));
            $('#paymentModal').find('#amount').trigger('input');
        }
    });

    function pay_by_type(transactor, amount) {
        const payment_type_val = $('#paymentModal').find('#payment_type').val();
        let paid = 0;
        if (payment_type_val == payment_type || payment_type_val == refund_type) {
            transactor.paid += pf(amount);
            paid += pf(amount);
        } else if (payment_type_val == pre_auth_type) {
            transactor.pre_auth += pf(amount);
        }
        return paid;
    }

    $('#paymentModal form').on('submit', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            if ($(this).data('referer')) {

            } else {
                const inputs = $(this).find(':input');
                const tr = $('<tr/>').appendTo('.payments-table tbody');
                inputs.each(function () {
                    const input = $(this);
                    const newInput = $('<input />');
                    if (input.attr('type') == 'files') {
                        newInput = input.clone();
                    } else if (input.hasClass('datepicker-submit')) {
                        const parent = input.closest('.datetimepicker');
                        newInput.attr('name', 'payments['+payment_number+']['+parent.attr('name')+']');
                        newInput.attr('value', input.val() + ' ' + parent.find('.timepicker').val());
                    } else {
                        newInput.attr('name', 'payments['+payment_number+']['+input.attr('name')+']');
                        newInput.attr('value', input.val());
                    }
                    newInput.attr('data-name', input.attr('name'));
                    tr.append(newInput.addClass('d-none'));
                });
                tr.append("<td>"+$(this).find('#payer_id').find('option[value="'+$(this).find('#payer_id').val()+'"]').text()+"</td>");
                tr.append("<td>"+$(this).find('#amount').val()+"</td>");
                tr.append("<td>"+$(this).find('#payment_datetime').find('.datepicker').val() + ' ' + $(this).find('#payment_datetime').find('.timepicker').val() +"</td>");
                let added_payment_type = $(this).find('#payment_type').val();
                if (added_payment_type == payment_type) {
                    added_payment_type = 'Είσπραξη';
                } else if (added_payment_type == refund_type) {
                    added_payment_type = 'Επιστροφή Χρημάτων';
                } else if (added_payment_type == pre_auth_type) {
                    added_payment_type = 'Εγγύηση';
                } else if (added_payment_type == refund_pre_auth_type) {
                    added_payment_type = 'Επιστροφή Χρημάτων Εγγύησης';
                }
                tr.append("<td>"+added_payment_type+"</td>");
                tr.append("<td>"+$(this).find('#payment_method').val()+"</td>");
                tr.append("<td><span class='btn btn-danger remove-payment'><i class='fas fa-trash'></i></span></td>");

                const payer = $(this).find('#payer_id').val();
                let paid = $(this).find('#amount').val();
                if (payer == 'driver') {
                    paid = pay_by_type(driver, paid);
                } else if (payer == 'company') {
                    paid = pay_by_type(company, paid);
                } else {
                    paid = pay_by_type(agent, paid);
                }
                values.total_paid += pf(paid);
                changeDepts();
                $('#paymentModal').modal('hide');
                $('#reference').val('');
                payment_number++;
            }
        }
    });

    $(document).on('click', '.remove-payment', function () {
        const tr = $(this).closest('tr');
        const payer = tr.find('input[data-name="payer_id"]').val();
        let paid = -pf(tr.find('input[data-name="amount"]').val());
        if (payer == 'driver') {
            paid = pay_by_type(driver, paid);
        } else if (payer == 'company') {
            paid = pay_by_type(company, paid);
        } else {
            paid = pay_by_type(agent, paid);
        }
        values.total_paid += pf(paid);
        changeDepts();
        tr.remove();
    });

    $(document).on('change', '.prices-modal input[type="checkbox"]', function() {
        if ($(this).prop('checked') == true) {
            $(this).attr('checked', 'checked');
            $(this).next().val(1);
            $(this).next().trigger('change');
        } else {
            $(this).removeAttr('checked');
            $(this).next().val(0);
            $(this).next().trigger('change');
        }
    });

    setTimeout(() => {
        const val = $('#program_id').val();
        if (val == '1') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
        } else if (val == '2') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
        } else if (val == '3') {
            order = {
                driver: 2,
                company: 1,
                agent: 3
            }
        } else if (val == '4') {
            order = {
                driver: 3,
                company: 2,
                agent: 1
            }
        }
        createPayers();
        $('#transport-modal .btn-success').trigger('click');
        $('#rental-modal .btn-success').trigger('click');
        $('#insurances-modal .btn-success').trigger('click');
        $('#extras-modal .btn-success').trigger('click');
        $('#booking-form').dirtyForms();
    }, 200);

    $('#create_next-btn').on('click', function(e) {
        if($('#booking-form').dirtyForms("isDirty")) {
            e.preventDefault();
            $('#bookingModal').modal('show');
        }
    });

    $('#bookingModal button[type="submit"]').on('click', function() {
        if ($(this).hasClass('btn-save')) {
            $('#create_next').val(1);
            $('#booking-form').submit();
        } else {
            $('#create_next-form').submit();
        }
    });

    $(document).on('click', '.btn-payment', function () {
        const type = $(this).data('type');
        $('#paymentModal').find('#payment_type').val(type);
        $('#paymentModal').removeAttr('data-referer');
        const transactors = $('#paymentModal').find('#payer_id')
        transactors.empty();
        if (driver.debt > 0){
            transactors.append(`<option value="driver">${driver.text}</option>`);
            // select.append();
        }
        if (company.debt > 0) {
            transactors.append(`<option value="company">${company.text}</option>`);
            // select.append(`<option value="App\\Company-${company.value}" data-payer='company'>${company.text}</option>`);
        }
        if (agent.value > 0) {
            transactors.append(`<option value="agent">${agent.text}</option>`);
            // select.append(`<option value="App\\Agent-${agent.value}" data-payer='agent'>${agent.text}</option>`);
        }

        transactors.trigger('change');

        selectorSearch(checkout_station_id.getValue(), station_id).done(function (response) {
            if (checkout_station_id.getValue() != station_id.getValue()) {
                station_id.setValue(checkout_station_id.getValue());
            }
            $('#paymentModal').modal('show');
        });
    });

    $(document).on('click', '.edit_payment', function () {
        const payment_modal = $('#paymentModal');
        payment_modal.modal('show');
        const tr = $(this).closest('tr');
        payment_modal.data('referer', tr.data('number'));
        const inputs = tr.find('input');
        inputs.each(function() {
            const modal_field = payment_modal.find('[name="'+$(this).data('name')+'"]');
            if ($(this).data('name') == 'payer_id') {
                const payer = tr.find('[data-name="payer_type"]');
                modal_field.empty();
                if (payer.val() == 'App\\Driver') {
                    modal_field.append(`<option value="driver">${driver.text}</option>`);
                } else if (payer.val() == 'App\\Company') {
                    modal_field.append(`<option value="company">${company.text}</option>`);
                } else if (payer.val() == 'App\\Agent') {
                    modal_field.append(`<option value="agent">${agent.text}</option>`);
                }
            } else {
                modal_field.val($(this).val());
                modal_field.trigger('change');
            }
        });
    });
});
