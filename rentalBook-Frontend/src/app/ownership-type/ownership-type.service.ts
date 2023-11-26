import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IOwnershipType } from './ownership-type.interface';

@Injectable({
  providedIn: 'root'
})
export class OwnershipTypeService<T extends IOwnershipType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/ownership`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
