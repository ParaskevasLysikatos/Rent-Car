import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IServiceDetails } from './service-details.interface';

@Injectable({
  providedIn: 'root'
})
export class ServiceDetailsService<T extends IServiceDetails> extends ApiService<T> {
  url = `${env.apiUrl}/visit/service-details`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
