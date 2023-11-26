import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { RolesFormComponent } from '../roles-form/roles-form.component';
import { IRoles } from '../roles.interface';
import { RolesService } from '../roles.service';

@Component({
  selector: 'app-create-roles',
  templateUrl: './create-roles.component.html',
  styleUrls: ['./create-roles.component.scss'],
  providers: [{provide: ApiService, useClass: RolesService}]
})
export class CreateRolesComponent extends CreateFormComponent<IRoles> {
  @ViewChild(RolesFormComponent, {static: true}) formComponent!: RolesFormComponent;
}
