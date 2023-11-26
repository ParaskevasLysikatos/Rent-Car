import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IRental } from './rental.interface';

@Injectable({
  providedIn: 'root'
})
export class RentalService<T extends IRental> extends ApiService<T> {
  url = `${env.apiUrl}/rentals`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
