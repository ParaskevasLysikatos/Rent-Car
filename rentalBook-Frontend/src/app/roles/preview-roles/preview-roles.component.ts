import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IRolesCollection } from '../roles-collection.interface';
import { RolesService } from '../roles.service';

@Component({
  selector: 'app-preview-roles',
  templateUrl: './preview-roles.component.html',
  styleUrls: ['./preview-roles.component.scss'],
  providers: [{provide: ApiService, useClass: RolesService}]
})
export class PreviewRolesComponent extends PreviewComponent<IRolesCollection> {
  displayedColumns = [ 'title','description','actions'];

  constructor(protected injector: Injector,public roleSrv:RolesService<IRolesCollection>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IRolesCollection) => `${element.title}`,
        hasFilter: true,
      },
       {
        columnDef: 'description',
        header: 'Περιγραφή',
        cell: (element: IRolesCollection) => `${element.description ?? ''}`
      }
    ];
  }
}
