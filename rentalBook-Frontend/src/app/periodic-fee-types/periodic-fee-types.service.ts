import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IPeriodicFeeTypes } from './periodic-fee-types.interface';

@Injectable({
  providedIn: 'root'
})
export class PeriodicFeeTypesService<T extends IPeriodicFeeTypes> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/periodicFeeTypes`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
