import { MultilingualFormComponent } from 'src/app/multilingual-form/multilingual-form.component';
import { Component, HostListener, Injector, OnInit, ViewChild } from '@angular/core';
import { Validators } from '@angular/forms';
import { filter } from 'rxjs';
import { AbstractFormComponent } from 'src/app/abstract-form/abstract-form.component';
import { IClassType } from 'src/app/class-type/class-type.interface';
import { ClassTypeService } from 'src/app/class-type/class-type.service';
import { IColorType } from 'src/app/color-type/color-type.interface';
import { ColorTypeService } from 'src/app/color-type/color-type.service';
import { IDriveType } from 'src/app/drive-type/drive-type.interface';
import { DriveTypeService } from 'src/app/drive-type/drive-type.service';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { IFuelType } from 'src/app/fuel-type/fuel-type.interface';
import { FuelTypeService } from 'src/app/fuel-type/fuel-type.service';
import { CreateLicencePlateComponent } from 'src/app/licence-plate/create-licence-plate/create-licence-plate.component';
import { EditLicencePlateComponent } from 'src/app/licence-plate/edit-licence-plate/edit-licence-plate.component';
import { ILicencePlate } from 'src/app/licence-plate/licence-plate.interface';
import { LicencePlateService } from 'src/app/licence-plate/licence-plate.service';
import { IOwnershipType } from 'src/app/ownership-type/ownership-type.interface';
import { OwnershipTypeService} from 'src/app/ownership-type/ownership-type.service';
import { IPeriodicFeeTypes } from 'src/app/periodic-fee-types/periodic-fee-types.interface';
import { PeriodicFeeTypesService } from 'src/app/periodic-fee-types/periodic-fee-types.service';
import { CreatePeriodicFeeComponent } from 'src/app/periodic-fee/create-periodic-fee/create-periodic-fee.component';
import { EditPeriodicFeeComponent } from 'src/app/periodic-fee/edit-periodic-fee/edit-periodic-fee.component';
import { IPeriodicFee } from 'src/app/periodic-fee/periodic-fee.interface';
import { ITransmissionType } from 'src/app/transmission-type/transmission-type.interface';
import { TransmissionTypeService } from 'src/app/transmission-type/transmission-type.service';
import { ITypesCollection } from 'src/app/types/types-collection.interface';
import { TypesService } from 'src/app/types/types.service';
import { IUseType } from 'src/app/use-type/use-type.interface';
import { UseTypeService } from 'src/app/use-type/use-type.service';
import { IVehicleStatus } from 'src/app/vehicle-status/vehicle-status.interface';
import { VehicleStatusService } from 'src/app/vehicle-status/vehicle-status.service';
import { IVehicleCollection } from '../vehicle-collection.interface';
import { VehicleService } from '../vehicle.service';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { SelectorService } from 'src/app/_selectors/selector/selector.service';
import { AbstractAutocompleteSelectorComponent } from 'src/app/_selectors/abstract-autocomplete-selector/abstract-autocomplete-selector.component';
import { CombinedService } from 'src/app/home/combined.service';
import { ICombined } from 'src/app/home/combined.interface';


