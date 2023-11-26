import { ComponentType } from "@angular/cdk/portal";
import { ApiService } from "src/app/_services/api-service.service";
import { AbstractSelectorComponent } from "../abstract-selector/abstract-selector.component";

export interface SelectorParams<T> {
    apiSrv: ApiService<T>;
    editComponent: ComponentType<any>;
    createComponent: ComponentType<any>;
    multiple?: boolean;
    data?: Array<T>;
    addBtn?: boolean;
    editBtn?: boolean;
  clearBtn?: boolean;
    key?: string;
    label?: string;
    excludeSelectors?: Array<AbstractSelectorComponent<any>>;
  include?: any[];
  type_id?: any[];//vehicle-selector
  status2?: any[];//vehicle-selector
  vehicle_status?: any[];//vehicle-selector
  role?: any[];//vehicle-selector
  from?: any[];//vehicle-selector
  to?: any[];//vehicle-selector
  rental_id?: any[];//vehicle-selector
}
