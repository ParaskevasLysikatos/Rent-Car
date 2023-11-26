import { Component, Injector, ViewChild } from '@angular/core';
import { EditFormComponent } from 'src/app/edit-form/edit-form.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { AuthService } from 'src/app/_services/auth.service';
import { UserFormComponent } from '../user-form/user-form.component';
import { IUser } from '../user.interface';
import { UserService } from '../user.service';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.scss'],
  providers: [{provide: ApiService, useClass: UserService}]
})
export class EditUserComponent extends EditFormComponent<IUser> {
  @ViewChild(UserFormComponent, {static: true}) formComponent!: UserFormComponent;
  constructor(protected injector: Injector, protected authSrv: AuthService) {
    super(injector);
  }
  submitted = (res) => {
    // console.log(res);
    this.authSrv.getUser();
    localStorage.setItem('loggedUser', JSON.stringify(res));
  }
}
