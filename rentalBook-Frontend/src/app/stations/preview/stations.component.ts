import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IStationCollection } from '../station-collection.interface';
import { IStation } from '../station.interface';
import { StationService } from '../station.service';

@Component({
  selector: 'app-stations',
  templateUrl: './stations.component.html',
  styleUrls: ['./stations.component.scss'],
  providers: [{provide: ApiService, useClass: StationService}]
})
export class StationsComponent extends PreviewComponent<IStationCollection> {
  displayedColumns = [ 'title', 'slug', 'location', 'actions'];

  constructor(protected injector: Injector, public stationSrv: StationService<IStation>) {
    super(injector);
    this.columns = [

      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IStationCollection) => `${element.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: IStationCollection) => `${element.slug}`
      },
      {
        columnDef: 'location',
        header: 'Γεωγραφικό Διαμέρισμα',
        cell: (element: IStationCollection) => `${element.location?.title}`,
        hasFilter: true,
      }
    ];
  }
}
