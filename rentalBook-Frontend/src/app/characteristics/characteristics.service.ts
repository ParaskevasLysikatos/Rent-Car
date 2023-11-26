import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ICharacteristicsCollection } from './characteristics-collection.interface';
import { ICharacteristics } from './characteristics.interface';

@Injectable({
  providedIn: 'root'
})
export class CharacteristicsService<T extends ICharacteristics> extends ApiService<T> {
  url = `${env.apiUrl}/characteristics`;

  total_CharSub: BehaviorSubject<ICharacteristicsCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