@Component({
  selector: 'app-vehicle-form',
  templateUrl: './vehicle-form.component.html',
  styleUrls: ['./vehicle-form.component.scss']
})
export class VehicleFormComponent extends MultilingualFormComponent implements OnInit {
  form = this.fb.group({
    id: [],
    model: [,Validators.required],
    make:[,Validators.required],
    km: [],
    place: [],
    place_text: [],
    vin: [,Validators.required],
    status: [],
    status_id: [],
    station_id: [],
    vehicle_status: [],
    type_id: [, Validators.required],
    images:[],
    engine:[,Validators.required],
    power:[],
    hp:[],
    drive_type:[],
    drive_type_id:[,Validators.required],
    transmission:[],
    transmission_type_id:[,Validators.required],
    key_code:[],
    keys_quantity:[],
    doors: [],
    seats: [],
    euroclass:[],

    fuel_type_id:[],
    color_type_id:[],
    ownership_type_id:[],
    class_type_id:[],
    use_type_id:[],

    purchase_date:[],
    warranty_expiration:[],

    engine_number:[],
    tank:[],
    pollution:[],
    radio_code:[],
    purchase_amount:[],
    depreciation_rate:[],//aposvesi
    depreciation_rate_year:[],
    sale_amount:[],
    sale_date:[],
    start_stop:[],//system of battery
    buy_back:[],
    first_date_marketing_authorisation:[],
    first_date_marketing_authorisation_gr:[],
    import_to_system:[],
    export_from_system:[],
    forecast_export_from_system:[],
    manufactured_year:[],
    periodic_fees:[],

    licence_plates: [],

    //create
    first_licence_plate:[],
    first_licence_plate_date:[],

    documents:[],


  });

  licence_plates:ILicencePlate[] = [];
  vehicle_status:any=[];
  types:any=[];
  fuel_types:any=[];
  color_types:any=[];
  ownership_types:any=[];
  use_types:any=[];
  class_types:any=[];
  periodic_fees:any[]=[];
  periodic_types:any=[];
  transmission_type:any=[];
  drive_types:any=[];
  vehicle_statusArray:any=[];

  // http
  messageSuccess!: string;
  messageError!: string;

