import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable, Injector } from '@angular/core';
import { delay, Observable, retry, shareReplay } from 'rxjs';
import { IApiService } from '../_interfaces/api-service.interface';
import { GetParams } from '../_interfaces/get-params.interface';
import { IPreview } from '../_interfaces/preview.interface';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})

export abstract class ApiService<Type> implements IApiService<Type> {
  abstract url: string;
  http: HttpClient;
  authSrv: AuthService;

  constructor(protected injector: Injector) {
    this.http = injector.get(HttpClient);
    this.authSrv = injector.get(AuthService);
  }

  get(filters?: GetParams, pageIndex?: number, pageSize?: number) {
    let params: any = {};
    if (filters) {
      params = filters;
    }

    if (pageIndex) {
      params.page = pageIndex;
    }

    if (pageSize == -1) {
      // set per_page to 1000 in order to get all options
      params.per_page = 1000;
    } else {
      if (pageSize) {
        this.authSrv.setPerPage(pageSize);
      }
      params.per_page = this.authSrv.getPerPage();
    }

    return this.http.get<IPreview<Type>>(this.url, { params }).pipe(
      shareReplay(1, 5 * 60 * 1000),// 5 min, in miillisec
      retry(2),// you retry 3 times
      delay(1000) // each retry will start after 1 second,
    );
  }

  edit(id: any): Observable<Type> {  //id:string was
    if (id != undefined && Object.keys(id).length > 3) {
      id = id.id;
    }
    return this.http.get<Type>(this.url + '/' + id).pipe(
      shareReplay(1,5*60*1000),// 5 min, in miillisec
      retry(2), // you retry 3 times
      delay(1000) // each retry will start after 1 second,

    );
  }

  create(data: Type): Observable<Type> {
    return this.http.post<Type>(this.url + '/create', data);
  }

  update(id: string, data: Type): Observable<Type>|any {
    return this.http.patch<Type>(this.url + '/' + id, data);
  }

  delete(id: string): Observable<any> {
    return this.http.delete<any>(this.url + '/' + id);
  }

}
