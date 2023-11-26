import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IVehicleStatusCollection } from './vehicle-status-collection.interface';
import { IVehicleStatus } from './vehicle-status.interface';

@Injectable({
  providedIn: 'root'
})
export class VehicleStatusService<T extends IVehicleStatus> extends ApiService<T> {
  url = `${env.apiUrl}/status`;

  total_V_StatusSub: BehaviorSubject<IVehicleStatusCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
