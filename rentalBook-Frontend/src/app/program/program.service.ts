import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IProgram } from './program.interface';

@Injectable({
  providedIn: 'root'
})
export class ProgramService<T extends IProgram> extends ApiService<T> {
  url = `${env.apiUrl}/programs`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
