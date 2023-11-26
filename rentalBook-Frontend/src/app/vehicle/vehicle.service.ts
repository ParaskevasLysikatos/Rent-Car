import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IVehicleCollection } from './vehicle-collection.interface';
import { IVehicle } from './vehicle.interface';

@Injectable({
  providedIn: 'root'
})
export class VehicleService<T extends IVehicle> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles`;

  total_VehicleSub: BehaviorSubject<IVehicleCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
