$(document).on('change', '#active_daily_cost', function () {
    if($(this).is(':checked')){
        $('#cost_max').prop('disabled', false);
        $('.cost_max_view').removeClass('d-none');
    }else{
        $('#cost_max').prop('disabled', true);
        $('.cost_max_view').addClass('d-none');
    }
});

$(document).on('change', '.acriss_select', function (e) {
    var active = 0;
    $('.acriss_select').each(function (index) {
        if( $(this).val() !== null ){
            active++;
        }
    });
    if(active === 4){
        $('#acriss_submit').prop('disabled', false);
    }else{
        $('#acriss_submit').prop('disabled', true);
    }
});
$(document).on('click', '#acriss_submit', function (e) {
    $("#international_code").val(
        $('#acriss_category').val()+
        $('#acriss_type').val()+
        $('#acriss_transmission').val()+
        $('#acriss_fuel_air').val()
    );
});
