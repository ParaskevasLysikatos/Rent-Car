import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IPeriodicFee } from './periodic-fee.interface';

@Injectable({
  providedIn: 'root'
})
export class PeriodicFeeService<T extends IPeriodicFee> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/periodicFee`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
