@modal([
    'name' => 'bookingModal'
])
    <div class="modal-header">
        <h4 class="modal-title">{{__('Η φόρμα έχει τροποποιηθεί δίχως να αποθηκευτεί')}}</h4>
    </div>
    <div class="modal-body">
        <h4>{{ __('Επιλέξτε τον τρόπο που θα συνεχίσετε:') }}</h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">
            {{__('Ακύρωση')}}
        </button>
        <button class="btn btn-secondary btn-without-save" type="submit">
            {{__('Συνέχεια δίχως Αποθήκευση')}}
        </button>
        <button class="btn btn-primary btn-save" type="submit">
            {{__('Συνέχεια με Αποθήκευση')}}
        </button>
    </div>
@endmodal
