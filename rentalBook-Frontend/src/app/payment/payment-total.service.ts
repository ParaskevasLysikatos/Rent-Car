import { IPaymentCollection } from './payment-collection.interface';
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { map } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { IPaymentMethod } from './payment-method.interface';

@Injectable({
  providedIn: 'root'
})
export class PaymentTotalService {
 // url = `${env.apiUrl}/payments/total`;

  total_paymentSub: BehaviorSubject<IPaymentCollection> = new BehaviorSubject(null);

  constructor(protected http: HttpClient) {
  }

  // get() {
  //   return this.http.get<any>(this.url).pipe(map(res => res));
  // }
}
