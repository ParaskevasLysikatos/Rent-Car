import { ComponentType } from '@angular/cdk/portal';
import { AfterViewInit, Component, ComponentFactoryResolver, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { Observable } from 'rxjs';
import { CreateAgentComponent } from 'src/app/agent/create-agent/create-agent.component';
import { EditAgentComponent } from 'src/app/agent/edit-agent/edit-agent.component';
import { EditContactComponent } from 'src/app/contact/edit-contact/edit-contact.component';
import { FormDialogService } from 'src/app/form-dialog/form-dialog.service';
import { SubaccountService } from 'src/app/subaccount/subaccount.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-sub-account-selector',
  templateUrl: './sub-account-selector.component.html',
  styleUrls: ['./sub-account-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => SubAccountSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: SubAccountSelectorComponent
    },
    {provide: ApiService, useClass: SubaccountService}
  ]
})
export class SubAccountSelectorComponent extends AbstractSelectorComponent<any> implements AfterViewInit {
  readonly EditComponent = null;
  readonly CreateComponent = CreateAgentComponent;
  fields = ['type', 'id'];
  label = 'Πωλητής';

  ngAfterViewInit(): void {
    this.selector.showEditDialog = (option: any): Observable<any> => {
      let component!: ComponentType<any>;
      if (option?.type == 'App\\Contact') {
        component = EditContactComponent;
      } else {
        component = EditAgentComponent;
      }
      return this.selector.formDialogSrv.showDialog(component, {id: option.id});
    }
  }
}
