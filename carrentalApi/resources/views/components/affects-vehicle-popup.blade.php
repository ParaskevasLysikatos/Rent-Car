<script>
    $(document).ready(function () {
        $('form{{ $form }}').submit(function (e) {
            e.preventDefault();
            var form = this;

            const popup = $('#affects-vehicle-popup');
            popup.modal('show');

            popup.find('.continue').on('click', function () {
                form.submit();
            });

            popup.find('.confirm').on('click', function() {
                $(form).append('<input type="hidden" name="affectsVehicle" value="1" />');
                form.submit();
            });
        })
    });
</script>

<div id="affects-vehicle-popup" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Επιβεβαίωση ενημέρωσης οχήματος')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Θέλετε να ενημερώσετε και το όχημα;') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default continue">
                    {{__('Συνέχεια δίχως ενημέρωση οχήματος')}}
                </button>
                <button type="button" class="btn btn-success confirm">
                    {{__('Ενημέρωση')}}
                </button>
            </div>
        </div>
    </div>
</div>
