import { ComponentType } from '@angular/cdk/portal';
import { AfterViewInit, Component, ComponentFactoryResolver, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { Observable } from 'rxjs';
import { EditAgentComponent } from 'src/app/agent/edit-agent/edit-agent.component';
import { EditCompanyComponent } from 'src/app/company/edit-company/edit-company.component';
import { CustomerService } from 'src/app/customer/customer.service';
import { IDriver } from 'src/app/driver/driver.interface';
import { EditDriverComponent } from 'src/app/driver/edit-driver/edit-driver.component';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-customer-selector',
  templateUrl: './customer-selector.component.html',
  styleUrls: ['./customer-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => CustomerSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: CustomerSelectorComponent
    },
    {provide: ApiService, useClass: CustomerService}
  ]
})
export class CustomerSelectorComponent extends AbstractSelectorComponent<any>  implements AfterViewInit {
  readonly EditComponent = null;
  readonly CreateComponent = null;
  label = 'Πελάτης';
  fields = ['type', 'id'];


  ngAfterViewInit(): void {
    this.selector.showEditDialog = (option: any): Observable<any> => {
      let component!: ComponentType<any>;
      if (option.type == 'driver') {
        component = EditDriverComponent;
      } else if(option.type == 'agent') {
        component = EditAgentComponent;
      }
      else if (option.type == 'company') {
          component = EditCompanyComponent;
      }
      return this.selector.formDialogSrv.showDialog(component, {id: option.id});
    };
  }

}
