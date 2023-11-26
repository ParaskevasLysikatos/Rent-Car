$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {

    //Select all categories with checkbox
    $(document).on('click', '#select_all', function () {
        if ($('#select_all').is(':checked'))
            $('.data_checkbox').prop('checked', true);
        else
            $('.data_checkbox').prop('checked', false);
    });

    //Delete single category
    $(document).on('click', '.delete_single', function () {
        if(confirm("Διαγραφή;")){
            var ids = [];
            ids.push($(this).attr('data-id'));
            console.log(ids);
            $("#index_"+$(this).attr('data-id')).hide();
            $("#index_"+$(this).attr('data-id')).remove();
            $.post(delete_route, {
                ids:ids
            },function ($return) {
                console.log($return);
            });
        }else
            console.log("Canceled");
    });

    //Delete multiple categories
    $(document).on('click', '#delete_multiple', function () {
        var ids=[];
        $('.data_checkbox:checkbox:checked').each(function () {
            ids.push($(this).attr('data-id'));
        });
        if(ids.length>0){
            if(confirm("Διαγραφή;")){
                console.log(ids);
                $.post(delete_route, {
                    ids:ids
                },function ($return) {
                    $('.data_checkbox:checkbox:checked').each(function () {
                        $("#index_"+$(this).attr('data-id')).hide();
                        $("#index_"+$(this).attr('data-id')).remove();
                    });
                });
            }else
                console.log("Canceled");
        }
    });


    $(document).on('click', '.single_icon', function () {
        var id = $(this).attr('data-id');
        $.post('./delete_icon', {
            id:id
        },function (response) {
            console.log("response:"+response);
            $('.image_'+id).hide(500);
            $('.image_'+id).remove();
        });
    });

    $(document).on('click', '#redirect_edit_licence_plate', function () {
        $('a[href="#license_plates_info"]').tab('show');
    });

    $(document).on('click', '.select-driver-type', function () {
        var driver_id_list = $('#driver_id_list');
        var driver_external_name = $('#driver_employee_list');
        var driver_external_document = $('#external_driver_document');
        var driver_external_licence = $('#external_driver_licence');
        if (!$('#driver_employee').is(':checked')) {
            reverse_visibility(driver_id_list, driver_external_name);
            reverse_visibility(driver_id_list, driver_external_document);
            reverse_visibility(driver_id_list, driver_external_licence);
            $('#external_document_id').addClass('d-none');
            $('#external_driver_licence_id').addClass('d-none');
            $('#external_document_id').removeClass('d-flex');
            $('#external_driver_licence_id').removeClass('d-flex');
            driver_id_list.find('select').attr('required', '');
            driver_external_name.find('select').removeAttr('required');
        } else {
            reverse_visibility(driver_external_name, driver_id_list);
            reverse_visibility(driver_external_document, driver_id_list);
            reverse_visibility(driver_external_licence, driver_id_list);
            $('#external_document_id').addClass('d-flex');
            $('#external_driver_licence_id').addClass('d-flex');
            $('#external_document_id').removeClass('d-none');
            $('#external_driver_licence_id').removeClass('d-none');
            driver_id_list.find('select').removeAttr('required', '');
            driver_external_name.find('select').attr('required');
        }
    });

});

function reverse_visibility(elemtnet1, element2) {
    elemtnet1.prop('disabled', false);
    elemtnet1.prop('hidden', false);
    element2.prop('disabled', true);
    element2.prop('hidden', true);
}

$(document).on('dblclick', '.listing-table tr', function() {
    const $this = $(this);
    const url = $this.find('.actions').find('.edit-btn').attr('href');
    if (url) {
        window.location.href = url;
    }
});
