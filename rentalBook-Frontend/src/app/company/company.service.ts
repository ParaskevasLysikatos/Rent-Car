import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ICompanyCollection } from './company-collection.interface';
import { ICompany } from './company.interface';

@Injectable({
  providedIn: 'root'
})
export class CompanyService<T extends ICompany> extends ApiService<T> {
  url = `${env.apiUrl}/companies`;

  total_CompaniesSub: BehaviorSubject<ICompanyCollection> = new BehaviorSubject(null);
  constructor(protected injector: Injector) {
    super(injector);
  }
}
