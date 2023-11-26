import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ColorTypeFormComponent } from '../color-type-form/color-type-form.component';
import { IColorType } from '../color-type.interface';
import { ColorTypeService } from '../color-type.service';

@Component({
  selector: 'app-edit-color-type',
  templateUrl: './edit-color-type.component.html',
  styleUrls: ['./edit-color-type.component.scss'],
  providers: [{provide: ApiService, useClass: ColorTypeService}]
})
export class EditColorTypeComponent extends EditFormComponent<IColorType> {
  @ViewChild(ColorTypeFormComponent, {static: true}) formComponent!: ColorTypeFormComponent;
}
