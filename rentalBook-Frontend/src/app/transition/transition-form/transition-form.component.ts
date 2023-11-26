import { I } from '@angular/cdk/keycodes';
import { Component, HostListener, Injector, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { MatRadioButton } from '@angular/material/radio';
import { ActivatedRoute } from '@angular/router';
import { retry, delay, take, last } from 'rxjs';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { ITransitionType } from 'src/app/transition-type/transition-type.interface';
import { TransitionTypeService } from 'src/app/transition-type/transition-type.service';
import { IUser } from 'src/app/user/user.interface';
import { UserService } from 'src/app/user/user.service';
import { AbstractAutocompleteSelectorComponent } from 'src/app/_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { ITransition } from '../transition.interface';

@Component({
  selector: 'app-transition-form',
  templateUrl: './transition-form.component.html',
  styleUrls: ['./transition-form.component.scss']
})
export class TransitionFormComponent extends AbstractFormComponent implements OnInit, OnDestroy {
  allTransitionData!: ITransition;
  form = this.fb.group({
    id: [],
    type_id :[,Validators.required],
    completed_at :[],
    vehicle_id :[,Validators.required],
    external_party :[],

    co_datetime: [,Validators.required],//
    co_station_id :[,Validators.required],//
    co_place_id :[],
    co_notes :[],
    co_km :[,Validators.required],//
    co_fuel_level :[,Validators.required],//
    co_user_id :[,Validators.required],//
    co_place_text :[],

    ci_datetime :[,Validators.required],//
    ci_station_id :[],
    ci_place_id :[],
    ci_notes :[],
    ci_km :[],
    ci_fuel_level :[],
    ci_user_id :[],
    ci_place_text :[],

    notes: [],
    driver_id: [, Validators.required],
    rental_id :[],

    type: [],

  co_user: [],
  ci_user: [],

    driver: [this.allTransitionData?.driver.role ?? '', Validators.required],

  co_station :[],
  ci_station :[],

  co_place :[],
  ci_place :[],

  vehicle: [],

  s_co_place: [],
  s_ci_place: [],
  distance:[],
  status:[],

  documents: [],
  documentsCount: [],
  });

  transitionTypeData!: ITransitionType[];
  users: IUser[];
  boolDriverRole: boolean;
  checkUrl: boolean = false;
  @ViewChild('stationCI', { static: false }) stationCI_id_Ref: AbstractSelectorComponent<any>;
  @ViewChild('stationCO', { static: false }) stationCO_id_Ref: AbstractSelectorComponent<any>;

  @ViewChild('ci_datetime', { static: false }) datetimeCI: DatetimepickerComponent;
  @ViewChild('co_datetime', { static: false }) datetimeCO: DatetimepickerComponent;

  @ViewChild('employee', { static: false }) empRB: MatRadioButton;
  @ViewChild('customer', { static: false }) custRB: MatRadioButton;


  constructor(protected injector: Injector,protected transTypeSrv:TransitionTypeService<ITransitionType>,
    private route: ActivatedRoute, public stationSrv: StationService<IStation>, public selectorSrv: SelectorService,public userSrv: UserService<IUser>) {
    super(injector);
  }

ngOnInit(): void {

  if (this.route.snapshot.routeConfig?.path == 'create') {
    this.checkUrl = true;
  }

  this.stationSrv.get({}, undefined, -1).subscribe(res => {
    this.stationCI_id_Ref.selector.data = res.data;
    this.stationCO_id_Ref.selector.data = res.data;
  });


  this.transTypeSrv.get({}, undefined, -1).pipe(
    retry(2), // you retry 3 times
    delay(1000) // each retry will start after 1 second,
  ).subscribe((res) => { this.transitionTypeData = res.data });


  if(this.allTransitionData?.driver.role=='customer'){
    this.boolDriverRole = true;
  }
  else{
     this.boolDriverRole = false;
  }

  // this.form.controls.ci_station_id.valueChanges.subscribe(res => {//they act as host listeners
  //   this.stationCIEvent();
  // });

  // this.form.controls.co_station_id.valueChanges.subscribe(res => {//they act as host listeners
  //   this.stationCOEvent();
  // });

}


ngOnDestroy(){
  this.selectorSrv.searchDriver.next(null);
  this.selectorSrv.searchStation.next(null);
  this.selectorSrv.searchGroup.next(null);
  this.selectorSrv.searchVehicle.next(null);
}


DriverRoleE(){
   this.boolDriverRole = true;
}

DriverRoleC(){
   this.boolDriverRole = false;
}


  //---------station auto change place---------------//

  stationCI_Data: any;
  stationCI_id: string;
  includeCIPlaces = [];
  @ViewChild('placeI', { static: true }) placeI_id_Ref: AbstractAutocompleteSelectorComponent<any>;

  stationCIEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.stationCI_Data = res;
      this.stationCI_id = res?.id;
      this.includeCIPlaces = []//clear
      this.placeI_id_Ref.selector.options = [];
      res.places.forEach((item) => {
        this.includeCIPlaces.push(item.id);
        this.placeI_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includeCIPlaces);
      this.form.controls.s_ci_place.patchValue({ //choose first filtered place
        id: this.stationCI_Data?.places[0]?.id,
        name: this.stationCI_Data?.places[0]?.profiles?.el?.title
      });
    });
  }

  stationCO_Data: any;
  stationCO_id: string;
  includeCOPlaces = [];
  @ViewChild('placeO', { static: true }) placeO_id_Ref: AbstractAutocompleteSelectorComponent<any>;

  stationCOEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.stationCO_Data = res;
      this.stationCO_id = res?.id;
      this.includeCOPlaces = []//clear
      this.placeO_id_Ref.selector.options = [];
      res.places.forEach((item) => {
        this.includeCOPlaces.push(item.id);
        this.placeO_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includeCOPlaces);
      this.form.controls.s_co_place.patchValue({ //choose first filtered place
        id: this.stationCO_Data?.places[0]?.id,
        name: this.stationCO_Data?.places[0]?.profiles?.el?.title
      });
    });
  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.stationCI_id != this.form.controls.ci_station_id.value) {
      console.log('some click s i');
      this.stationCIEvent();
    }
    else if (this.stationCO_id != this.form.controls.co_station_id.value) {
      console.log('some click s o');
      this.stationCOEvent();
    }
  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.stationCI_id != this.form.controls.ci_station_id.value) {
      console.log('some change s');
      this.stationCIEvent();
    }
    else if (this.stationCO_id != this.form.controls.co_station_id.value) {
      console.log('some change s o');
      this.stationCOEvent();
    }



  }
  //------//



}
