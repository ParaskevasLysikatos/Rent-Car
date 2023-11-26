import { Component, Injector, TemplateRef, ViewChild } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { IUser } from 'src/app/user/user.interface';
import { UserService } from 'src/app/user/user.service';
import { AbstractSelectorComponent } from 'src/app/_selectors/abstract-selector/abstract-selector.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IDocumentsCollection } from '../documents-collection.interface';
import { DocumentsService } from '../documents.service';

@Component({
  selector: 'app-preview-documents',
  templateUrl: './preview-documents.component.html',
  styleUrls: ['./preview-documents.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentsService}]
})
export class PreviewDocumentsComponent extends PreviewComponent<IDocumentsCollection> {
  displayedColumns = ['id','name','user','path','mime' ,'actions'];
  @ViewChild('user_filter', { static: true }) userFilter: TemplateRef<any>;

  @ViewChild('user', { static: false }) user_id_Ref: AbstractSelectorComponent<any>;

  constructor(protected injector: Injector, public docSrv: DocumentsService<IDocumentsCollection>, public userSrv: UserService<IUser>) {
    super(injector);
  }

  ngOnInit() {
    super.ngOnInit();

    this.userSrv.get({}, undefined, -1).subscribe((res) => {
      this.user_id_Ref.selector.data = res.data;
    });

    this.columns = [
      {
        columnDef: 'id',
        header: 'ID',
        cell: (element: IDocumentsCollection) => `${element.id}`,
        hasFilter: true
      },
       {
        columnDef: 'name',
        header: 'Όνομα',
        cell: (element: IDocumentsCollection) => `${element.name ?? '-'}`,
         hasFilter: true,
      },
      {
        columnDef: 'user',
        header: 'User',
        cell: (element: IDocumentsCollection) => `${element.user ?? '-'}`,
        hasFilter: true,
        filterField: 'user_id',
        filterTemplate: this.userFilter
      },
      {
        columnDef: 'path',
        header: 'Path',
        cell: (element: IDocumentsCollection) => `${element.path}`
      },
      {
        columnDef: 'mime',
        header: 'Mime',
        cell: (element: IDocumentsCollection) => `${element.mime_type}`,
        hasFilter: true,
        filterField: 'mime_type',
      },
    ];
  }

}
