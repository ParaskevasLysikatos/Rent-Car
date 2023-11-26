import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IQuotes } from './quotes.interface';

@Injectable({
  providedIn: 'root'
})
export class QuotesService<T extends IQuotes> extends ApiService<T> {
  url = `${env.apiUrl}/quotes`;

  callSaveSubject: BehaviorSubject<boolean> = new BehaviorSubject(false);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
