import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { GetParams } from '../_interfaces/get-params.interface';
import { IPreview } from '../_interfaces/preview.interface';
import { ApiService } from '../_services/api-service.service';

@Injectable({
  providedIn: 'root'
})
export class RentalSelectorService extends ApiService<any> {
  url = `${env.apiUrl}/rentals`;

  constructor(protected injector: Injector) {
    super(injector);
  }


  get(filters?: object, pageIndex?: number, pageSize?: number) {
    if (filters == null) {
      filters = { 'id[]': 0, 'per_page': 10 }; // needed because will bring default sub accounts
    }
    return super.get(filters, pageIndex, pageSize).pipe(map(res => {
      res.data.map(item => {
        item.key = item.type + '-' + item.id;
        return item;
      })
      return res;
    }));
  }
}

