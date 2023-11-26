<script>
    $(document).ready(function () {
        $('form{{ $form }}').submit(function (e) {
            var form = this;
            $newKm = $(form).find('input[name="{{ $inputName }}"]').val();
            $currentKm = {{ $vehicleKm }};
            if ($newKm > $currentKm) {
                e.preventDefault();

                const popup = $('#higher-km-confirmation');
                popup.modal('show');

                popup.find('.confirm').on('click', function() {
                    form.submit();
                });
            }
        })
    });
</script>

<div id="higher-km-confirmation" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Επιβεβαίωση χιλιομέτρων')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Τα χιλιόμετρα που έχετε προσθέσει είναι μεγαλύτερα από τα χιλιόμετρα που έχει αυτή τη στιγμή το αμάξι.
                Η αλλαγή δεν θα επηρεάσει το αμάξι. Είστε σίγουροι ότι θέλετε να συνεχίσετε;') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{__('Ακύρωση')}}
                </button>
                <button type="button" class="btn btn-success confirm">
                    {{__('Ενημέρωση')}}
                </button>
            </div>
        </div>
    </div>
</div>
