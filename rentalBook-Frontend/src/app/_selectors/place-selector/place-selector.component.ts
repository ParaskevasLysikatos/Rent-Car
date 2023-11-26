import { Component, Injector, Input, Optional, Self } from '@angular/core';
import { ControlValueAccessor, NgControl } from '@angular/forms';
import { MatFormFieldControl } from '@angular/material/form-field';
import { CreatePlaceComponent } from 'src/app/places/create-place/create-place.component';
import { EditPlaceComponent } from 'src/app/places/edit-place/edit-place.component';
import { IPlace } from 'src/app/places/place.interface';
import { PlaceService } from 'src/app/places/place.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractAutocompleteSelectorComponent } from '../abstract-autocomplete-selector/abstract-autocomplete-selector.component';

@Component({
  selector: 'app-place-selector',
  templateUrl: './place-selector.component.html',
  styleUrls: ['./place-selector.component.scss'],
  providers: [
    {provide: MatFormFieldControl, useExisting: PlaceSelectorComponent},
    {provide: ApiService, useClass: PlaceService}
  ]
})
export class PlaceSelectorComponent extends AbstractAutocompleteSelectorComponent<IPlace> implements MatFormFieldControl<any>,
  ControlValueAccessor {
  readonly EditComponent = EditPlaceComponent;
  readonly CreateComponent = CreatePlaceComponent;
  @Input() activeEditBtn: boolean = true;
  @Input() include: any[];
}
