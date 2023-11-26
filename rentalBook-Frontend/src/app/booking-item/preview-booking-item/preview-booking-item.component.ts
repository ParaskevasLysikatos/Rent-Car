import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IBookingItemCollection } from '../booking-item-collection.interface';
import { BookingItemService } from '../booking-item.service';

@Component({
  selector: 'app-preview-booking-item',
  templateUrl: './preview-booking-item.component.html',
  styleUrls: ['./preview-booking-item.component.scss'],
  providers: [{provide: ApiService, useClass: BookingItemService}]
})
export class PreviewBookingItemComponent extends PreviewComponent<IBookingItemCollection> {
  displayedColumns = ['id', 'actions'];

  constructor(protected injector: Injector) {
    super(injector);
    this.columns = [
      {
        columnDef: 'id',
        header: '#',
        cell: (element: IBookingItemCollection) => `${element.id}`
      }
    ];
  }
}
