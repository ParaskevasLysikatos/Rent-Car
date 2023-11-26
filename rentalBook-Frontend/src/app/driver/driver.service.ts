import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IDriverCollection } from './driver-collection.interface';
import { IDriver } from './driver.interface';

@Injectable({
  providedIn: 'root'
})
export class DriverService<T extends IDriver> extends ApiService<T> {
  url = `${env.apiUrl}/drivers`;

  total_DriverSub: BehaviorSubject<IDriverCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
