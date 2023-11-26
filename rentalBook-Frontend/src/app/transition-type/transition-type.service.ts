import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ITransitionType } from './transition-type.interface';

@Injectable({
  providedIn: 'root'
})
export class TransitionTypeService<T extends ITransitionType> extends ApiService<T> {
  url = `${env.apiUrl}/transition/type`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
