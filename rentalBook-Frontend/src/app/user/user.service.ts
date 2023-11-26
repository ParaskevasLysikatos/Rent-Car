import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IUserCollection } from './user-collection.interface';
import { IUser } from './user.interface';

@Injectable({
  providedIn: 'root'
})
export class UserService<T extends IUser> extends ApiService<T> {
  url = `${env.apiUrl}/users`;

  total_UserSub: BehaviorSubject<IUserCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
