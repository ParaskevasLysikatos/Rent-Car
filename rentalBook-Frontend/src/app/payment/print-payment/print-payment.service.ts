
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map } from 'rxjs/operators';
import { IDocuments } from 'src/app/documents/documents.interface';
import { environment as env } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PrintPaymentService {
  url = `${env.apiUrl}/create-payment-pdf`;
  urlMail = `${env.apiUrl}/mail-payment-pdf`;

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
}
