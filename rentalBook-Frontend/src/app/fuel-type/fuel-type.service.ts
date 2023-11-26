import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IFuelType } from './fuel-type.interface';

@Injectable({
  providedIn: 'root'
})
export class FuelTypeService<T extends IFuelType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/fuel`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
