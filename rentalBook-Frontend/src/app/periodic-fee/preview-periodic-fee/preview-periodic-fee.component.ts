import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IPeriodicFeeCollection } from '../periodic-fee-collection.interface';
import { PeriodicFeeService } from '../periodic-fee.service';

@Component({
  selector: 'app-preview-periodic-fee',
  templateUrl: './preview-periodic-fee.component.html',
  styleUrls: ['./preview-periodic-fee.component.scss'],
  providers: [{provide: ApiService, useClass: PeriodicFeeService}]
})
export class PreviewPeriodicFeeComponent extends PreviewComponent<IPeriodicFeeCollection> {
  displayedColumns = ['id', 'actions'];

  constructor(protected injector: Injector) {
    super(injector);
    this.columns = [
      {
        columnDef: 'id',
        header: '#',
        cell: (element: IPeriodicFeeCollection) => `${element.id}`
      }
    ];
  }
}
