import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { GetParams } from '../_interfaces/get-params.interface';
import { ApiService } from '../_services/api-service.service';
import { IBookingItem } from './booking-item.interface';

@Injectable({
  providedIn: 'root'
})
export class BookingItemService<T extends IBookingItem> extends ApiService<T> {
  url = `${env.apiUrl}/`;

  constructor(protected injector: Injector) {
    super(injector);
  }

  map(obs: Observable<any>, nest?: string) {
    return obs.pipe(map(res => {
      if (nest) {
        res[nest].map((s: IBookingItem) => {
          s.option_id = s.option.id;
          return s;
        });
      } else {
        res.option_id = res.option.id;
      }

      return res;
    }));
  }

  get(filters?: GetParams, pageIndex?: number, pageSize?: number) {
    return this.map(super.get(filters, pageIndex, pageSize), 'data');
  }
}
