import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleFormComponent } from '../vehicle-form/vehicle-form.component';
import { IVehicle } from '../vehicle.interface';
import { VehicleService } from '../vehicle.service';

@Component({
  selector: 'app-create-vehicle',
  templateUrl: './create-vehicle.component.html',
  styleUrls: ['./create-vehicle.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleService}]
})
export class CreateVehicleComponent extends CreateFormComponent<IVehicle> {
  @ViewChild(VehicleFormComponent, {static: true}) formComponent!: VehicleFormComponent;
  currentDate = new Date();

  ngOnInit(): void {
  super.ngOnInit();
  //this.formComponent.form.controls['first_licence_plate_date'].setValue(this.currentDate);
}



}
