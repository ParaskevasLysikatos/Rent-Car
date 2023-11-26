
function roundTo(n, digits) {
    if (digits === undefined) {
        digits = 0;
    }

    var multiplicator = Math.pow(10, digits);
    n = parseFloat((n * multiplicator).toFixed(11));
    var test =(Math.round(n) / multiplicator);
    return +(test.toFixed(digits));
}

window.roundTo = roundTo;

function clearThousandsSeparator(value) {
    return value.replace(thousandsSeparator, '');
}

function sendingFloat(value) {
    const keepOnlyDigits = new RegExp('[^\\d'+decimalSeparatorReg+']', 'g');
    value = value.replace(keepOnlyDigits, '');
    const replaceDecimal = new RegExp(decimalSeparatorReg, 'g');
    value = value.replace(replaceDecimal, '.');
    return value;
}

function normalize(value) {
    return value.replace(/\./g, decimalSeparatorReg);
}

function normalizeSubmit(value, decimals = '') {
    const keepOnlyDigits = new RegExp('[^\\-\\d'+decimalSeparatorReg+']', 'g');
    value = value.replace(keepOnlyDigits, '');
    const replaceDecimal = new RegExp(decimalSeparatorReg, 'g');
    value = value.replace(replaceDecimal, '.');
    if (decimals) {
        value = roundTo(value, decimals);
        // // Keep only {decimals} digits past the decimal point:
        // const reg = new RegExp("(\\.\\d{"+decimals+"})\\d+");
        // value = value
        // .replace(reg, '$1');
    }
    return value;
}

window.normalizeSubmit = normalizeSubmit;

function showingFloat(str, decimals = '') {
    const keepOnlyDigits = RegExp('[^\\-\\d'+decimalSeparatorReg+']', 'g');
    const removeDuplicates = RegExp('^(\\d*'+decimalSeparatorReg+')(.*)'+decimalSeparatorReg+'(.*)$');
    str = str
            // Keep only digits and decimal points:
            .replace(keepOnlyDigits, "")
            // Remove duplicated decimal point, if one exists:
            .replace(removeDuplicates, '$1$2$3');

    if (decimals) {
        // Keep only {decimals} digits past the decimal point:
        const reg = new RegExp(decimalSeparatorReg+"(\\d{"+decimals+"})\\d+");
        str = str
        .replace(reg, decimalSeparatorReg+'$1');
    }

    // Add thousands separators:
    const reg = new RegExp("(?<!"+decimalSeparatorReg+".*)\\B(?=(\\d{3})+(?!\\d))", 'g');
    str = str.replace(reg, thousandsSeparator);

    return str;
}

function normalizeCoord(str) {
    const keepOnlyDigits = RegExp('[^\\d\\.]', 'g');
    const removeDuplicates = RegExp('^(\\d*\\.)(.*)\\.(.*)$');
    str = str.replace(keepOnlyDigits,"").replace(removeDuplicates, '$1$2$3');
    // $str = str.replace(/(?=d{3})/, ".");
    return str;
}

function setFloatInput(input, val) {
    if (val !== '') {
        $this = $(input);
        const previousVal = $this.val();
        const countFirst = previousVal.split(thousandsSeparator)[0].length;
        var position = input.selectionStart;
        const nextVal = showingFloat(normalize(val), 2);
        const countNormalized = nextVal.split(thousandsSeparator)[0].length;
        $this.val(nextVal);
        if ((countFirst == 3 || countFirst == 4) && countNormalized==1) {
            position++;
        } else if (countFirst == 1 && countNormalized==3) {
            position--;
        }
        input.selectionEnd = position;
    } else {
        $(input).val(val);
    }
}

function submitFunction(submit) {
    const val = submit.val();
    const input = submit.prev();
    setFloatInput(input[0], val);
}

$(document).on('change input calculate', '.float-submit', function (e) {
    const $this = $(this);
    submitFunction($this);
});

$('.coord-input').on('input', function() {
    $this = $(this);
    $this.val(normalizeCoord($this.val()));
});

function floatFunction(float) {
    const submit = float.next();
    if (float.val() !== '') {
        submit.val(normalizeSubmit(float.val(), 2));
    } else {
        submit.val('');
    }
    return submit;
}

$(document).ready(function() {
    const floats = $('.float-input');
    // floats.on('input', setFloatInput);
    $(document).on('input', '.float-input', function () {
        const $this = $(this);
        $this.val(showingFloat($this.val()));
    });

    $(document).on('change', '.float-input', function () {
        const $this = $(this);
        const submit = floatFunction($this);
        submit.trigger('input');
    });

    for (let float of floats) {
        float = $(float);
        let idStr = float.attr('id') ? 'id="'+float.attr('id')+'"' : '';
        let htmlClass = float.attr('class');
        htmlClass = htmlClass.replace('float-input', '');
        const name = float.attr('name');
        let value = "";
        if (float.val()) {
            value = float.val();
        }
        const floatSubmit = $('<input type="hidden" class="'+htmlClass+' float-submit" '+idStr+' name="'+name+'" value="'+value+'"/>').insertAfter(float);
        float.removeAttr('id');
        float.removeAttr('name');
        submitFunction(floatSubmit);
        // floatSubmit.trigger('change');
        // float.trigger('input');
        floatFunction(float);
    }
});
