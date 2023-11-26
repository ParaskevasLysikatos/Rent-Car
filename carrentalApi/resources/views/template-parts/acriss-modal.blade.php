<div class="modal fade" id="acress_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ _('Δημιουργία κωδικού ACRISS') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div>
                            <label for="acriss_category">{{__('Κατηγορία')}}</label>
                            <select class="form-control acriss_select" id="acriss_category" size="17">
                                <option value="M">M Mini</option>
                                <option value="N">N Mini Elite</option>
                                <option value="E">E Economy</option>
                                <option value="H">H Economy Elite</option>
                                <option value="C">C Compact</option>
                                <option value="D">D Compact Elite</option>
                                <option value="I">I Intermediate</option>
                                <option value="J">J Intermediate Elite</option>
                                <option value="S">S Standard</option>
                                <option value="R">R Standard Elite</option>
                                <option value="F">F Fullsize</option>
                                <option value="G">G Fullsize Elite</option>
                                <option value="P">P Premium</option>
                                <option value="U">U Premium Elite</option>
                                <option value="L">L Luxury</option>
                                <option value="W">W Luxury Elite</option>
                                <option value="O">O Oversize</option>
                                <option value="X">X Special</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <label for="acriss_type">Type</label>
                            <select class="form-control acriss_select" id="acriss_type" size="17">
                                <option value="B">B 2-3 Door</option>
                                <option value="C">C 2/4 Door</option>
                                <option value="D">D 4-5 Door</option>
                                <option value="W">W Wagon/Estate</option>
                                <option value="V">V Passenger van</option>
                                <option value="L">L Limousine</option>
                                <option value="S">S Sport</option>
                                <option value="T">T Convertible</option>
                                <option value="F">F SUV</option>
                                <option value="J">J Open-air All Terrain</option>
                                <option value="X">X Special</option>
                                <option value="P">P Pickup (regular cab)</option>
                                <option value="Q">Q Pickup (extended cab)</option>
                                <option value="Z">Z Special offer Car</option>
                                <option value="E">E Coupe</option>
                                <option value="M">M Monospace</option>
                                <option value="R">R Recreational vehicle</option>
                                <option value="H">H Motorhome</option>
                                <option value="Y">Y 2-wheel vehicle</option>
                                <option value="N">N Roadster</option>
                                <option value="G">G Crossover</option>
                                <option value="K">K Commercial van/truck</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <label for="acriss_type">Transmission</label>
                            <select class="form-control acriss_select" id="acriss_transmission" size="17">
                                <option value="M">M Manual Unspecified</option>
                                <option value="N">N Manual 4-wheel</option>
                                <option value="C">C Manual All-wheel</option>
                                <option value="A">A Automatic Unspecified</option>
                                <option value="B">B Automatic 4-wheel</option>
                                <option value="D">D Automatic All-wheel</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <label for="acriss_fuel_air">Fuel/Air conditioning</label>
                            <select class="form-control acriss_select" id="acriss_fuel_air" size="17">
                                <option value="R">R Unspecified / Yes</option>
                                <option value="N">N Unspecified / No</option>
                                <option value="D">D Diesel / Yes</option>
                                <option value="Q">Q Diesel / No</option>
                                <option value="H">H Hybrid / Yes</option>
                                <option value="I">I Hybrid / No</option>
                                <option value="E">E Electric / Yes</option>
                                <option value="C">C Electric / No</option>
                                <option value="L">L LPG / Yes</option>
                                <option value="S">S LPG / No</option>
                                <option value="A">A Hydrogen / Yes</option>
                                <option value="B">B Hydrogen / No</option>
                                <option value="M">M Multifuel / Yes</option>
                                <option value="F">F Multifuel / No</option>
                                <option value="V">V Petrol / Yes</option>
                                <option value="Z">Z Petrol / No</option>
                                <option value="U">U Ethanol / Yes</option>
                                <option value="X">X Ethanol / No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <p>{{__('Για περισσότερες πληροφορίες σχετικά με τους κωδικούς SIP παρακαλώ επισκευτήτε το')}} <a href="https://www.acriss.org/car-codes/expanded-matrix/" target="_blank">ACRISS</a></p>
                <button id="acriss_submit" type="button" class="btn btn-success" disabled data-dismiss="modal">{{__('Προσθήκη')}}</button>
            </div>

        </div>
    </div>
</div>

