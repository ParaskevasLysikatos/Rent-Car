import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { DatetimepickerComponent } from 'src/app/datetimepicker/datetimepicker.component';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IVehicleExchangesCollection } from '../vehicle-exchanges-collection.interface';
import { VehicleExchangesService } from '../vehicle-exchanges.service';

@Component({
  selector: 'app-preview-vehicle-exchanges',
  templateUrl: './preview-vehicle-exchanges.component.html',
  styleUrls: ['./preview-vehicle-exchanges.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleExchangesService}]
})
export class PreviewVehicleExchangesComponent extends PreviewComponent<IVehicleExchangesCollection> {
  displayedColumns = ['old_vehicle','old_km','new_vehicle','new_km','proximate','date','rental','actions'];
  @ViewChild('licence_plates_old_filter', { static: true }) licence_plates_old: TemplateRef<any>;
  @ViewChild('licence_plates_new_filter', { static: true }) licence_plates_new: TemplateRef<any>;
  @ViewChild('date_filter', { static: true }) date: TemplateRef<any>;
  @ViewChild('rental_filter', { static: true }) rental: TemplateRef<any>;

  @ViewChild('picker1', { static: false }) picker1: DatetimepickerComponent;

  constructor(protected injector: Injector,public V_ExchangeSrv:VehicleExchangesService<IVehicleExchangesCollection>) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'old_vehicle',
        header: 'Όχημα που αντικαταστάθηκε',
        cell: (element: IVehicleExchangesCollection) => `${element.old_vehicle_source?.licence_plates?.[0].licence_plate}`,
        hasFilter: true,
        filterTemplate: this.licence_plates_old,
      },
      {
        columnDef: 'old_km',
        header: 'Διανυθέντα Χιλιόμετρα',
        cell: (element: IVehicleExchangesCollection) => `${element.old_vehicle_km_diff}`
      },
      {
        columnDef: 'new_vehicle',
        header: 'Νέο όχημα',
        cell: (element: IVehicleExchangesCollection) => `${element.new_vehicle_source?.licence_plates?.[0].licence_plate}`,
        hasFilter: true,
        filterTemplate: this.licence_plates_new,
      },
      {
        columnDef: 'new_km',
        header: 'Διανυθέντα Χιλιόμετρα',
        cell: (element: IVehicleExchangesCollection) => `${element.new_vehicle_km_diff}`
      },
      {
        columnDef: 'proximate',
        header: 'Ραντεβού',
        cell: (element: IVehicleExchangesCollection) => `${element.proximate_datetime ?? '-'}`
      },
      {
        columnDef: 'date',
        header: 'Ημ. Αντικατάστασης',
        cell: (element: IVehicleExchangesCollection) => `${element.datetime}`,
        hasFilter: true,
        filterTemplate: this.date,
        filterField:'datetime',
      },
      {
        columnDef: 'rental',
        header: 'RNT',
        cell: (element: IVehicleExchangesCollection) => `${element?.rental?.sequence_number ?? '-'}`,
        hasFilter: true,
        filterTemplate: this.rental,
        filterField:'rental_id'
      },

    ];
  }

  clearDates1() {
    this.picker1.datepickerControl.patchValue(null);
    this.picker1.timepickerControl.patchValue(null);
  }
}
