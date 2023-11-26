import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/internal/Observable';
import { environment as env } from 'src/environments/environment';
import { IRental } from '../rental.interface';


@Injectable({
  providedIn: 'root'
})
export class RentalSignatureService {
  url = `${env.apiUrl}/rentals/signature`;

  constructor(protected http: HttpClient) { }

  save(missingUrl: string,file: any, rental:IRental): Observable<any> {
    return this.http.post<any>(this.url+missingUrl, { 'file': file, 'rental': rental });
  }

  delete(missingUrl: string, rental: IRental): Observable<any> {
    return this.http.post<any>(this.url + missingUrl, {'rental': rental });
  }

  seeImg(missingUrl: string, rental: IRental): Observable<string> {
    return this.http.post<any>(this.url + missingUrl, { 'rental': rental });
  }


}
