import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateLocationComponent } from 'src/app/locations/create-location/create-location.component';
import { EditLocationComponent } from 'src/app/locations/edit-location/edit-location.component';
import { ILocation } from 'src/app/locations/location.inteface';
import { LocationService } from 'src/app/locations/location.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-location-selector',
  templateUrl: './location-selector.component.html',
  styleUrls: ['./location-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => LocationSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: LocationSelectorComponent
    },
    {provide: ApiService, useClass: LocationService}
  ]
})
export class LocationSelectorComponent extends AbstractSelectorComponent<ILocation> {
  readonly EditComponent = EditLocationComponent;
  readonly CreateComponent = CreateLocationComponent;
  label = 'Περιοχή';
}
