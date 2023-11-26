import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { CharacteristicsFormComponent } from '../characteristics-form/characteristics-form.component';
import { ICharacteristics } from '../characteristics.interface';
import { CharacteristicsService } from '../characteristics.service';

@Component({
  selector: 'app-create-characteristics',
  templateUrl: './create-characteristics.component.html',
  styleUrls: ['./create-characteristics.component.scss'],
  providers: [{provide: ApiService, useClass: CharacteristicsService}]
})
export class CreateCharacteristicsComponent extends CreateFormComponent<ICharacteristics> {
  @ViewChild(CharacteristicsFormComponent, {static: true}) formComponent!: CharacteristicsFormComponent;
}
