import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IRoles } from './roles.interface';

@Injectable({
  providedIn: 'root'
})
export class RolesService<T extends IRoles> extends ApiService<T> {
  url = `${env.apiUrl}/roles`;

  total_RoleSub: BehaviorSubject<IRoles> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
