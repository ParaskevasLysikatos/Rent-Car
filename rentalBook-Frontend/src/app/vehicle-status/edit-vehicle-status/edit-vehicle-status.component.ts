import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { VehicleStatusFormComponent } from '../vehicle-status-form/vehicle-status-form.component';
import { IVehicleStatus } from '../vehicle-status.interface';
import { VehicleStatusService } from '../vehicle-status.service';

@Component({
  selector: 'app-edit-vehicle-status',
  templateUrl: './edit-vehicle-status.component.html',
  styleUrls: ['./edit-vehicle-status.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleStatusService}]
})
export class EditVehicleStatusComponent extends EditFormComponent<IVehicleStatus> {
  @ViewChild(VehicleStatusFormComponent, {static: true}) formComponent!: VehicleStatusFormComponent;
}
