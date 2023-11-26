import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IClassType } from './class-type.interface';

@Injectable({
  providedIn: 'root'
})
export class ClassTypeService<T extends IClassType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/class`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
