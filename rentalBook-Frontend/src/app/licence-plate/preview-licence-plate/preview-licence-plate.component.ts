import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ILicencePlateCollection } from '../licence-plate-collection.interface';
import { LicencePlateService } from '../licence-plate.service';

@Component({
  selector: 'app-preview-licence-plate',
  templateUrl: './preview-licence-plate.component.html',
  styleUrls: ['./preview-licence-plate.component.scss'],
  providers: [{provide: ApiService, useClass: LicencePlateService}]
})
export class PreviewLicencePlateComponent extends PreviewComponent<ILicencePlateCollection> {
  displayedColumns = ['licence_plate','registration_date', 'actions'];

  constructor(protected injector: Injector) {
    super(injector);
    this.columns = [
      {
        columnDef: 'licence_plate',
        header: 'Πινακίδα',
        cell: (element: ILicencePlateCollection) => `${element.licence_plate}`
      },
      {
        columnDef: 'registration_date',
        header: 'Ημερομηνία',
        cell: (element: ILicencePlateCollection) => `${element.registration_date}`
      }
    ];
  }
}
