import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ITypesCollection } from './types-collection.interface';
import { ITypes } from './types.interface';

@Injectable({
  providedIn: 'root'
})
export class TypesService<T extends ITypes> extends ApiService<T> {
  url = `${env.apiUrl}/types`;

  total_TypesSub: BehaviorSubject<ITypesCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
