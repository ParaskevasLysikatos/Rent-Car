import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { PlaceFormComponent } from '../place-form/place-form.component';
import { IPlace } from '../place.interface';
import { PlaceService } from '../place.service';

@Component({
  selector: 'app-edit-place',
  templateUrl: './edit-place.component.html',
  styleUrls: ['./edit-place.component.scss'],
  providers: [{provide: ApiService, useClass: PlaceService}]
})
export class EditPlaceComponent extends EditFormComponent<IPlace> {
  @ViewChild(PlaceFormComponent, {static: true}) formComponent!: PlaceFormComponent;

}
