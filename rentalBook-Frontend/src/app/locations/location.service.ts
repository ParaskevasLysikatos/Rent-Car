import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ILocation } from './location.inteface';

@Injectable({
  providedIn: 'root'
})
export class LocationService extends ApiService<ILocation> {
  url = `${env.apiUrl}/locations`;

  total_LocationSub: BehaviorSubject<ILocation> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
