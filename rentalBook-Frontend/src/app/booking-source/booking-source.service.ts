import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IBookingSource } from './booking-source.interface';

@Injectable({
  providedIn: 'root'
})
export class BookingSourceService<T extends IBookingSource> extends ApiService<T> {
  url = `${env.apiUrl}/booking_sources`;

  total_SourceSub: BehaviorSubject<IBookingSource> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
