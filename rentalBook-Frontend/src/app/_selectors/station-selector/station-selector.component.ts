import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateStationComponent } from 'src/app/stations/create-station/create-station.component';
import { EditStationComponent } from 'src/app/stations/edit-station/edit-station.component';
import { IStation } from 'src/app/stations/station.interface';
import { StationService } from 'src/app/stations/station.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-station-selector',
  templateUrl: './station-selector.component.html',
  styleUrls: ['./station-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => StationSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: StationSelectorComponent
    },
    {provide: ApiService, useClass: StationService}
  ]
})
export class StationSelectorComponent extends AbstractSelectorComponent<IStation> {
  readonly EditComponent = EditStationComponent;
  readonly CreateComponent = CreateStationComponent;
  label = 'Σταθμός';
}
