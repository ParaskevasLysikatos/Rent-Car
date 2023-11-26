var z_index = 1051;

// $(document).on('click', '.btn-open-modal', function () {
//     const $this = $(this);
//     const modal = $($this.data('modal'));
//     forms = modal.find('form');
//     for (const form of forms) {
//         form.reset();
//     }
//     console.log(z_index);
//     modal.css('z-index', z_index++);
//     modal.data('referer', $this.data('referer'));
//     modal.modal('show');
// });

$(document).on('shown.bs.modal', '.modal-for-edits', function () {
    $(this).css('z-index', z_index++);
});

$(document).on('click', '.btn-cancel', function(e) {
    const cancel = $(this);
    const modal = $(cancel.closest('.modal'));
    if (modal) {
        e.preventDefault();
        modal.modal('hide');
    }
});

window.z_index = z_index;
