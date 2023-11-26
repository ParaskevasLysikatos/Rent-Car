$(document).on('click', ".maintenancesVehicle", function () {
    var car_id = $(this).data('id');
    $('#maintenancesOptions').hide();
    $('#maintenancesOptions input').prop('checked', false);
    $('#maintenancesOptions input').attr("data-car",''+car_id);
    $.post(displayMaintenances, {
        car: car_id
    }, function (response) {
        console.log(response);
        $.each(response, function( index, value ){
            $('#maintenance_'+value['type']).prop('checked', true);
        });

        $('#maintenancesOptions').show(500);
    });
});


$('#maintenanceModal').on('hidden.bs.modal', function () {
    window.location.reload();
});
