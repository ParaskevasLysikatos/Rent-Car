import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { LicencePlateFormComponent } from '../licence-plate-form/licence-plate-form.component';
import { ILicencePlate } from '../licence-plate.interface';
import { LicencePlateService } from '../licence-plate.service';

@Component({
  selector: 'app-edit-licence-plate',
  templateUrl: './edit-licence-plate.component.html',
  styleUrls: ['./edit-licence-plate.component.scss'],
  providers: [{provide: ApiService, useClass: LicencePlateService}]
})
export class EditLicencePlateComponent extends EditFormComponent<ILicencePlate> {
  @ViewChild(LicencePlateFormComponent, {static: true}) formComponent!: LicencePlateFormComponent;
}
