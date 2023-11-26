import { ComponentFactoryResolver, Injectable, Injector } from '@angular/core';
import {
  Router, Resolve,
  RouterStateSnapshot,
  ActivatedRouteSnapshot
} from '@angular/router';
import { Observable } from 'rxjs';
import { OptionsService } from '../options/options.service';
import { PaymentService } from '../payment/payment.service';
import { ApiService } from './api-service.service';

@Injectable({
  providedIn: 'root'
})
export class PreviewResolver implements Resolve<any> {
  constructor(private injector: Injector, private componentFac: ComponentFactoryResolver) {}

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> {
    const fac = this.componentFac.resolveComponentFactory(route.routeConfig.component).create(this.injector);
    const apiSrv: ApiService<any> = fac.instance.apiSrv;
    fac.destroy();
    if (route.params.type) {
      const type = route.params.type;
      if (apiSrv instanceof OptionsService || apiSrv instanceof PaymentService) {
        apiSrv.setType(type);
      }
    }
    const filters:any = route.queryParams ? {...route.queryParams} : {};
    if (route.queryParams.sortBy && route.queryParams.sortDirection) {
      filters.orderBy = route.queryParams.sortBy;
      filters.orderByType = route.queryParams.sortDirection;
    }
    return apiSrv.get(filters, route.queryParams.page, route.queryParams.perPage);
  }
}
