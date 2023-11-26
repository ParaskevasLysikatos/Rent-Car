$(document).ready(function () {

    //Select all cars with checkbox
    $(document).on('click', '#select_all_cars', function () {
        if ($('#select_all_cars').is(':checked'))
            $('.cars_checkbox').prop('checked', true);
        else
            $('.cars_checkbox').prop('checked', false);
    });

    //Delete single car
    $(document).on('click', '.delete_car', function () {
        if (confirm("Διαγραφή;")) {
            var ids = [];
            ids.push($(this).attr('data-id'));
            console.log(ids);
            $("#car_index_" + $(this).attr('data-id')).hide();
            $("#car_index_" + $(this).attr('data-id')).remove();
            $.post(delete_route, {
                ids: ids
            }, function ($return) {
                console.log($return);
            });
        } else
            console.log("Canceled");
    });

    //Delete multiple cars
    $(document).on('click', '#delete_multiple_cars', function () {
        var ids = [];
        $('.cars_checkbox:checkbox:checked').each(function () {
            ids.push($(this).attr('data-id'));
        });
        if (ids.length > 0) {
            if (confirm("Διαγραφή;")) {
                console.log(ids);
                $.post(delete_route, {
                    ids: ids
                }, function ($return) {
                    $('.cars_checkbox:checkbox:checked').each(function () {
                        $("#car_index_" + $(this).attr('data-id')).hide();
                        $("#car_index_" + $(this).attr('data-id')).remove();
                    });
                });
            } else
                console.log("Canceled");
        }
    });


    $(document).on('click', '.car_icon', function () {
        var id = $(this).attr('data-id');
        $.post('./delete_icon', {
            id: id
        }, function (response) {
            console.log("response:" + response);
            $('.image_' + id).hide(500);
            $('.image_' + id).remove();
        });
    });

    $(document).on('click', '.delete_plate', function () {
        var item = {};
        item.index  = $(this).data('index');
        item.id     = $(this).data('id');
        if(typeof item.id === "undefined"){
            $('#plate'+item.index).hide(500);
        }else{
            if(confirm('Διαγραφή'))
            $.post(deletePlate, {
                item: item
            }, function (response) {
                console.log("response:" + response);
                $('#plate'+item.index).hide(500);
            });
        }
    });

    $(document).on('click', '.preview_fee', function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_type').val($(this).data('type'));
        $('#edit_title').val($(this).data('title'));
        $('#edit_description').val($(this).data('description'));
        $('#edit_fee').val($(this).data('fee'));
        $('#edit_date_start').val($(this).data('start')).change();
        $('#edit_date_expiration').val($(this).data('end')).change();
        $('#edit_date_payed').val($(this).data('payed')).change();
        $('#documents').html($(this).data('documents'));
    });

    $(document).on('click', '#update_fee', function () {
        var item ={};
        item.id                 = $('#edit_id').val();
        item.type               = $('#edit_type').val();
        item.title              = $('#edit_title').val();
        item.description        = $('#edit_description').val();
        item.fee                = $('#edit_fee').val();
        item.date_start         = $('input[name="edit_date_start"]').val();
        item.date_expiration    = $('input[name="edit_date_expiration"]').val();
        item.date_payed         = $('input[name="edit_date_payed"]').val();
        var filesInput          = $('input[name="edit_files[]"]');
        const files             = filesInput[0].files;

        var formData = new FormData();
        formData.append('fee', item);

        $.each(item, function (key, value) {
            formData.append('fee['+key+']', value);
        });

        $.each(files,function(j, file){
            formData.append('files['+j+']', file);
        })

        $.ajax({
            url: updateFee,
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false
        }).done(function (response) {
            $('#notification').html(response);
        });
    });

    $(document).on('click', '.delete_fee', function () {
        var item = {};
        item.index = $(this).data('index');
        item.id = $(this).data('id');
        if (confirm('Διαγραφή'))
            $.post(deleteFee, {
                item: item
            }, function (response) {
                console.log("response:" + response);
                $('#fee' + item.index).hide(500);
            });
    });

    $(document).on('change', '#chooseLocation', function () {
        var id = $('#chooseLocation').val();
        $('#chooseStation').prop('disabled', 'disabled');
        $('#transferCars').prop('disabled', 'disabled');
        $.post(chooseLocation, {
            id: id
        }, function (response) {
            $('#chooseStation').html("<option value='0' disabled selected> - </option>");
            $.each(response, function( index, value ){
                $('#chooseStation').append("<option value='"+value['station_id']+"'>"+value['title']+"</option>");
                $('#chooseStation').prop('disabled', false);
            });
        });
    });

    $(document).on('change', '#chooseStation', function () {
        $('#transferCars').prop('disabled', false);
        $('#chooseType').prop('disabled', false);
    });

    $(document).on('click', '#transferCars', function () {
        var ids = [];
        $('.data_checkbox:checkbox:checked').each(function () {
            ids.push($(this).attr('data-id'));
        });
        if (ids.length > 0) {
            if($('#chooseType').val().length > 0){
                if (confirm("Μεταφορά;")) {
                    $.post(transferCars, {
                        ids: ids,
                        location: $('#chooseStation').val(),
                        type:  $('#chooseType').val()
                    }, function (response) {
                       $('#transferNotifications').html(
                           '<p class="alert alert-success">' +response+
                           '</p>'
                       );
                    });
                } else
                    console.log("Canceled");
            }else{
                $('#chooseType').css('border', '1px solid red');
            }
        }
    });

    $(document).on('click', ".maintenancesCar", function () {
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


    $(document).on('click', ".maintenance_option", function () {
        var car_id  = $(this).attr('data-car');
        var active  = $(this).is(':checked');
        var type    = $(this).attr('data-type');

        $.post(updateMaintenances, {
            car: car_id,
            active:active,
            type:type
        }, function (response) {
            console.log(response)
        });
    });

});
