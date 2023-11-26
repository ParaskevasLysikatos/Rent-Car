import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CharacteristicsFormComponent } from '../characteristics-form/characteristics-form.component';
import { ICharacteristics } from '../characteristics.interface';
import { CharacteristicsService } from '../characteristics.service';

@Component({
  selector: 'app-edit-characteristics',
  templateUrl: './edit-characteristics.component.html',
  styleUrls: ['./edit-characteristics.component.scss'],
  providers: [{provide: ApiService, useClass: CharacteristicsService}]
})
export class EditCharacteristicsComponent extends EditFormComponent<ICharacteristics> {
  @ViewChild(CharacteristicsFormComponent, {static: true}) formComponent!: CharacteristicsFormComponent;


  afterDataLoad(res:ICharacteristics) {
    this.formComponent.iconData = res.icon;
  }

}
