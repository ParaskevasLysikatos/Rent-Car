import { Component, ViewChild } from '@angular/core';
import moment from 'moment';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleExchangesFormComponent } from '../vehicle-exchanges-form/vehicle-exchanges-form.component';
import { IVehicleExchanges } from '../vehicle-exchanges.interface';
import { VehicleExchangesService } from '../vehicle-exchanges.service';

@Component({
  selector: 'app-edit-vehicle-exchanges',
  templateUrl: './edit-vehicle-exchanges.component.html',
  styleUrls: ['./edit-vehicle-exchanges.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleExchangesService}]
})
export class EditVehicleExchangesComponent extends EditFormComponent<IVehicleExchanges> {
  @ViewChild(VehicleExchangesFormComponent, {static: true}) formComponent!: VehicleExchangesFormComponent;

  afterDataLoad(res: IVehicleExchanges) {
    this.formComponent.vehicleExchangeAllData = res;
    this.formComponent.rental_id=res.rental_id;
    this.formComponent.vehicleExchangeAllData?.type == 'office' ? this.formComponent.selectedIndex = 0 : this.formComponent.selectedIndex = 1;
    this.formComponent.old_traveled_km = Number(res.old_vehicle_km_diff);
    this.formComponent.new_traveled_km = Number(res.new_vehicle_km_diff);

    let date = res.datetime;
    let time: string = String(this.formComponent.datetime.timepickerControl.value);
   // this.formComponent.datetime.timepickerControl.patchValue(moment(date).hour(+String(time).substring(0, 2)).minute(+String(time).substring(3, 6)).format('HH:mm'));
    this.formComponent.form.controls.datetime.patchValue(moment(date).hour(+String(time.substring(0, 2))).minute(+String(time.substring(3, 6))).format('YYYY-MM-DD HH:mm'));
    setTimeout(() => this.formComponent.datetime.timepickerControl.patchValue(moment(date).hour(+String(time.substring(0, 2))).minute(+String(time.substring(3, 6))).format('HH:mm')), 200);

    if (res.new_vehicle_type_id) {// fill vehicle selector
      this.formComponent.groupParams.filters['type_id[]'] = [];//first ini specific filter val
      this.formComponent.groupParams.filters['vehicle_status[]'] = [];//first ini specific filter val
      this.formComponent.groupParams.filters['status[]'] = [];//first ini specific filter val
      //vehicle filters
      this.formComponent.groupParams.filters['type_id[]'].push(res?.new_vehicle_type_id?.id);
      this.formComponent.groupParams.filters['status[]'].push('available');
      this.formComponent.groupParams.filters['vehicle_status[]'].push('active');
      //fill the vehicle with options
      console.log(this.formComponent.groupParams.filters);
      this.formComponent.vehicleSrv.get(this.formComponent.groupParams.filters, undefined, -1)
        .subscribe(res => { this.formComponent.vehicle_Ref.selector.data = res.data; this.formComponent.vehicle_Ref2.selector.data = res.data; this.formComponent.groupParams.filters = { '': null } });
    }

    this.formComponent.vehicleExchangeSrv.afterDataLoadSubject.next(true);

  }
}
