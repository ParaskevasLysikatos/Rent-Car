import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { RolesFormComponent } from '../roles-form/roles-form.component';
import { IRoles } from '../roles.interface';
import { RolesService } from '../roles.service';

@Component({
  selector: 'app-edit-roles',
  templateUrl: './edit-roles.component.html',
  styleUrls: ['./edit-roles.component.scss'],
  providers: [{provide: ApiService, useClass: RolesService}]
})
export class EditRolesComponent extends EditFormComponent<IRoles> {
  @ViewChild(RolesFormComponent, {static: true}) formComponent!: RolesFormComponent;
}
