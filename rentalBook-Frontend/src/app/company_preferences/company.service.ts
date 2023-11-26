import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ICompanyPreferences } from './company.interface';

@Injectable({
  providedIn: 'root'
})
export class CompanyPreferencesService {
  url = `${env.apiUrl}/company_preferences`;

  constructor(private http: HttpClient) { }

  edit(): Observable<ICompanyPreferences> {
    return this.http.get<ICompanyPreferences>(this.url);
  }

  update(data: ICompanyPreferences): Observable<ICompanyPreferences> {
    return this.http.post<ICompanyPreferences>(this.url, data);
  }

}
