import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment as env } from 'src/environments/environment';
import { IInvoicesCollection } from './invoices-collection.interface';


@Injectable({
  providedIn: 'root'
})
export class InvoiceTotalService {
  //url = `${env.apiUrl}/invoices/total`;

  constructor(protected http: HttpClient) {
  }

  total_invoiceSub: BehaviorSubject<IInvoicesCollection> = new BehaviorSubject(null);

  // get() {
  //   return this.http.get<any>(this.url).pipe(map(res => res));
  // }
}
