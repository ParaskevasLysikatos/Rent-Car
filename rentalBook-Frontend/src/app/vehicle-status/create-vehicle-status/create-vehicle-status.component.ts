import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleStatusFormComponent } from '../vehicle-status-form/vehicle-status-form.component';
import { IVehicleStatus } from '../vehicle-status.interface';
import { VehicleStatusService } from '../vehicle-status.service';

@Component({
  selector: 'app-create-vehicle-status',
  templateUrl: './create-vehicle-status.component.html',
  styleUrls: ['./create-vehicle-status.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleStatusService}]
})
export class CreateVehicleStatusComponent extends CreateFormComponent<IVehicleStatus> {
  @ViewChild(VehicleStatusFormComponent, {static: true}) formComponent!: VehicleStatusFormComponent;
}
