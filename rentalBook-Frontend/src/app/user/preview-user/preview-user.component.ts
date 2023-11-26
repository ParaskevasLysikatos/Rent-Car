import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IUserCollection } from '../user-collection.interface';
import { UserService } from '../user.service';

@Component({
  selector: 'app-preview-user',
  templateUrl: './preview-user.component.html',
  styleUrls: ['./preview-user.component.scss'],
  providers: [{provide: ApiService, useClass: UserService}]
})
export class PreviewUserComponent extends PreviewComponent<IUserCollection> {
  displayedColumns = ['name','email','phone', 'actions'];

  constructor(protected injector: Injector,public userSrv: UserService<IUserCollection>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'name',
        header: 'Όνομα',
        cell: (element: IUserCollection) => `${element.name}`,
        hasFilter:true,
      },
       {
        columnDef: 'email',
        header: 'E-mail',
        cell: (element: IUserCollection) => `${element.email}`,
         hasFilter: true,
      },
        {
        columnDef: 'phone',
        header: 'Τηλέφωνο',
        cell: (element: IUserCollection) => `${element.phone}`,
          hasFilter: true,
      },
    ];
  }
}
