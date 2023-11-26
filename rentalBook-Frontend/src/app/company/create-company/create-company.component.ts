import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CompanyFormComponent } from '../company-form/company-form.component';
import { ICompany } from '../company.interface';
import { CompanyService } from '../company.service';

@Component({
  selector: 'app-create-company',
  templateUrl: './create-company.component.html',
  styleUrls: ['./create-company.component.scss'],
  providers: [{provide: ApiService, useClass: CompanyService}]
})
export class CreateCompanyComponent extends CreateFormComponent<ICompany> {
  @ViewChild(CompanyFormComponent, {static: true}) formComponent!: CompanyFormComponent;
}
