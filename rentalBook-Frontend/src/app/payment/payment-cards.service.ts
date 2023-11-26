import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { IPaymentMethod } from './payment-method.interface';

@Injectable({
  providedIn: 'root'
})
export class PaymentCardsService {
  url = `${env.apiUrl}/payments/cards`;

  constructor(protected http: HttpClient) {
  }

  get() {
    return this.http.get<any>(this.url).pipe(map(res => res.data));
  }
}
