import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IVisitCollection } from './visit-collection.interface';
import { IVisit } from './visit.interface';

@Injectable({
  providedIn: 'root'
})
export class VisitService<T extends IVisit> extends ApiService<T> {
  url = `${env.apiUrl}/visit`;

  total_VisitSub: BehaviorSubject<IVisitCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
