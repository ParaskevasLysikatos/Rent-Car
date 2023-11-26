import { Component, Injector } from '@angular/core';
import { LanguageService } from 'src/app/languages/language.service';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IPlaceCollection } from '../place-collection.interface';
import { PlaceService } from '../place.service';

@Component({
  selector: 'app-places-preview',
  templateUrl: './places-preview.component.html',
  styleUrls: ['./places-preview.component.scss'],
  providers: [{provide: ApiService, useClass: PlaceService}]
})
export class PlacesPreviewComponent extends PreviewComponent<IPlaceCollection> {
  displayedColumns = [ 'title', 'slug', 'stations', 'actions'];

  constructor(public injector: Injector,public placeSrv: PlaceService) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IPlaceCollection) => `${element.profiles?.el?.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'slug',
        header: 'Σύνδεσμος',
        cell: (element: IPlaceCollection) => `${element.slug}`
      },
      {
        columnDef: 'stations',
        header: 'Σταθμοί',
        cell: (element: IPlaceCollection) => `${element.stations.map(station => station.profiles?.el.title).join(',')}`,
        hasFilter: true,
      }
    ];
  }
}
