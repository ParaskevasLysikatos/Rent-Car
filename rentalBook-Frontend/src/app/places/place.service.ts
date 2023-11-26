import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IPlace } from './place.interface';

@Injectable({
  providedIn: 'root'
})
export class PlaceService extends ApiService<IPlace> {
  url = `${env.apiUrl}/places`;

  total_PlaceSub: BehaviorSubject<IPlace> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
