import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ITransition } from './transition.interface';

@Injectable({
  providedIn: 'root'
})
export class TransitionService<T extends ITransition> extends ApiService<T> {
  url = `${env.apiUrl}/transition`;

  total_TransitionSub: BehaviorSubject<ITransition> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
