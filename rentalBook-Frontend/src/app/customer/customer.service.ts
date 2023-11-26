import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CustomerService extends ApiService<any> {
  url = `${env.apiUrl}/customer`;

  constructor(protected injector: Injector) {
    super(injector);
  }

  // get(filters?: object, pageIndex?: number, pageSize?: number) {
  //   return super.get(filters, pageIndex,15).pipe(map(res => {
  //     return res;
  //   }));
  // }


}
