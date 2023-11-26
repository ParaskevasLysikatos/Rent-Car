import { Component, HostListener, Injector, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { take } from 'rxjs/internal/operators/take';
import { Subscription } from 'rxjs/internal/Subscription';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { CompanyPreferencesService } from 'src/app/company_preferences/company.service';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { ITypes } from 'src/app/types/types.interface';
import { TypesService } from 'src/app/types/types.service';
import { IVehicle } from 'src/app/vehicle/vehicle.interface';
import { VehicleService } from 'src/app/vehicle/vehicle.service';
import { GetParams } from 'src/app/_interfaces/get-params.interface';
import { AbstractAutocompleteSelectorComponent } from 'src/app/_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { AuthService } from 'src/app/_services/auth.service';
import { IVehicleExchanges } from '../vehicle-exchanges.interface';
import { VehicleExchangesService } from '../vehicle-exchanges.service';

@Component({
  selector: 'app-vehicle-exchanges-form',
  templateUrl: './vehicle-exchanges-form.component.html',
  styleUrls: ['./vehicle-exchanges-form.component.scss']
})
export class VehicleExchangesFormComponent extends AbstractFormComponent implements OnInit, OnDestroy {
  form = this.fb.group({
    id: [],
    driver_id: [,Validators.required],//ok

    new_vehicle_type_id: [],
    new_vehicle_id: [,Validators.required],//ok
    new_vehicle_transition_id: [],
    new_vehicle_rental_co_km: [,Validators.required],//ok
    new_vehicle_rental_co_fuel_level: [],

    old_vehicle_id: [,Validators.required],//ok
    old_vehicle_transition_id: [],
    old_vehicle_rental_co_km: [,Validators.required],//ok
    old_vehicle_rental_co_fuel_level: [],
    old_vehicle_rental_ci_km: [,Validators.required],//ok
    old_vehicle_rental_ci_fuel_level: [,Validators.required],//ok

    proximate_datetime: [],
    station_id: [,Validators.required],//ok
    rental_id: [],
    datetime: [, Validators.required],//ok
    place_id: [],
    place_text: [],
    reason: [],
    status: [],
    type: [],// office or outside

    place:[,Validators.required],//ok

    old_vehicle_source: [],
    new_vehicle_source: [],

    documents: [],
  });

  vehicleExchangeAllData: IVehicleExchanges;
  vehicleDataCreateMode: boolean=false;

  rental_id!:string
  allowReadOnly: boolean= true;

  new_traveled_km:number=0;
  old_traveled_km:number=0;

  selectedIndex!: number ; //The index of the active tab.

  old_vehicle_Create: string='';// on create mode show car make-model

  afterDLbool: boolean = false;

 checkout_datetimeGet: string='';//vehicle selector received from create mode
checkin_datetimeGet: string='';//vehicle selector received from create mode

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('datetime', { static: false }) datetime: DatetimepickerComponent;

  constructor(protected injector: Injector, public comPrefSrv: CompanyPreferencesService,
    public authSrv: AuthService, protected selectorSrv: SelectorService,
    protected typesSrv:TypesService<ITypes>,public vehicleSrv:VehicleService<IVehicle>
    , public vehicleExchangeSrv: VehicleExchangesService<IVehicleExchanges>, public stationSrv: StationService<IStation>)
  {
    super(injector);
  }

  ngOnInit() {
    this.selectorSrv.searchDriver.next(null);
    this.selectorSrv.searchStation.next(null);
    this.selectorSrv.searchGroup.next(null);
    this.selectorSrv.searchVehicle.next(null);
    super.ngOnInit();
  }

  ngAfterViewInit(): void {
    this.typesSrv.get({}, undefined, -1).subscribe((res) => { // fill the group selector with options
      this.group_Ref.selector.data = res.data;
    });

    this.stationSrv.get({}, undefined, -1).subscribe(res => {
      this.station_id_Ref.selector.data = res.data;
    });


    this.form.controls.old_vehicle_rental_ci_km.valueChanges.subscribe(() => {
      console.log('a');
      this.form.controls.old_vehicle_rental_ci_km.addValidators(Validators.min(this.form.controls.old_vehicle_rental_co_km.value));
      this.form.controls.old_vehicle_rental_ci_km.updateValueAndValidity({ onlySelf: true, emitEvent: false });
    });


  }

  ngOnDestroy(){
    this.selectorSrv.searchDriver.next(null);
    this.selectorSrv.searchStation.next(null);
    this.selectorSrv.searchGroup.next(null);
    this.selectorSrv.searchVehicle.next(null);
  }

  ngAfterViewChecked() {
   this.vehicleExchangeSrv.afterDataLoadSubject.subscribe(res => {
      if (res == true) {//after data load from edit will activate checks
        this.vehicleExchangeSrv.afterDataLoadSubject.next(null);
        this.afterDLbool = true;
      }
    });

    if (this.afterDLbool) {//after data load start checks

      // sychronize group selector
      if (!this.group_Ref.selector.selectControl.value) {
        this.includeGroupPlates = [];
        this.hasGroup = false;
      }
      else {
        this.hasGroup = true;
        if (this.new_vehicle_Create) {
          this.includeGroupPlates = [this.new_vehicle_Create?.type_id];
        }
      }

      if (this.selectedIndex == 0) {
        this.form.controls.type.patchValue('office');
      }
      else {
        this.form.controls.type.patchValue('outside');
      }

    }

  }



  //---------station-out auto change place---------------//

  station_Data: any;
  station_id: string;
  includeOutPlaces = [];
  @ViewChild('place', { static: true }) place_id_Ref: AbstractAutocompleteSelectorComponent<any>;


  stationEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.station_Data = res;
      this.station_id = res?.id;
      this.includeOutPlaces = []//clear
      this.place_id_Ref.selector.options = [];
      res.places.forEach((item) => {
        this.includeOutPlaces.push(item.id);
        this.place_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includeOutPlaces);
      this.form.controls.place.patchValue({ //choose first filtered place
        id: this.station_Data?.places[0]?.id,
        name: this.station_Data?.places[0]?.profiles?.el?.title
      });
    });
  }


  //-----------group event to filter licence plates------//

  type_id!: string;
  @ViewChild('group', { static: true }) group_Ref: AbstractSelectorComponent<any>;
  includeGroupPlates = [];
  groupParams: GetParams = { filters: [] };

  groupEvent() {
    this.selectorSrv.searchGroup.subscribe(res => {
      this.type_id = res?.id;
      this.form.controls.new_vehicle_type_id.patchValue(res?.id);
      console.log(res?.id + ' gr  ' + this.new_vehicle_Create?.type_id);
      if (res?.id != this.new_vehicle_Create?.type_id) {//check current vehicle is of this group, otherwise nullify

       this.vehicle_Ref.selector.selectControl.patchValue(null);
      this.vehicle_Ref.selector.data = [];
        this.vehicle_Ref2.selector.selectControl.patchValue(null);
        this.vehicle_Ref2.selector.data = [];

        this.new_vehicle_Create = null;
        this.new_vehicleDesc = '';
        this.selectorSrv.searchVehicle.next(null);

        this.form.controls.new_vehicle_rental_co_km.patchValue(null);
        this.form.controls.new_vehicle_rental_co_fuel_level.patchValue(null);
      }

      this.includeGroupPlates = [];//clear;
      this.includeGroupPlates.push(res?.id);
      console.log(this.includeGroupPlates);//due to large array console not showing instantly correct

      this.groupParams.filters['type_id'] = [];//first ini specific filter val
      this.groupParams.filters['vehicle_status'] = [];//first ini specific filter val
      this.groupParams.filters['status2'] = [];//first ini specific filter val
      //vehicle filters
      this.groupParams.filters['type_id'].push(res?.id);
      this.groupParams.filters['status2'].push('available');
      this.groupParams.filters['vehicle_status'].push('active');
      //fill the vehicle with options
      console.log(this.groupParams.filters);
      this.vehicleSrv.get(this.groupParams.filters, undefined, -1)
      .subscribe(res => { this.vehicle_Ref.selector.data = res.data;
        this.vehicle_Ref2.selector.data = res.data; this.groupParams.filters = { '': null }; });
    });
  }

  //-----vehicle event that auto changes (optional the group-excess), certain the model-make-fuel-km---//
  vehicle_id: string;
  //vehicleData: IVehicle;
  new_vehicle_Create:IVehicle;// on create mode show car make-model
  new_vehicleDesc: string = '';
  @ViewChild('vehicle', { static: true }) vehicle_Ref: AbstractSelectorComponent<any>;
  @ViewChild('vehicle2', { static: true }) vehicle_Ref2: AbstractSelectorComponent<any>;
  hasGroup: boolean;

  vehicleEvent() {
    if (this.hasGroup) { //has group active
      this.selectorSrv.searchVehicle.subscribe(res => {
        this.vehicle_id = res?.id;
        this.form.controls.new_vehicle_id.patchValue(res?.id);
        this.new_vehicle_Create = res;//model-make
        this.new_vehicleDesc = this.new_vehicle_Create.make + ' ' + this.new_vehicle_Create.model;
        this.form.controls.new_vehicle_rental_co_km.patchValue(res?.km);//km
        this.form.controls.new_vehicle_rental_co_fuel_level.patchValue(res?.fuel_level);//fuel
      });
    } else {// has not group
      this.selectorSrv.searchVehicle.subscribe(res => {
        this.vehicle_id = res?.id;
        this.form.controls.new_vehicle_id.patchValue(res?.id);
        this.new_vehicle_Create = res;//model-make
        this.new_vehicleDesc = this.new_vehicle_Create.make + ' ' + this.new_vehicle_Create.model;

        this.form.controls.new_vehicle_rental_co_km.patchValue(res?.km);//km
        this.form.controls.new_vehicle_rental_co_fuel_level.patchValue(res?.fuel_level);//fuel

        this.form.controls.new_vehicle_type_id.patchValue(res?.type_id);//group-selector
        this.type_id = res?.type_id;// to not trigger group event

        this.includeGroupPlates = [];//clear;
        this.includeGroupPlates.push(res?.type_id);
        this.vehicle_Ref.selector.data = [];// clear after selected vehicle options after without group
        this.vehicle_Ref2.selector.data = [];// clear after selected vehicle options after without group
      });
    }
  }

  //----host listener for all auto changes------//

  @HostListener('document:input', ['$event'])
  EventInput(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some input s');
      this.stationEvent();
    }
    else if (this.type_id != this.form.controls.new_vehicle_type_id.value) {
      console.log('some input t');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.groupEvent();
    }
    else if (this.vehicle_id != this.form.controls.new_vehicle_id.value) {
      console.log('some input v');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.vehicleEvent();
    }


  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some click s');
      this.stationEvent();
    }
    else if (this.type_id != this.form.controls.new_vehicle_type_id.value) {
      console.log('some click t');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.groupEvent();
    }
    else if (this.vehicle_id != this.form.controls.new_vehicle_id.value) {
      console.log('some click v');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.vehicleEvent();
    }

  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some change s');
      this.stationEvent();
    }
    else if (this.type_id != this.form.controls.new_vehicle_type_id.value) {
      console.log('some change t');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.groupEvent();
    }
    else if (this.vehicle_id != this.form.controls.new_vehicle_id.value) {
      console.log('some change v');
      // console.log(this.driver_id);
      //  console.log(this.form.controls.driver_id.value);
      this.vehicleEvent();
    }

  }






}
