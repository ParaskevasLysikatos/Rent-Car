import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { ApiService } from 'src/app/_services/api-service.service';
import { ICancelReasons } from './cancel-reason.interface';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})


export class CancelReasonService {
  url = `${env.apiUrl}/bookings/reason`;



  cancelSubject: BehaviorSubject<number> = new BehaviorSubject(null);


  constructor(protected http: HttpClient) {
  }

  get() {
    return this.http.options<any[]>(this.url);
  }

  getOne(id:number) {
    return this.http.get<any>(this.url+'/'+id);
  }

}
