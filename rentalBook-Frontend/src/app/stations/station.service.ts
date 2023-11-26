import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IStationCollection } from './station-collection.interface';

@Injectable({
  providedIn: 'root'
})
export class StationService<T> extends ApiService<T> {
  url = `${env.apiUrl}/stations`;

  total_StationSub: BehaviorSubject<IStationCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
