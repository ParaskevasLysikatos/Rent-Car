import { Component, Injector } from '@angular/core';
import { PreviewComponent } from 'src/app/preview/preview.component';
import { ApiService } from 'src/app/_services/api-service.service';
import { ILanguagesCollection } from '../languages-collection.interface';
import { LanguagesService } from '../languages.service';

@Component({
  selector: 'app-preview-languages',
  templateUrl: './preview-languages.component.html',
  styleUrls: ['./preview-languages.component.scss'],
  providers: [{provide: ApiService, useClass: LanguagesService}]
})
export class PreviewLanguagesComponent extends PreviewComponent<ILanguagesCollection> {
  displayedColumns = ['title','id','order', 'actions'];
  constructor(protected injector: Injector,public languageSrv:LanguagesService<ILanguagesCollection>) {
    super(injector);
    this.columns = [
      {
        columnDef: 'title',
        header: 'Τίτλος',
        cell: (element: ILanguagesCollection) => `${element.title}`,
        hasFilter: true,
      },
      {
        columnDef: 'id',
        header: 'Σύνδεσμος',
        cell: (element: ILanguagesCollection) => `${element.id}`
      },
      {
        columnDef: 'order',
        header: 'Κατάταξη',
        cell: (element: ILanguagesCollection) => `${element.order}`
      }
    ];
  }
}
