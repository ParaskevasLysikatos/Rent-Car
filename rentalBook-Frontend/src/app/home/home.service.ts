
import { HttpClient } from '@angular/common/http';
import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';

@Injectable({
  providedIn: 'root'
})
export class HomeService{

  url = `${env.apiUrl}/home`;

  constructor(private http: HttpClient) {
  }

get(){
  return this.http.get<any>(this.url);
}
}


