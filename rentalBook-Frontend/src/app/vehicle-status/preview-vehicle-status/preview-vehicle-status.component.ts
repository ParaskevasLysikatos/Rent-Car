import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IVehicleStatusCollection } from '../vehicle-status-collection.interface';
import { IVehicleStatus } from '../vehicle-status.interface';
import { VehicleStatusService } from '../vehicle-status.service';

@Component({
  selector: 'app-preview-vehicle-status',
  templateUrl: './preview-vehicle-status.component.html',
  styleUrls: ['./preview-vehicle-status.component.scss'],
  providers: [{provide: ApiService, useClass: VehicleStatusService}]
})
export class PreviewVehicleStatusComponent extends PreviewComponent<IVehicleStatusCollection> {
  displayedColumns = ['name','description', 'actions'];

  constructor(protected injector: Injector,public v_statusSrv:VehicleStatusService<IVehicleStatus>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'name',
        header: 'Όνομα',
        cell: (element: IVehicleStatusCollection) => `${element.profiles?.el?.title ?? '-'}`,
        hasFilter: true,
      },
      {
        columnDef: 'description',
        header: 'Περιγραφή',
        cell: (element: IVehicleStatusCollection) => `${element.profiles?.el?.description ?? '-'}`,
        hasFilter: true
      }

    ];
  }
}
