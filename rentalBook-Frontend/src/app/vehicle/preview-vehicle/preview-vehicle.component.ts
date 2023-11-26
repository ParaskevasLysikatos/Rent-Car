import { Component, ElementRef, Injector, TemplateRef, ViewChild } from '@angular/core';
import { MatInput } from '@angular/material/input';
import { MatSelect } from '@angular/material/select';
import moment from 'moment';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import { CombinedService } from 'src/app/home/combined.service';
import { IOwnershipType } from 'src/app/ownership-type/ownership-type.interface';
import { OwnershipTypeService } from 'src/app/ownership-type/ownership-type.service';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { ITypes } from 'src/app/types/types.interface';
import { TypesService } from 'src/app/types/types.service';
import { IVehicleStatus } from 'src/app/vehicle-status/vehicle-status.interface';
import { VehicleStatusService } from 'src/app/vehicle-status/vehicle-status.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IVehicleCollection } from '../vehicle-collection.interface';
import { VehicleService } from '../vehicle.service';

@Component({
  selector: 'app-preview-vehicle',
  templateUrl: './preview-vehicle.component.html',
  styleUrls: ['./preview-vehicle.component.scss'],
  providers: [{ provide: ApiService, useClass: VehicleService }]
})
export class PreviewVehicleComponent extends PreviewComponent<IVehicleCollection> {
  @ViewChild('hex_code', {static: true}) hexCode: TemplateRef<any>;
  @ViewChild('status', { static: true }) status: TemplateRef<any>;
  @ViewChild('station_filter', {static: true}) stationFilter: TemplateRef<any>;
  @ViewChild('status_filter', { static: true }) statusFilter: TemplateRef<any>;
  @ViewChild('vehicle_status_filter', { static: true }) vehicle_statusFilter: TemplateRef<any>;
  @ViewChild('ownership_filter', { static: true }) ownershipFilter: TemplateRef<any>;
  @ViewChild('group_filter', { static: true }) groupFilter: TemplateRef<any>;

  @ViewChild('import_to_system_filter', { static: true }) import_to_systemFilter: TemplateRef<any>;
  @ViewChild('purchase_date_filter', { static: true }) purchase_dateFilter: TemplateRef<any>;

  @ViewChild('availability_filter', { static: true }) availabilityFilter: TemplateRef<any>;
  displayedColumns = ['licence_plates', 'model', 'hex_code', 'km','fuel','type','station', 'place_text',
    'status', 'vehicle_status', 'insurance', 'KTEO', 'import_to_system','purchase_date','actions'];

  vehicle_statusArray: any=[];
  ownership_types:any=[];
  @ViewChild('chargeType', { static: false }) charge_type_Ref: AbstractSelectorComponent<any>;
  @ViewChild('station', { static: false }) station_id_Ref: AbstractSelectorComponent<any>;

  //clear dates
  @ViewChild('input2', { read: MatInput }) input2: MatInput;
  @ViewChild('input3', { read: MatInput }) input3: MatInput;

  @ViewChild('matRef') matRef: MatSelect;
  @ViewChild('matRef2') matRef2: MatSelect;
  @ViewChild('matRef3') matRef3: MatSelect;
  @ViewChild('picker1', { static: false }) picker1: DatetimepickerComponent;

  constructor(protected injector: Injector, private vehicleStatusSrv: VehicleStatusService<IVehicleStatus>,public vehicleSrv: VehicleService<IVehicleCollection>,
    private ownershipSrv: OwnershipTypeService<IOwnershipType>, public typesSrv: TypesService<ITypes>, public stationSrv: StationService<IStation>, public combinedSrv: CombinedService) {
    super(injector);
  //  this.vehicleStatusSrv.get({}, undefined, -1).subscribe(res => { this.vehicle_statusArray = res.data });
  //  this.ownershipSrv.get({}, undefined, -1).subscribe(res => { this.ownership_types = res.data });
  }

