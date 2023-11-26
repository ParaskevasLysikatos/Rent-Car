import { Component, forwardRef, Injector } from '@angular/core';
import { NG_VALUE_ACCESSOR } from '@angular/forms';
import { CreateUserComponent } from 'src/app/user/create-user/create-user.component';
import { EditUserComponent } from 'src/app/user/edit-user/edit-user.component';
import { IUser } from 'src/app/user/user.interface';
import { UserService } from 'src/app/user/user.service';
import { ApiService } from 'src/app/_services/api-service.service';
import { AbstractSelectorComponent } from '../abstract-selector/abstract-selector.component';

@Component({
  selector: 'app-user-selector',
  templateUrl: './user-selector.component.html',
  styleUrls: ['./user-selector.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => UserSelectorComponent),
      multi: true
    },
    {
      provide: AbstractSelectorComponent,
      useExisting: UserSelectorComponent
    },
    {provide: ApiService, useClass: UserService}
  ]
})
export class UserSelectorComponent extends AbstractSelectorComponent<IUser> {
  readonly EditComponent = EditUserComponent;
  readonly CreateComponent = CreateUserComponent;
  label = 'Χρήστης';
}
