import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { IDocuments } from 'src/app/documents/documents.interface';
import { IPayers } from 'src/app/rental/rental-form/rental-form.component';
import { environment as env } from 'src/environments/environment';


@Injectable({
  providedIn: 'root'
})
export class PrintBookingService {
  url = `${env.apiUrl}/create-booking-pdf`;
  urlMail= `${env.apiUrl}/mail-booking-pdf`;

  total_paidSubject: BehaviorSubject<number> = new BehaviorSubject(0);//without agent
  afterDataLoadSubject: BehaviorSubject<boolean> = new BehaviorSubject(false);

  createFirstTime: BehaviorSubject<boolean> = new BehaviorSubject(false);

  constructor(protected http: HttpClient) {
  }

  httpOptions = {
  //  'responseType': 'arraybuffer' as 'json'
    //'responseType'  : 'blob' as 'json'        //This also worked
  };

  get(id: number) {
    return this.http.get<IDocuments>(this.url + '/' + id, this.httpOptions);
  }

  mail(data: any) {
    return this.http.post<any>(this.urlMail, data);
  }
}
