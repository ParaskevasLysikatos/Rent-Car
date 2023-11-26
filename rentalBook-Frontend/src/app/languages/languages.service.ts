import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ILanguagesCollection } from './languages-collection.interface';
import { ILanguages } from './languages.interface';

@Injectable({
  providedIn: 'root'
})
export class LanguagesService<T extends ILanguages> extends ApiService<T> {
  url = `${env.apiUrl}/languages`;

  total_LanguageSub: BehaviorSubject<ILanguagesCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
  
}
