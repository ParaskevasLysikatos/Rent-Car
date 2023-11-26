import { Injectable, Injector } from '@angular/core';
import { environment as env } from 'src/environments/environment';
import { ApiService } from '../_services/api-service.service';
import { IDriveType } from './drive-type.interface';

@Injectable({
  providedIn: 'root'
})
export class DriveTypeService<T extends IDriveType> extends ApiService<T> {
  url = `${env.apiUrl}/vehicles/drive_type`;

  constructor(protected injector: Injector) {
    super(injector);
  }
}
