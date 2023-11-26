import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { IDocumentTypeCollection } from '../document-type-collection.interface';
import { DocumentTypeService } from '../document-type.service';

@Component({
  selector: 'app-preview-document-type',
  templateUrl: './preview-document-type.component.html',
  styleUrls: ['./preview-document-type.component.scss'],
  providers: [{provide: ApiService, useClass: DocumentTypeService}]
})
export class PreviewDocumentTypeComponent extends PreviewComponent<IDocumentTypeCollection> {
  displayedColumns = ['title', 'description','actions'];

  constructor(protected injector: Injector,public docTypeSrv:DocumentTypeService<IDocumentTypeCollection>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: IDocumentTypeCollection) => `${element?.profiles?.el?.title ?? 'Μη μεταφρασμένο'}`,
        hasFilter: true,
      },
      {
        columnDef: 'description',
        header: 'Περιγραφή',
        cell: (element: IDocumentTypeCollection) => `${element?.profiles?.el?.description ?? '-'}`,
        hasFilter: true,
      },
    ];
  }
}
