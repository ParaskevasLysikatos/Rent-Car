import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IOptionsCollection } from './options-collection.interface';
import { IOptions } from './options.interface';

@Injectable({
  providedIn: 'root'
})
export class OptionsService<T extends IOptions> extends ApiService<T> {
  baseUrl = `${env.apiUrl}/options`;
  url = this.baseUrl;

  total_OptionsSub: BehaviorSubject<IOptionsCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }

  setType(type: string|null) {
    this.url = this.baseUrl;
    if (type) {
      this.url += '/' + type;
    }
  }
}
