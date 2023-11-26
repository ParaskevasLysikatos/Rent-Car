$(document).ready(function () {

    //Select all visits with checkbox
    $(document).on('click', '#select_all_visits', function () {
        if ($('#select_all_visits').is(':checked'))
            $('.visits_checkbox').prop('checked', true);
        else
            $('.visits_checkbox').prop('checked', false);
    });

    //Delete single visit
    $(document).on('click', '.delete_visit', function () {
        if (confirm("Διαγραφή;")) {
            var ids = [];
            ids.push($(this).attr('data-id'));
            console.log(ids);
            $("#visit_index_" + $(this).attr('data-id')).hide();
            $("#visit_index_" + $(this).attr('data-id')).remove();
            $.post(delete_route, {
                ids: ids
            }, function ($return) {
                console.log($return);
            });
        } else
            console.log("Canceled");
    });

    //Delete multiple visits
    $(document).on('click', '#delete_multiple_visits', function () {
        var ids = [];
        $('.visits_checkbox:checkbox:checked').each(function () {
            ids.push($(this).attr('data-id'));
        });
        if (ids.length > 0) {
            if (confirm("Διαγραφή;")) {
                console.log(ids);
                $.post(delete_route, {
                    ids: ids
                }, function ($return) {
                    $('.visits_checkbox:checkbox:checked').each(function () {
                        $("#visit_index_" + $(this).attr('data-id')).hide();
                        $("#visit_index_" + $(this).attr('data-id')).remove();
                    });
                });
            } else
                console.log("Canceled");
        }
    });


    $(document).on('click', '.visit_icon', function () {
        var id = $(this).attr('data-id');
        $.post('./delete_icon', {
            id: id
        }, function (response) {
            console.log("response:" + response);
            $('.image_' + id).hide(500);
            $('.image_' + id).remove();
        });
    });


});
