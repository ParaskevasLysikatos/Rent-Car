import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { ICategoriesCollection } from './categories-collection.interface';
import { ICategories } from './categories.interface';

@Injectable({
  providedIn: 'root'
})
export class CategoriesService<T extends ICategories> extends ApiService<T> {
  url = `${env.apiUrl}/categories`;

  total_CategoriesSub: BehaviorSubject<ICategoriesCollection> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
