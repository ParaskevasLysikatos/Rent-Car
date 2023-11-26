$(document).on('click', '.image_link', function () {
    var image_id = $(this).attr('data-image_id');
    var image_link_id = $(this).attr('data-image_link_id');
    var image_link_type = $(this).attr('data-image_link_type');
    $.post(removeImageLink, {
        image_id: image_id,
        image_link_id: image_link_id,
        image_link_type: image_link_type
    },function (response) {
        console.log("response:"+response);
        $('.image_'+image_id).hide(500);
        $('.image_'+image_id).remove();
    });
});

$(document).on('click', '.rm-document', function () {
    $(this).closest('.document').remove();
});