  ngOnInit() {
    super.ngOnInit();
    let currentDate = moment().format('YYYY-MM-DD HH:mm');
    setTimeout(() => this.matRef.options.first.select(), 2000);//slow on initialize needs timeout
    setTimeout(() => { this.picker1.datepickerControl.patchValue(currentDate);
      this.picker1.timepickerControl.patchValue(moment().format('HH:mm'));
    }, 2000);//slow on initialize needs timeout


    this.combinedSrv.getVehicles().subscribe((res) => {
      this.charge_type_Ref.selector.data = res.groups;
      this.station_id_Ref.selector.data = res.stations;
      this.vehicle_statusArray = res.vehicleStatus;
      this.ownership_types = res.ownership;
    });

    // this.typesSrv.get({}, undefined, -1).subscribe((res) => { // fill the group selector with options
    //   this.charge_type_Ref.selector.data = res.data;
    // });
    // this.stationSrv.get({}, undefined, -1).subscribe(res => {
    //   this.station_id_Ref.selector.data = res.data;
    // });
    this.columns = [
      {
        columnDef: 'licence_plates',
        header: 'Πινακίδα',
        cell: (element: IVehicleCollection) => `${element.licence_plates[0].licence_plate}`,
        hasFilter: true,
        sortBy: 'license_plates.licence_plate',
      },
      {
        columnDef: 'model',
        header: 'Μοντέλο',
        cell: (element: IVehicleCollection) => `${element.make}` + ' ' + `${element.model}`
      },
      {
        columnDef: 'hex_code',
        header: 'Χρώμα',
        cellTemplate: this.hexCode
      },
      {
        columnDef: 'km',
        header: 'Χιλιόμετρα',
        cell: (element: IVehicleCollection) => `${element.km}`
      },
      {
        columnDef: 'fuel',
        header: 'Fuel',
        cell: (element: IVehicleCollection) => `${element.fuel_level+'/8'}`
      },
      {
        columnDef: 'type',
        header: 'Group',
        cell: (element: IVehicleCollection) => `${element.type?.international_title}`,
        hasFilter: true,
        sortBy: 'type.current_profile.title',
        filterTemplate: this.groupFilter,
        filterField: 'type_id2', //v2 there is type id v1
      },
      {
        columnDef: 'station',
        header: 'Σταθμός',
        cell: (element: IVehicleCollection) => `${element.station?.title ?? ''}`,
        sortBy: 'station.current_profile.title',
        hasFilter: true,
        filterField: 'station_id',
        filterTemplate: this.stationFilter
      },
      {
        columnDef: 'place_text',
        header: 'Τοποθεσία',
        cell: (element: IVehicleCollection) => `${element.place?.name ?? ''}`,
        sortBy: 'place_text',
        hasFilter: true
      },
      {
        columnDef: 'insuranceStart', //excel use
        header: 'Έναρξης ασφάλειας',
        cell: (element: IVehicleCollection) => `${element.insurance?.date_start.substring(0, 10) ?? ''}`
      },
      {
        columnDef: 'insurance',
        header: 'Λήξη ασφάλειας',
        cell: (element: IVehicleCollection) => `${element.insurance?.date_expiration.substring(0, 10) ?? ''}`,
        sortBy: 'getInsuranceAttribute.date_expiration'
      },
      {
        columnDef: 'insuranceFee',//excel use
        header: 'Ποσό ασφάλειας',
        cell: (element: IVehicleCollection) => `${element.insurance?.fee ?? ''}`,
      },
      {
        columnDef: 'KTEOStart',//excel use
        header: 'Έναρξης ΚΤΕΟ',
        cell: (element: IVehicleCollection) => `${element.KTEO?.date_start.substring(0, 10) ?? ''}`,
      },
      {
        columnDef: 'KTEO',
        header: 'Λήξη ΚΤΕΟ',
        cell: (element: IVehicleCollection) => `${element.KTEO?.date_expiration.substring(0, 10) ?? ''}`,
        sortBy:'getKteoAttribute.date_expiration'
      },
      {
        columnDef: 'KTEOFee',//excel use
        header: 'Ποσό ΚΤΕΟ',
        cell: (element: IVehicleCollection) => `${element.KTEO?.fee ?? ''}`,
      },
      // {
      //   columnDef: 'vin',
      //   header: 'VIN',
      //   cell: (element: IVehicleCollection) => `${element.vin}`
      // },
      {
        columnDef: 'status',
        header: 'Διαθεσιμότητα',
        //cell: (element: IVehicleCollection) => `${element.status}`,
        cellTemplate: this.status,
        hasFilter: true,
        filterField: 'status2', // v2 there is status in v1
        sortBy: 'status',
        filterTemplate: this.statusFilter
      },
      {
        columnDef: 'availability',
        header: 'Διαθεσιμότητα',
        hasFilter: true,
        filterField: 'availability',
        filterTemplate: this.availabilityFilter
      },
      {
        columnDef: 'vehicle_status',
        header: 'Κατάσταση Οχήματος',
        cell: (element: IVehicleCollection) => `${element.vehicle_status?.profiles?.el?.title === null ? element.vehicle_status?.profiles?.el?.title  : 'Ενεργό'}`,
        hasFilter: true,
        sortBy: 'vehicle_status.slug',
        filterTemplate: this.vehicle_statusFilter
      },
      {
        columnDef: 'ownership',
        header: 'Ιδιοκτησία',
        hasFilter: true,
        filterField: 'ownership_type_id',
        filterTemplate: this.ownershipFilter
      },
      {
        columnDef: 'import_to_system',
        header: ' Hμ/νία Eισαγωγής',
        cell: (element: IVehicleCollection) => `${element.import_to_system ?? ''}`,
        sortBy: 'import_to_system',
        hasFilter: true,
        filterField: 'import_to_system',
        filterTemplate: this.import_to_systemFilter
      },
      {
        columnDef: 'purchase_date',
        header: ' Hμ/νία Hμ/νία Αγοράς',
        cell: (element: IVehicleCollection) => `${element.purchase_date ?? ''}`,
        sortBy: 'purchase_date',
        hasFilter: true,
        filterField: 'purchase_date',
        filterTemplate: this.purchase_dateFilter
      },

    ];
  }

  clearDates1() { this.picker1.datepickerControl.patchValue(null);
             this.picker1.timepickerControl.patchValue(null); }
  clearDates2() { this.input2.value = null; }
  clearDates3() { this.input3.value = null;  }

  clear(){
    this.matRef.value=null;
  }

  clear2() {
    this.matRef2.value = null;
  }

  clear3() {
    this.matRef3.value = null;
  }

  first(v: string) {
    console.log(v);
  }

}
