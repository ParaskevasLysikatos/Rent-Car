import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { StationFormComponent } from '../station-form/station-form.component';
import { IStation } from '../station.interface';
import { StationService } from '../station.service';

@Component({
  selector: 'app-create-station',
  templateUrl: './create-station.component.html',
  styleUrls: ['./create-station.component.scss'],
  providers: [{provide: ApiService, useClass: StationService}]
})
export class CreateStationComponent extends CreateFormComponent<IStation> {
  @ViewChild(StationFormComponent, {static: true}) formComponent!: StationFormComponent;
}
