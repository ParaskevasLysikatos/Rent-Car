import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { TypesFormComponent } from '../types-form/types-form.component';
import { ITypes } from '../types.interface';
import { TypesService } from '../types.service';

@Component({
  selector: 'app-edit-types',
  templateUrl: './edit-types.component.html',
  styleUrls: ['./edit-types.component.scss'],
  providers: [{provide: ApiService, useClass: TypesService}]
})
export class EditTypesComponent extends EditFormComponent<ITypes> {
  @ViewChild(TypesFormComponent, {static: true}) formComponent!: TypesFormComponent;


  afterDataLoad(res:ITypes) {
    this.formComponent.form.get('options')?.patchValue(res.options.map(option=>option.id));//bring the values from response
    this.formComponent.setUpSelectedOptions();//patch the values on checkboxes

    this.formComponent.form.get('characteristics')?.patchValue(res.characteristics.map(characteristics => characteristics.id));//bring the values from response
    this.formComponent.setUpSelectedCharacteristics();//patch the values on checkboxes

    this.formComponent.iconData = res.icon;

  }


}
