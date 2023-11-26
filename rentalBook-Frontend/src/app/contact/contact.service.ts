import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IContactCollection } from './contact-collection.interface';
import { IContact } from './contact.interface';

@Injectable({
  providedIn: 'root'
})
export class ContactService<T extends IContact> extends ApiService<T> {
  url = `${env.apiUrl}/contacts`;

  total_ContactSub: BehaviorSubject<IContactCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
