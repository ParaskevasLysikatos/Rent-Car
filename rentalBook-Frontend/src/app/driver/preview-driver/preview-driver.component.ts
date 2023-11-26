import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IDriverCollection } from '../driver-collection.interface';
import { IDriver } from '../driver.interface';
import { DriverService } from '../driver.service';

@Component({
  selector: 'app-preview-driver',
  templateUrl: './preview-driver.component.html',
  styleUrls: ['./preview-driver.component.scss'],
  providers: [{provide: ApiService, useClass: DriverService}]
})
export class PreviewDriverComponent extends PreviewComponent<IDriverCollection> {
  displayedColumns = ['firstname', 'lastname','phone', 'actions'];
  @ViewChild('fullname_filter', { static: true }) fullname: TemplateRef<any>;

  constructor(protected injector: Injector,public driverSrv:DriverService<IDriver>) {
    super(injector);
  }
  ngOnInit() {
    super.ngOnInit();
    this.columns = [
      {
        columnDef: 'firstname',
        header: 'Όνομα',
        cell: (element: IDriverCollection) => `${element?.contact?.firstname}`,
        hasFilter: true,
        filterTemplate: this.fullname,
        filterField: 'id',
      },
      {
        columnDef: 'lastname',
        header: 'Επώνυμο',
        cell: (element: IDriverCollection) => `${element?.contact?.lastname}`
      },
      {
        columnDef: 'phone',
        header: 'Τηλέφωνο',
        cell: (element: IDriverCollection) => `${element?.contact?.phone}`,
        hasFilter: true,
      }


    ];
  }

}
