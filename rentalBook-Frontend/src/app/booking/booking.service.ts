import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IBooking } from './booking.interface';

@Injectable({
  providedIn: 'root'
})
export class BookingService<T extends IBooking> extends ApiService<T> {
  url = `${env.apiUrl}/bookings`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
