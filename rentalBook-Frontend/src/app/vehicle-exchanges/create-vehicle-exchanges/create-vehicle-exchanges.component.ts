import { Component, Input, ViewChild } from '@angular/core';
import moment from 'moment';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleExchangesFormComponent } from '../vehicle-exchanges-form/vehicle-exchanges-form.component';
import { IVehicleExchanges } from '../vehicle-exchanges.interface';
import { VehicleExchangesService } from '../vehicle-exchanges.service';

@Component({
  selector: 'app-create-vehicle-exchanges',
  templateUrl: './create-vehicle-exchanges.component.html',
  styleUrls: ['./create-vehicle-exchanges.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleExchangesService}]
})
export class CreateVehicleExchangesComponent extends CreateFormComponent<IVehicleExchanges> {
  @ViewChild(VehicleExchangesFormComponent, {static: true}) formComponent!: VehicleExchangesFormComponent;

  currentDate = moment().format('YYYY-MM-DD HH:mm');
  selectedIndex: number = 0; //The index of the active tab.
  @Input() rental_id: string;
  @Input() vehicle_id: string;
  @Input() checkout_km: string;
  @Input() checkout_fuel_level: string;

  @Input() checkout_datetime: string;//vehicle selector
  @Input() checkin_datetime: string;//vehicle selector


  vehicleDataCreateMode: boolean= true;

  @Input() old_vehicle_Create: string;

  ngOnInit() {
    super.ngOnInit();
    this.formComponent.vehicleDataCreateMode = this.vehicleDataCreateMode;

    this.formComponent.rental_id= this.rental_id
    this.formComponent.form.controls.rental_id.patchValue(this.rental_id);

    this.formComponent.form.controls.old_vehicle_id.patchValue(this.vehicle_id);
    this.formComponent.form.controls.old_vehicle_rental_co_km.patchValue(this.checkout_km);
    this.formComponent.form.controls.old_vehicle_rental_co_fuel_level.patchValue(this.checkout_fuel_level);

    this.formComponent.allowReadOnly = false;
    this.formComponent.selectedIndex=this.selectedIndex;
    this.formComponent.form.controls.datetime.patchValue(this.currentDate);
    this.formComponent.comPrefSrv.edit().subscribe(res => {
      this.formComponent.form.controls.station_id.patchValue(res.station_id);
      this.formComponent.station_id=res.station_id;
      this.formComponent.form.controls.place.patchValue(res.place);
    });

    let currentUser = JSON.parse(localStorage.getItem('loggedUser'));
   // this.formComponent.authSrv.user.subscribe(res => {
    this.formComponent.form.controls.driver_id.patchValue(currentUser.driver_id);
   //});

    this.formComponent.old_vehicle_Create = this.old_vehicle_Create;

    this.formComponent.checkin_datetimeGet = this.checkin_datetime;
    this.formComponent.checkout_datetimeGet = this.checkout_datetime;

    this.formComponent.vehicleExchangeSrv.afterDataLoadSubject.next(true);
  }

}
