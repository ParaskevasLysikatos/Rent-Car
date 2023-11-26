$(document).on('click', '#orderItems', function () {
    var ids = []; $('.languageOrder').each(function (index) {
        ids.push($(this).attr('data-id'));
    }); $.post(languageOrder, {
        ids: ids
    }, function (response) {
        window.location.reload();
    });
});
