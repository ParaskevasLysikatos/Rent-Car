
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { map } from 'rxjs/operators';
import { IDocuments } from 'src/app/documents/documents.interface';
import { environment as env } from 'src/environments/environment';
import { IInvoices } from '../invoices.interface';

@Injectable({
  providedIn: 'root'
})
export class PrintInvoiceService {
  url = `${env.apiUrl}/create-invoice-pdf`;
  urlMail = `${env.apiUrl}/mail-invoice-pdf`;
  urlBadPrint = `${env.apiUrl}/invoices/badPrinting`;

  createFirstTime: BehaviorSubject<boolean> = new BehaviorSubject(false);

  constructor(protected http: HttpClient) {
  }

  httpOptions = {
   // 'responseType': 'arraybuffer' as 'json'
    //'responseType'  : 'blob' as 'json'        //This also worked
  };

  get(id: number) {
    return this.http.get<IDocuments>(this.url + '/' + id, this.httpOptions);
  }

  mail(data: any) {
    return this.http.post<any>(this.urlMail, data);
  }

  badPrint(data:IInvoices) {
    return this.http.post<any>(this.urlBadPrint, data);
  }
}
