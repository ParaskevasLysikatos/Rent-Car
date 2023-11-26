import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IServiceStatus } from './service-status.interface';

@Injectable({
  providedIn: 'root'
})
export class ServiceStatusService<T extends IServiceStatus> extends ApiService<T> {
  url = `${env.apiUrl}/visit/service_status`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
