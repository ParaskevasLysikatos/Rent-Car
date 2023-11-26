import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleFormComponent } from '../vehicle-form/vehicle-form.component';
import { IVehicle } from '../vehicle.interface';
import { VehicleService } from '../vehicle.service';

@Component({
  selector: 'app-edit-vehicle',
  templateUrl: './edit-vehicle.component.html',
  styleUrls: ['./edit-vehicle.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleService}]
})
export class EditVehicleComponent extends EditFormComponent<IVehicle> {
  @ViewChild(VehicleFormComponent, {static: true}) formComponent!: VehicleFormComponent;
  submitted = (res) => {
   // console.log(res);
  }

  afterDataLoad(res:IVehicle) {
    this.formComponent.licence_plates=res.licence_plates;
    this.formComponent.periodic_fees=res.periodic_fees;
    if(res.vehicle_status!=null){
    this.formComponent.vehicle_status=res.vehicle_status.slug;
    }
 }

}
