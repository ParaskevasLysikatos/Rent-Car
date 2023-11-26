import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IInvoices } from './invoices.interface';

@Injectable({
  providedIn: 'root'
})
export class InvoicesService<T extends IInvoices> extends ApiService<T> {
  url = `${env.apiUrl}/invoices`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
