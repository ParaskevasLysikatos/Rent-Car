import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateCompanyComponent } from 'src/app/company/create-company/create-company.component';
import { EditCompanyComponent } from 'src/app/company/edit-company/edit-company.component';
import { ICompany } from 'src/app/company/company.interface';
import { CompanyService } from 'src/app/company/company.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';

@Component({
  selector: 'app-company-selector',
  templateUrl: './company-selector.component.html',
  styleUrls: ['./company-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => CompanySelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: CompanySelectorComponent
    },
    {provide: ApiService, useClass: CompanyService}
  ]
})
export class CompanySelectorComponent extends AbstractSelectorComponent<ICompany> {
  readonly EditComponent = EditCompanyComponent;
  readonly CreateComponent = CreateCompanyComponent;
  label = 'Εταιρεία';
}