  //upload

  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, protected modalsrv:FormDialogService, protected vehicleSrv: VehicleService<IVehicleCollection>,
  protected typeSrv:TypesService<ITypesCollection>,protected fuelSrv:FuelTypeService<IFuelType>,protected colorSrv:ColorTypeService<IColorType>,
  protected ownershipSrv:OwnershipTypeService<IOwnershipType>,protected useSrv:UseTypeService<IUseType>,protected classSrv:ClassTypeService<IClassType>,
  protected periodicFeeTypeSrv:PeriodicFeeTypesService<IPeriodicFeeTypes>,protected transmissionSrv:TransmissionTypeService<ITransmissionType>,
  protected driveTypeSrv:DriveTypeService<IDriveType>,protected vehicleStatusSrv:VehicleStatusService<IVehicleStatus>,
    protected licenceSrv: LicencePlateService<ILicencePlate>, public stationSrv: StationService<IStation>, public selectorSrv: SelectorService,public combinedSrv: CombinedService)
  {
    super(injector);
  }


  ngOnInit(): void {
    super.ngOnInit();

    this.combinedSrv.getVehicles().subscribe(res => {
      this.types = res.groups;
      this.fuel_types = res.fuel;
      this.color_types = res.color_type;
      this.ownership_types = res.ownership;
      this.use_types = res.use;
      this.class_types = res.class;
      this.periodic_types = res.periodicFee_types;
      this.transmission_type = res.transmission;
      this.drive_types = res.drive_type;
      this.vehicle_statusArray = res.vehicleStatus;
      this.station_id_Ref.selector.data = res.stations;
    });
    //this.typeSrv.get({}, undefined, -1).subscribe(res => { this.types = res.data });
   // this.fuelSrv.get({}, undefined, -1).subscribe(res=>{this.fuel_types=res.data});
   //this.colorSrv.get({}, undefined, -1).subscribe(res=>{this.color_types=res.data});
   // this.ownershipSrv.get({}, undefined, -1).subscribe(res=>{this.ownership_types=res.data});
   // this.useSrv.get({}, undefined, -1).subscribe(res => { this.use_types = res.data });
   // this.classSrv.get({}, undefined, -1).subscribe(res=>{this.class_types=res.data});
   // this.periodicFeeTypeSrv.get({}, undefined, -1).subscribe(res=>{this.periodic_types=res.data});
   // this.transmissionSrv.get({}, undefined, -1).subscribe(res=>{this.transmission_type=res.data});
   // this.driveTypeSrv.get({}, undefined, -1).subscribe(res=>{this.drive_types=res.data});
   // this.vehicleStatusSrv.get({}, undefined, -1).subscribe(res=>{this.vehicle_statusArray=res.data});

    // this.stationSrv.get({}, undefined, -1).subscribe(res => {
    //   this.station_id_Ref.selector.data = res.data;
    // });
    this.spinnerSrv.hideSpinner();
  }


  editPlate(li: ILicencePlate){
    this.modalsrv.showDialog(EditLicencePlateComponent, {object: li}, false).pipe(filter(licencePlate => licencePlate))
      .subscribe((licencePlate: ILicencePlate) => {
        const index = this.licence_plates.findIndex((searchLicencePlate) =>  searchLicencePlate.id == li.id);
        this.licence_plates[index] = licencePlate;
    });
  }

  removePlate(index: number) {
    this.licence_plates.splice(index, 1);
  }

  addPlate() {
    this.modalsrv.showDialog(CreateLicencePlateComponent, {}, false).pipe(filter(licencePlate => licencePlate))
      .subscribe((licencePlate: ILicencePlate) => {
        this.licence_plates.push(licencePlate);
    });
  }

  // submitLicencePlates(){
  //   let licence = this.form.controls.licence_plates.value;
  //   console.log(licence[0].licence_plate);
  //   this.licenceSrv.update(licence[0].id, licence).subscribe((licencePlate: ILicencePlate) => { licencePlate;this.messageSuccess='success'},(error:any)=>{this.messageError=error});
  // }

  editPeriodic(p: IPeriodicFee) {//pipe handle to no send subscribe
    this.modalsrv.showDialog(EditPeriodicFeeComponent, { object: p }, false).pipe(filter(periodic => periodic))
      .subscribe((periodic: IPeriodicFee) => {
        console.log(periodic);
        const index = this.periodic_fees.findIndex((searchPeriodic) => searchPeriodic.id == p.id);
        this.periodic_fees[index] = periodic;
      });
  }

  removePeriodic(index: number) {
    this.periodic_fees.splice(index, 1);
  }

  addPeriodic() {
    this.modalsrv.showDialog(CreatePeriodicFeeComponent, {}, false).pipe(filter(periodic => periodic))
      .subscribe((periodic: IPeriodicFee) => {
        console.log(periodic);
        this.periodic_fees.push(periodic);
      });
  }

  get periodic_fees_types() {

    return [...new Map(this.periodic_fees.map(item => [item.fee_type.id,item.fee_type])).values()];
}


  //---------station auto change place---------------//

  station_Data: any;
  station_id: string;
  includePlaces = [];
  @ViewChild('place', { static: true }) place_id_Ref: AbstractAutocompleteSelectorComponent<any>;

  stationEvent() {
    this.selectorSrv.searchStation.subscribe(res => {
      this.station_Data = res;
      this.station_id = res?.id;
      this.includePlaces = []//clear
      this.place_id_Ref.selector.options = [];
      res?.places.forEach((item) => {
        this.includePlaces.push(item.id);
        this.place_id_Ref.selector.options.push(item);
        //filter places
      });
      //console.log(res.places);
      console.log(this.includePlaces);
      this.form.controls.place.patchValue({ //choose first filtered place
        id: this.station_Data?.places[0]?.id,
        name: this.station_Data?.places[0]?.profiles?.el?.title
      });
    });
  }

  @HostListener('document:click', ['$event'])
  EventClick(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some click s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
    }
  }

  @HostListener('document:change', ['$event'])
  EventChange(event: Event) {
    if (this.station_id != this.form.controls.station_id.value) {
      console.log('some change s');
      //  console.log(this.checkout_station_id);
      //  console.log(this.form.controls.checkout_station_id.value + ' new' + this.checkout_station_id);
      this.stationEvent();
      this.form.markAsDirty();
      this.form.markAllAsTouched();
    }
  }
  //------//


}
