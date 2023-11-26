import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IUseType } from './use-type.interface';

@Injectable({
  providedIn: 'root'
})
export class UseTypeService<T extends IUseType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/use`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
