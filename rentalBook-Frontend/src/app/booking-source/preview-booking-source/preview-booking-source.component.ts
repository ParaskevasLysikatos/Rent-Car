import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IBookingSourceCollection } from '../booking-source-collection.interface';
import { IBookingSource } from '../booking-source.interface';
import { BookingSourceService } from '../booking-source.service';

@Component({
  selector: 'app-preview-booking-source',
  templateUrl: './preview-booking-source.component.html',
  styleUrls: ['./preview-booking-source.component.scss'],
  providers: [{provide: ApiService, useClass: BookingSourceService}]
})
export class PreviewBookingSourceComponent extends PreviewComponent<IBookingSourceCollection> {
  displayedColumns = ['title','slug', 'actions'];

  constructor(protected injector: Injector,public  sourceSrv: BookingSourceService<IBookingSource>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IBookingSourceCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
      },
       {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: IBookingSourceCollection) => `${element.slug}`
      },

    ];
  }
}
