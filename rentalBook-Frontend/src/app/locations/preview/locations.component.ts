import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ILocation } from '../location.inteface';
import { LocationService } from '../location.service';

@Component({
  selector: 'app-locations',
  templateUrl: './locations.component.html',
  styleUrls: ['./locations.component.scss'],
  providers: [{provide: ApiService, useClass: LocationService}]
})
export class LocationsComponent extends PreviewComponent<ILocation> {
  displayedColumns = [ 'title',  'slug', 'actions'];

  constructor(protected injector: Injector,public locationSrv:LocationService) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: ILocation) => `${element.title}`,
         hasFilter: true,
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: ILocation) => `${element.slug}`,
        hasFilter: true,
      },
      // {
      //   columnDef: 'latitude',
      //   header: 'Latitude',
      //   cell: (element: ILocation) => `${element.latitude}`
      // },
      // {
      //   columnDef: 'longitude',
      //   header: 'Longitude',
      //   cell: (element: ILocation) => `${element.longitude}`
      // }
    ];
  }
}
