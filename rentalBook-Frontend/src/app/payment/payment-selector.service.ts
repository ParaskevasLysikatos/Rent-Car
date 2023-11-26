import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IPayment } from './payment.interface';

@Injectable({
  providedIn: 'root'
})
export class PaymentSelectorService<T extends IPayment> extends ApiService<T> {
  baseUrl = `${env.apiUrl}/payments/payment`;
  url = this.baseUrl;
  constructor(protected injector: Injector) {
    super(injector);
  }

  // setType(type: string | null) {
  //   this.url = this.baseUrl;
  //   if (type) {
  //     this.url += '/' + type;
  //   }
  // }
}
