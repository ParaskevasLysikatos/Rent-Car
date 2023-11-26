import { Injectable, Injector } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IDocumentType } from './document-type.interface';

@Injectable({
  providedIn: 'root'
})
export class DocumentTypeService<T extends IDocumentType> extends ApiService<T> {
  url = `${env.apiUrl}/documentType`;

  total_DocTypeSub: BehaviorSubject<IDocumentType> = new BehaviorSubject(null);

  constructor(protected injector: Injector) {
    super(injector);
  }
}
