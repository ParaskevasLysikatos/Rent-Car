import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IVehicleExchangesCollection } from './vehicle-exchanges-collection.interface';
import { IVehicleExchanges } from './vehicle-exchanges.interface';

@Injectable({
  providedIn: 'root'
})
export class VehicleExchangesService<T extends IVehicleExchanges> extends ApiService<T> {
  url = `${env.apiUrl}/vehicle-exchanges`;

  afterDataLoadSubject: BehaviorSubject<boolean> = new BehaviorSubject(false);

  total_V_ExchangeSub: BehaviorSubject<IVehicleExchangesCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
