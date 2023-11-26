import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { LicencePlateFormComponent } from '../licence-plate-form/licence-plate-form.component';
import { ILicencePlate } from '../licence-plate.interface';
import { LicencePlateService } from '../licence-plate.service';

@Component({
  selector: 'app-create-licence-plate',
  templateUrl: './create-licence-plate.component.html',
  styleUrls: ['./create-licence-plate.component.scss'],
  providers: [{provide: ApiService, useClass: LicencePlateService}]
})
export class CreateLicencePlateComponent extends CreateFormComponent<ILicencePlate> {
  @ViewChild(LicencePlateFormComponent, {static: true}) formComponent!: LicencePlateFormComponent;
}
