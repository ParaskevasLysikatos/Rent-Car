import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IRateCode } from './rate-code.interface';

@Injectable({
  providedIn: 'root'
})
export class RateCodeService<T extends IRateCode> extends ApiService<T> {
  url = `${env.apiUrl}/rate-code`;

  total_RateCodeSub: BehaviorSubject<IRateCode> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
