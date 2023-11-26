import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ColorTypeFormComponent } from '../color-type-form/color-type-form.component';
import { IColorType } from '../color-type.interface';
import { ColorTypeService } from '../color-type.service';

@Component({
  selector: 'app-create-color-type',
  templateUrl: './create-color-type.component.html',
  styleUrls: ['./create-color-type.component.scss'],
  providers: [{provide: ApiService, useClass: ColorTypeService}]
})
export class CreateColorTypeComponent extends CreateFormComponent<IColorType> {
  @ViewChild(ColorTypeFormComponent, {static: true}) formComponent!: ColorTypeFormComponent;
}
