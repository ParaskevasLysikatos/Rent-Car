import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IColorType } from './color-type.interface';

@Injectable({
  providedIn: 'root'
})
export class ColorTypeService<T extends IColorType> extends ApiService<T> {
  url = `${env.apiUrl}/color_types`;


  total_ColorSub: BehaviorSubject<IColorType> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
