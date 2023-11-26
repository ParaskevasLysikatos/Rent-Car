import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IBrand } from './brand.interface';

@Injectable({
  providedIn: 'root'
})
export class BrandService<T extends IBrand> extends ApiService<T> {
  url = `${env.apiUrl}/brands`;

  total_BrandSub: BehaviorSubject<IBrand> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
