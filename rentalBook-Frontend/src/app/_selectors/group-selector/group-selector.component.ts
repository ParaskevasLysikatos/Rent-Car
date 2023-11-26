import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateTypesComponent } from 'src/app/types/create-types/create-types.component';
import { EditTypesComponent } from 'src/app/types/edit-types/edit-types.component';
import { ITypes } from 'src/app/types/types.interface';
import { TypesService } from 'src/app/types/types.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-group-selector',
  templateUrl: './group-selector.component.html',
  styleUrls: ['./group-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => GroupSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: GroupSelectorComponent
    },
    {provide: ApiService, useClass: TypesService}
  ]
})
export class GroupSelectorComponent extends AbstractSelectorComponent<ITypes> {
  readonly EditComponent = EditTypesComponent;
  readonly CreateComponent = CreateTypesComponent;
  label = 'Group';
}
