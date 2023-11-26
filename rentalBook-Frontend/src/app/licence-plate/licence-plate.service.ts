import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ILicencePlate } from './licence-plate.interface';

@Injectable({
  providedIn: 'root'
})
export class LicencePlateService<T extends ILicencePlate> extends ApiService<T> {
url = `${env.apiUrl}/licencePlate`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
