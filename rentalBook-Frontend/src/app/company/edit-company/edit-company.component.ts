import { Component, Injector, ViewChild } from '@angular/core';
import { IDriver } from 'src/app/driver/driver.interface';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CompanyFormComponent } from '../company-form/company-form.component';
import { ICompany } from '../company.interface';
import { CompanyService } from '../company.service';

@Component({
  selector: 'app-edit-company',
  templateUrl: './edit-company.component.html',
  styleUrls: ['./edit-company.component.scss'],
  providers: [{provide: ApiService, useClass: CompanyService}]
})
export class EditCompanyComponent extends EditFormComponent<ICompany> {
  @ViewChild(CompanyFormComponent, {static: true}) formComponent!: CompanyFormComponent;

  afterDataLoad(res:ICompany) {
    this.formComponent.invoices=res.invoices;
    this.formComponent.rentals = res.rentals;
    this.formComponent.bookings = res.bookings;
    this.formComponent.quotes = res.quotes;
  }

}
