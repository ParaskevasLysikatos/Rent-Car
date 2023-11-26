import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { StationFormComponent } from '../station-form/station-form.component';
import { IStation } from '../station.interface';
import { StationService } from '../station.service';

@Component({
  selector: 'app-edit-station',
  templateUrl: './edit-station.component.html',
  styleUrls: ['./edit-station.component.scss'],
  providers: [{provide: ApiService, useClass: StationService}]
})
export class EditStationComponent extends EditFormComponent<IStation> {
  @ViewChild(StationFormComponent, {static: true}) formComponent!: StationFormComponent;
}
