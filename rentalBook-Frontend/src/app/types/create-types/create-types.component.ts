import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { TypesFormComponent } from '../types-form/types-form.component';
import { ITypes } from '../types.interface';
import { TypesService } from '../types.service';

@Component({
  selector: 'app-create-types',
  templateUrl: './create-types.component.html',
  styleUrls: ['./create-types.component.scss'],
  providers: [{provide: ApiService, useClass: TypesService}]
})
export class CreateTypesComponent extends CreateFormComponent<ITypes> {
  @ViewChild(TypesFormComponent, {static: true}) formComponent!: TypesFormComponent;
}
