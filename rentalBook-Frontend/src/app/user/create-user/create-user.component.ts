import { Component, Injector, ViewChild } from '@angular/core';
import { CreateFormComponent } from 'src/app/create-form/create-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { UserFormComponent } from '../user-form/user-form.component';
import { IUser } from '../user.interface';
import { UserService } from '../user.service';

@Component({
  selector: 'app-create-user',
  templateUrl: './create-user.component.html',
  styleUrls: ['./create-user.component.scss'],
  providers: [{provide: ApiService, useClass: UserService}]
})
export class CreateUserComponent extends CreateFormComponent<IUser> {
  @ViewChild(UserFormComponent, {static: true}) formComponent!: UserFormComponent;
}
