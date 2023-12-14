<div id="transferModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Μεταφορά αυτοκινήτων')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;
                </button>
            </div>
            <div class="modal-body">
                <div id="locationSelect">
                    <label>{{__('Γεωγραφικό Διαμέρισμα')}}</label>
                    <select id="chooseLocation" class="form-control">
                        <option selected
                                disabled>{{__('Επιλέξτε τοποθεσία')}}</option>
                        @foreach($locations as $location)
                            <option
                                value="{{$location->id}}">{{$location->getProfileByLanguageId($lng)->title ?? __('Μη Μεταφρασμένο')}}</option>
                        @endforeach
                    </select>
                </div>
                <div id="stationSelect" class="mt-3">
                    <label>{{__('Σταθμός')}}</label>
                    <select id="chooseStation" class="form-control">
                        <option selected disabled>{{__('Επιλέξτε σταθμό')}}</option>
                    </select>
                </div>
                <div id="transferType" class="mt-3">
                    <label for="chooseType">{{__('Τύπος')}}</label>
                    <div class="input-group mb-3">
                        <select name="chooseType" id="chooseType" class="form-control">
                            @foreach(App\TransitionType::all() as $tft)
                                <option value="{{$tft->id}}">{{ $tft->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="transferNotifications" class="mt-3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{__('Ακύρωση')}}
                </button>
                <button class="btn btn-success" id="transferCars" disabled>
                    {{__('Ενημέρωση')}}
                </button>
            </div>
        </div>
    </div>
</div>
