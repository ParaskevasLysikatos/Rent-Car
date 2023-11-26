import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { GetParams } from '../_interfaces/get-params.interface';
import { IPreview } from '../_interfaces/preview.interface';
import { AuthService } from '../_services/auth.service';
import { Tag } from './tag.interface';
import { environment as env } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class TagService {
  url = `${env.apiUrl}/tags`;

  constructor(private http: HttpClient, private authSrv: AuthService) { }

  get(filters?: GetParams, pageIndex?: number, pageSize?: number) {
    let params: any = {};
    if (filters) {
      params = filters;
    }

    if (pageIndex) {
      params.page = pageIndex;
    }

    if (pageSize == -1) {
      // set per_page to 1000 in order to get all options
      params.per_page = 1000;
    } else {
      if (pageSize) {
        this.authSrv.setPerPage(pageSize);
      }
      params.per_page = this.authSrv.getPerPage();
    }

    return this.http.get<IPreview<Tag>>(this.url, {params});
  }
}
