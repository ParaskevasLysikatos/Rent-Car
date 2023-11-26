<div id="maintenanceModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Συντήρηση αυτοκινήτων')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;
                </button>
            </div>
            <div class="modal-body">
                <div id="maintenancesOptions">

                    <div class="d-flex">
                        <label class="p-2">{{__('Κάυσιμα')}}</label>
                        <label class="switch p-2 ml-auto">
                            <input class="maintenance_option" id="maintenance_fuel" type="checkbox" data-car="" data-type="fuel">
                            <span class="slider round maintenance_fuel"></span>
                        </label>
                    </div>

                    <div class="d-flex">
                        <label class="p-2">{{__('Πλύσιμο')}}</label>
                        <label class="switch p-2 ml-auto">
                            <input class="maintenance_option" id="maintenance_wash" type="checkbox" data-car="" data-type="wash">
                            <span class="slider round maintenance_wash"></span>
                        </label>
                    </div>

                    <div class="d-flex">
                        <label class="p-2">{{__('Service')}}</label>
                        <label class="switch p-2 ml-auto">
                            <input class="maintenance_option" id="maintenance_service" type="checkbox" data-car="" data-type="service">
                            <span class="slider round maintenance_service"></span>
                        </label>
                    </div>

                    <div class="d-flex">
                        <label class="p-2">{{__('Άλλο')}}</label>
                        <label class="switch p-2 ml-auto">
                            <input class="maintenance_option" id='maintenance_other' type="checkbox" data-car="" data-type="other">
                            <span class="slider round maintenance_other"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{__('Ακύρωση')}}
                </button>
            </div>
        </div>
    </div>
</div>
