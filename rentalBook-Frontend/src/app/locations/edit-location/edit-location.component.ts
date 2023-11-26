import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { LocationFormComponent } from '../location-form/location-form.component';
import { ILocation } from '../location.inteface';
import { LocationService } from '../location.service';

@Component({
  selector: 'app-edit-location',
  templateUrl: './edit-location.component.html',
  styleUrls: ['./edit-location.component.scss'],
  providers: [{provide: ApiService, useClass: LocationService}]
})
export class EditLocationComponent extends EditFormComponent<ILocation> {
  @ViewChild(LocationFormComponent, {static: true}) formComponent!: LocationFormComponent;
}
