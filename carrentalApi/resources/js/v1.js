
$( document ).ready(function() {

    // MAKE BUTTONS SCROLLABLE
    $(window).scroll(function(){
        if( $(window).scrollTop() > 0) {
            $('.fixed-bottom-menu').addClass('lock-bottom');
        }else{
            $('.fixed-bottom-menu').removeClass('lock-bottom');
        }
    });

    $(document).on('keyup', "input:not(#notes, #checkout_notes, #checkin_notes, #password, #email), textarea:not(#notes, #checkout_notes, #checkin_notes, #password, #email)", function (e) {
        if (e.which >= 97 && e.which <= 122) {
            var newKey = e.which - 32;
            // I have tried setting those
            e.keyCode = newKey;
            e.charCode = newKey;
        }
        var start = this.selectionStart,
        end = this.selectionEnd;

        $(this).val(($(this).val()).toUpperCase());

        this.setSelectionRange(start, end);

    });


     //Show-Hide Password
    $(document).on('click', '#trigger-password-view', function () {
        if($(this).attr('data-status') === 'disabled'){
            $(this).html('<em class="fa fa-eye-slash"></em>');
            $(this).attr('data-status', 'active');
            $('#password').attr('type', 'text');
        }else{
            $(this).html('<em class="fa fa-eye"></em>');
            $(this).attr('data-status', 'disabled');
            $('#password').attr('type', 'password');
        }
    });


    //Hide field on payment method change
    $(document).on('change', '#payment_method', function () {
        if($(this).val()==='cash'){
            $('#reference_block').addClass('d-none');
        }else{
            $('#reference_block').removeClass('d-none');
        }
    });


    //Trigger buttons save, save and close etc
    $(document).on('click', '.trigger-activator', function () {
        $('.trigger-buttons button[value="'+$(this).val()+'"').click();
    });


    //Change popup title based on Button Text
    $(document).on('click', '.change-popup-title', function () {
        $('#custom-rental-popup-title').text($(this).text());
    });



    //check if foo parameter is set to anything
    var urlParams = new URLSearchParams(window.location.search); //get all parameters
    var print = urlParams.get('print_status'); //value else NULL
    if(print) {
        alert('FOO EXISTS');
    }



    //Calculate TAX
    $(document).on('click', '#subtotalCalc, #totalCalc', function () {
        var fpa         = $('#fpa').val();
        var subtotal    = $('#subtotal').val();
        var total       = $('#total').val();
        var price       = 0;
        var copyText

        if($(this).attr('id') === 'subtotalCalc') {
            price = total / (1 + (fpa / 100));
            $('#subtotal').val( price.toFixed(2) );
            copyText = document.getElementById("subtotal");
        }else {
            price = subtotal * (1 + (fpa / 100));
            $('#total').val( price.toFixed(2) );
            copyText = document.getElementById("total");
        }

        $('#fpaPrice').val( ($('#total').val() -  $('#subtotal').val() ).toFixed(2) );
        copyText.select();
        document.execCommand("copy");
    });

    //Reset Tax Fields
    $(document).on('click', '#resetTaxfields', function () {
        $('#fpa').val(24);
        $('#subtotal').val('');
        $('#total').val('');
    });

    //Reset Inputs for popups
    $(document).on('click', '.btn-payment', function() {
       $('#balance').val(0);
       $('#reference').val('');
       $('#payment_method').val('cash').trigger('change');
       $('#credit_card_number').val('');
       $('#comments').val('');
    })

    //Calculate Age
   $(document).on('change', 'input[name="birthday"]', function () {
        if($(this).val()!==''){
            var dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            if(age>0)
                $('#age_number').html( '('+age + ' ' + $('#age_number').attr('data-title')+')'  );
            else
                $('#age_number').html('');
        }
   });
    $('input[name="birthday"]').trigger("change");



    //Change booking date time from to with one input
    $(document).on('change', 'input[name="checkout_datetime_single"]', function () {
        var d = new Date($(this).val());
        $('#checkout_datetime_from').val(   d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()    ).trigger('change');
        $('#checkout_datetime_to').val(     d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()    ).trigger('change');
    });

    //Change booking date time from to with one input
    $(document).on('change', 'input[name="checkin_datetime_single"]', function () {
        var d = new Date($(this).val());
        $('#checkin_datetime_from').val(   d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()    ).trigger('change');
        $('#checkin_datetime_to').val(     d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()    ).trigger('change');
    });

    $(document).on('click', 'th', function () {
        if ($(this).attr('data-orderBy')) {
            var queryParams = new URLSearchParams(window.location.search);
            queryParams.set("orderBy", $(this).attr('data-orderBy') );
            queryParams.set("orderByType", $(this).attr('data-orderByType') );
            history.replaceState(null, null, "?"+queryParams.toString());
            window.location.href =  window.location.href;
        }
        // var current_url = window.location.href;
        // var currentURL = window.location.href;
        // window.location.href = currentURL.split('?')[0]+"?orderBy-"+$(this).attr('data-orderBy')+"="+$(this).attr('data-orderByType');
    });


});
