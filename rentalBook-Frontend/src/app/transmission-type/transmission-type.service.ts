import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ITransmissionType } from './transmission-type.interface';

@Injectable({
  providedIn: 'root'
})
export class TransmissionTypeService<T extends ITransmissionType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/transmission`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
